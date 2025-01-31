<?php

namespace DaguConnect\Services;

use Exception;

trait FileUploader
{
   

    public function uploadProfilePic($file, $directory): string
    {
        $targetDir = $directory;
        $targetFile = $targetDir . basename($file['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            throw new Exception("File is not an image.");
        }

        // Check file size (limit to 5MB for example)
        if ($file['size'] > 5000000) {
            throw new Exception("File is too large.");
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new Exception("Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            throw new Exception("Sorry, there was an error uploading your file.");
        }

        return $targetFile; // Return the file path to store in the database
    }
}