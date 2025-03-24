<?php
session_start();
include 'api.php';

if (!isset($_SESSION["token"])) {
    header("Location: login.php");
    exit();
}

$token = $_SESSION["token"];
$student_id = $_SESSION["student_id"];

$responseProfile = callAPI("GET", "student/{$student_id}", null, $token);
$responseAbsences = callAPI("GET", "student/{$student_id}/absences", null, $token);

if (!$responseProfile || !is_array($responseProfile)) {
    echo "<p>Erreur: Impossible de récupérer les données de l'étudiant.</p>";
    exit();
}

if (isset($responseAbsences) && is_array($responseAbsences)) {
    $absences = $responseAbsences;
} else {
    $absences = [];
}

$first_name = isset($responseProfile["firstName"]) ? $responseProfile["firstName"] : "Prénom inconnu";
$last_name = isset($responseProfile["lastName"]) ? $responseProfile["lastName"] : "Nom inconnu";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($first_name . ' ' . $last_name, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Roboto', sans-serif; }
        body { background: white; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .dashboard-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 900px;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }
        .dashboard-container:hover {
            transform: scale(1.02);
        }
        h1 { color: #333; margin-bottom: 1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; }
        .nav-links { margin-top: 20px; }
        .nav-links a {
            text-decoration: none;
            color: #1f8ef1;
            margin: 0 10px;
            font-weight: 500;
            transition: color 0.3s ease-in-out;
        }
        .nav-links a:hover {
            color: #1569c7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            transition: background-color 0.3s ease-in-out;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #1f8ef1;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f4f4f4;
            transition: background-color 0.3s ease-in-out;
        }
        tr:nth-child(even):hover {
            background-color: #e1e1e1;
        }
        .absence-status {
            padding: 5px;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease-in-out;
        }
        .justified {
            background-color: green; /* Carré vert pour les absences justifiées */
        }
        /* Toutes les absences, justifiées ou non, sont maintenant en vert */
        .unjustified {
            background-color: green; /* Carré vert pour les absences non justifiées aussi */
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h1>Bienvenue, <?= htmlspecialchars($first_name . ' ' . $last_name, ENT_QUOTES, 'UTF-8') ?></h1>

        <div class="nav-links">
            <a href="logout.php">Se déconnecter</a>
        </div>

        <section class="absences">
            <h2>Vos Absences</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Justifiée</th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($absences)): ?>
                        <tr>
                            <td colspan="3">Aucune absence trouvée.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($absences as $absence): ?>
                            <tr>
                                <td><?= htmlspecialchars($absence['absenceDate'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="absence-status <?= 'unjustified' ?>"> <!-- Toutes les absences sont traitées comme 'unjustified' -->
                                        <?= htmlspecialchars($absence['status'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($absence['reason'], ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

</body>
</html>
