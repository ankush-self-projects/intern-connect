<?php
require '../includes/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = sanitizeInput($_POST['full_name']);
    $email     = sanitizeInput($_POST['email']);
    $phone     = sanitizeInput($_POST['phone']);
    $duration  = sanitizeInput($_POST['duration']);

    // File Upload
    $cvName = time() . "_" . basename($_FILES["cv"]["name"]);
    $uploadDir = __DIR__ . "/../assets/uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }
    $targetPath = $uploadDir . $cvName;
    move_uploaded_file($_FILES["cv"]["tmp_name"], $targetPath);

    // Insert into DB
    $stmt = $pdo->prepare("INSERT INTO applications (full_name, email, phone, duration, cv_filename) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$full_name, $email, $phone, $duration, $cvName]);

    $application_id = $pdo->lastInsertId();
    redirect("payment.php?id=" . $application_id);
}
?>