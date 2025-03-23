<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Je moet inloggen!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bier_id = intval($_POST['bier_id']);
    $rating = intval($_POST['rating']);
    $user_id = $_SESSION['user_id'];

    if ($rating < 1 || $rating > 5) {
        die("Ongeldige rating.");
    }

    // Controleer of gebruiker al een rating heeft gegeven voor dit bier
    $checkQuery = "SELECT * FROM ratings WHERE bier_id = ? AND user_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $bier_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $updateQuery = "UPDATE ratings SET rating = ? WHERE bier_id = ? AND user_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iii", $rating, $bier_id, $user_id);
        $stmt->execute();
    } else {
        $insertQuery = "INSERT INTO ratings (bier_id, user_id, rating) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iii", $bier_id, $user_id, $rating);
        $stmt->execute();
    }

    $stmt->close();
    echo "success";
}
?>

