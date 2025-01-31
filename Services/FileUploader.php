<?php

namespace DaguConnect\Services;

use Exception;

trait FileUploader
{
    public function uploadProfilePic($file, $directory): string
    {
        // Define upload directory relative to document root
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $directory . '/';

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

        // Return web-accessible relative path
        return '/uploads/' . $directory . '/' . $uniqueFileName;
    }
}