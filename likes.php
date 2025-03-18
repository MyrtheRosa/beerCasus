<?php
include 'connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bier_id = intval($_POST['bier_id']);
    $action = $_POST['action']; 

    //LIKES EN DISLIKE MATH
    if ($action === 'like') {
        $query = "UPDATE bier SET likes = likes + 1 WHERE id = ?";
    } elseif ($action === 'dislike') {
        $query = "UPDATE bier SET likes = likes - 1 WHERE id = ?";
    }

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $bier_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: index.php"); 
}
?>