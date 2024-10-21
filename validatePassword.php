<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($password === "GAFP20112011") {
        $_SESSION['user_authenticated1'] = true;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>