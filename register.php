<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash het wachtwoord
    $cookie_id = bin2hex(random_bytes(16)); // Uniek cookie ID

    // Controleer of de gegenereerde cookie_id al bestaat (extra zekerheid)
    $checkQuery = "SELECT * FROM users WHERE cookie_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $cookie_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($result->num_rows > 0) {
        // Als de cookie_id al bestaat, genereer een nieuwe
        $cookie_id = bin2hex(random_bytes(16));
        $stmt->bind_param("s", $cookie_id);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    // Voeg gebruiker toe aan de database
    $query = "INSERT INTO users (username, password, cookie_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $password, $cookie_id);

    if ($stmt->execute()) {
        setcookie('user_id', $cookie_id, time() + (86400 * 30), "/"); // Sla cookie op
        header("Location: index.php");
        exit();
    } else {
        echo "Fout bij registreren.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <style>
        body {
            background-color: #ffe0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            flex-direction: column;
        }

        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ff99cc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background: #ff66a3;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #ff3385;
        }

        a {
            display: block;
            margin-top: 10px;
            color: #ff3385;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registreren</h2>
        <form method="POST" action="register.php">
            <input type="text" name="username" placeholder="Gebruikersnaam" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit">Registreer</button>
        </form>
        <a href="login.php">Al een account? Log hier in!</a>
    </div>
</body>
</html>