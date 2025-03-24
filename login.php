<?php
session_start();
include 'api.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $student_id = $_POST["student_id"];  // Récupération de l'ID étudiant

    // Mise à jour des paramètres pour correspondre à ce que l'API attend
    $data = ["email" => $email, "password" => $password];

    // Appel à l'API pour la connexion
    $response = callAPI("POST", "auth/login", $data);  // Vérifiez que l'endpoint est correct

    // Debug: Affichage de la réponse complète de l'API
    error_log("Réponse API: " . json_encode($response));  // Ajout dans les logs du serveur

    if (isset($response["token"])) {
        // Si la réponse contient un token, la connexion est réussie
        $_SESSION["token"] = $response["token"];
        $_SESSION["email"] = $response["email"];  // Assurez-vous que cette clé est correcte
        $_SESSION["role"] = $response["role"];  // Assurez-vous que cette clé est correcte
        $_SESSION["student_id"] = $student_id;  // Sauvegarde de l'ID étudiant
        header("Location: dashboard.php");
        exit();
    } else {
        // Si la réponse ne contient pas de token, afficher l'erreur
        $error = "Erreur de connexion. Réponse API: " . json_encode($response);  // Affichage complet de la réponse
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion étudiant</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Roboto', sans-serif; }

        body {
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .login-container:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border 0.3s ease-in-out;
        }

        .input-group input:focus {
            border-color: #4caf50;
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #4caf50;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #45a049;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Connexion</h2>
    <form method="post">
        <div class="input-group">
            <input type="text" name="student_id" placeholder="ID étudiant" required>
        </div>
        <div class="input-group">
            <input type="text" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Mot de Passe" required>
        </div>
        <button type="submit" class="btn">Se connecter</button>
    </form>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
</div>

</body>
</html>
