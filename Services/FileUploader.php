<?php

namespace DaguConnect\Services;

use Exception;

trait FileUploader
{
    private string $baseUrl;

    public function initializeBaseUrl()
    {
        $this->baseUrl = 'http://' . $_SERVER['HTTP_HOST']; // Auto-detect domain
    }

    public function uploadProfilePic($file, $directory): string
    {
        // Ensure baseUrl is initialized
        if (!isset($this->baseUrl)) {
            $this->initializeBaseUrl();
        }

        // Define upload directory relative to document root
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . $directory;

        // Ensure directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Get file extension
        $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Generate unique filename
        $uniqueFileName = uniqid('profile_', true) . '.' . $imageFileType;
        $targetFile = $uploadDir . $uniqueFileName;

        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, and GIF files are allowed.");
        }

        // Validate file size (max 5MB)
        if ($file['size'] > 5000000) {
            throw new Exception("File is too large. Max size allowed is 5MB.");
        }

        // Resize image to 1080x1080
        if (!$this->resizeImage($file['tmp_name'], $targetFile, 1080, 1080, $mimeType)) {
            throw new Exception("Error resizing image.");
        }

        // Return full URL including base domain
        return $this->baseUrl . $directory . $uniqueFileName;
    }

    private function resizeImage($sourcePath, $destinationPath, $newWidth, $newHeight, $mimeType): bool
    {
        // Create an image resource from the uploaded file
        switch ($mimeType) {
            case 'image/jpeg':
                $srcImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $srcImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $srcImage = imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }

        if (!$srcImage) {
            return false;
        }

        // Get original width and height
        $origWidth = imagesx($srcImage);
        $origHeight = imagesy($srcImage);

        // Create a blank true color image with new dimensions
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Maintain aspect ratio and crop if necessary
        $srcAspect = $origWidth / $origHeight;
        $destAspect = $newWidth / $newHeight;

        if ($srcAspect > $destAspect) {
            // Source is wider than destination, crop width
            $newHeightFit = $newHeight;
            $newWidthFit = (int) ($newHeight * $srcAspect);
            $srcX = (int) (($newWidthFit - $newWidth) / 2);
            $srcY = 0;
        } else {
            // Source is taller than destination, crop height
            $newWidthFit = $newWidth;
            $newHeightFit = (int) ($newWidth / $srcAspect);
            $srcX = 0;
            $srcY = (int) (($newHeightFit - $newHeight) / 2);
        }

        // Resize and crop
        imagecopyresampled($newImage, $srcImage, 0, 0, $srcX, $srcY, $newWidth, $newHeight, $origWidth, $origHeight);

        // Save the resized image
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($newImage, $destinationPath, 90);
                break;
            case 'image/png':
                imagepng($newImage, $destinationPath);
                break;
            case 'image/gif':
                imagegif($newImage, $destinationPath);
                break;
            default:
                return false;
        }

        // Free memory
        imagedestroy($srcImage);
        imagedestroy($newImage);

        return true;
    }
}
