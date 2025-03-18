<?php
include 'connect.php';

// Perform query
$result = $conn->query("SELECT * FROM bier ORDER BY likes DESC, naam ASC");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bier Casus</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Brouwer</th>
                    <th>Likes</th>
                    <th>Like/Dislike</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="tr-body">
                        <td><?php echo $row["naam"] ?></td>
                        <td><?php echo $row["brouwer"] ?></td>
                        <td><?php echo $row["likes"] ?></td>
                        <?php
                        echo "<td>
                            <form method='POST' action='likes.php'>                            
                                <input type='hidden' name='bier_id' value='" . $row['id'] . "'>
                                <button type='submit' class='like-btn' name='action' value='like'>
                                <i class='fa-solid fa-thumbs-up'></i>
                                </button>
                                <button type='submit' class='dislike-btn' name='action' value='dislike'>
                                    <i class='fa-solid fa-thumbs-down'></i>
                                </button>
                            </form>
                          </td>"; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>