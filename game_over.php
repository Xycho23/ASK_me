<?php
session_start();

// Check if there is only one player left
if (!isset($_SESSION['players']) || count($_SESSION['players']) > 1) {
    header('Location: index.php'); // Redirect if this page is accessed incorrectly
    exit;
}

$winner = $_SESSION['players'][0]; // The last remaining player
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Over</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #74ebd5 0%, #9face6 100%);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }
        .game-over-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }
        .winner-name {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2ecc71;
            font-weight: bold;
        }
        .restart-button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .restart-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="game-over-container">
    <h1>Game Over!</h1>
    <p class="winner-name">Congratulations, <?= htmlspecialchars($winner) ?>! 🎉</p>
    <button class="restart-button" onclick="window.location.href='rock_paper_scissors_game.php'">Restart Game</button>
</div>

</body>
</html>
