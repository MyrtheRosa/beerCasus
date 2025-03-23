<?php
include 'connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bier_id = intval($_POST['bier_id']);
    $action = $_POST['action']; 

    // Haal de cookie ID op
    if (!isset($_COOKIE['user_id'])) {
        $cookie_id = bin2hex(random_bytes(16)); 
        setcookie('user_id', $cookie_id, time() + (86400 * 30), "/"); 
    } else {
        $cookie_id = $_COOKIE['user_id'];
    }

    // Check of de gebruiker al een like heeft gegeven
    $check_query = "SELECT * FROM likes WHERE cookie_id = ? AND bier_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "si", $cookie_id, $bier_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $already_liked = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);

    if ($action === 'like' && !$already_liked) {
        // Voeg een like toe in de likes-tabel
        $insert_query = "INSERT INTO likes (cookie_id, bier_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "si", $cookie_id, $bier_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Update het aantal likes
        $query = "UPDATE bier SET likes = likes + 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $bier_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } elseif ($action === 'dislike' && $already_liked) {
        // Verwijder de like uit de likes-tabel
        $delete_query = "DELETE FROM likes WHERE cookie_id = ? AND bier_id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "si", $cookie_id, $bier_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Update het aantal likes
        $query = "UPDATE bier SET likes = likes - 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $bier_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("Location: index.php"); 
}
?>
