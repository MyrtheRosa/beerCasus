<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'connect.php';

$user_id = $_SESSION['user_id'];

// Haal bieren en persoonlijke ratings op
$query = "
    SELECT b.*, r.rating 
    FROM bier b
    LEFT JOIN ratings r ON b.id = r.bier_id AND r.user_id = ?
    ORDER BY b.naam ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bier Casus</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        function rateBeer(bier_id, rating) {
            fetch('rate.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'bier_id=' + bier_id + '&rating=' + rating
            }).then(() => {
                location.reload(); // Herlaad pagina om de rating te updaten
            });
        }
    </script>
</head>
<body>
    <<div class="logout-container">
    <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Uitloggen</button>
    </form>
</div>


    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Brouwer</th>
                    <th>Jouw Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["naam"]; ?></td>
                        <td><?php echo $row["brouwer"]; ?></td>
                        <td>
                            <?php 
                            $user_rating = $row['rating'] ?? 0;
                            for ($i = 1; $i <= 5; $i++): 
                            ?>
                                <i class="fa-star fa-solid <?php echo ($i <= $user_rating) ? 'active' : ''; ?>"
                                   onclick="rateBeer(<?php echo $row['id']; ?>, <?php echo $i; ?>)"></i>
                            <?php endfor; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
