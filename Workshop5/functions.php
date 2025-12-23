<?php

function formatName($name) {
    return ucwords(trim($name));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function cleanSkills($string) {
    $skills = explode(",", $string);
    return array_map('trim', $skills);
}

function saveStudent($name, $email, $skillsArray) {
    $skills = implode("|", $skillsArray);
    $data = "$name,$email,$skills\n";

    if (!file_put_contents("students.txt", $data, FILE_APPEND)) {
        throw new Exception("Unable to save student data.");
    }
}

function uploadPortfolioFile($file) {
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    $maxSize = 2 * 1024 * 1024;

    if ($file['size'] > $maxSize) {
        throw new Exception("File size exceeds 2MB.");
    }

    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception("Invalid file type.");
    }

    if (!is_dir("uploads")) {
        throw new Exception("Upload directory not found.");
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = "portfolio_" . time() . "." . $ext;

    if (!move_uploaded_file($file['tmp_name'], "uploads/$newName")) {
        throw new Exception("File upload failed.");
    }

    return $newName;
}
