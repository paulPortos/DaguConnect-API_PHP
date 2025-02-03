<?php

namespace DaguConnect\Services;

use Exception;

trait FileUploader
{
    private string $baseUrl;

    public function initializeBaseUrl()
    {
        $this->baseUrl = 'http://' . $_SERVER['HTTP_HOST']; // Auto-detect domain
        // $this->baseUrl = 'http://' . $_ENV['DOMAIN']; // Use IP Address if needed
    }

    public function uploadProfilePic($file, $directory): string
    {
        // Ensure baseUrl is initialized
        if (!isset($this->baseUrl)) {
            $this->initializeBaseUrl();
        }

        // Define upload directory relative to document root
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . $directory ;

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

        // Validate file size
        if ($file['size'] > 5000000) {
            throw new Exception("File is too large. Max size allowed is 5MB.");
        }

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            throw new Exception("Error uploading file.");
        }

        // Return full URL including base domain
        return $this->baseUrl . $directory  . $uniqueFileName;
    }
}
