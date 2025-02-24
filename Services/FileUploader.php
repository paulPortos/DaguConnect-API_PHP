<?php

namespace DaguConnect\Services;

use Exception;

trait FileUploader
{
    private string $baseUrl;

    public function initializeBaseUrl(): void
    {
        $this->baseUrl = 'http://' . $_SERVER['HTTP_HOST']; // Auto-detect domain
    }

    public function uploadFile($file, $directory): string
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
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Generate unique filename
        $uniqueFileName = uniqid('upload_', true) . '.' . $fileExtension;
        $targetFile = $uploadDir . $uniqueFileName;

        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif',
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ];

        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, GIF, PDF, DOC, DOCX, and TXT files are allowed.");
        }

        // Validate file size (max 10MB)
        if ($file['size'] > 10000000) {
            throw new Exception("File is too large. Max size allowed is 10MB.");
        }

        // If it's an image, resize it
        if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
            if (!$this->resizeImage($file['tmp_name'], $targetFile, 1080, 1080, $mimeType)) {
                throw new Exception("Error resizing image.");
            }
        } else {
            // Move non-image files directly
            if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
                throw new Exception("Error uploading file.");
            }
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
            $newHeightFit = $newHeight;
            $newWidthFit = (int) ($newHeight * $srcAspect);
            $srcX = (int) (($newWidthFit - $newWidth) / 2);
            $srcY = 0;
        } else {
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

    private function isValidFileType($file, $allowedExtensions)
    {
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        return in_array($fileExtension, $allowedExtensions);
    }
}
