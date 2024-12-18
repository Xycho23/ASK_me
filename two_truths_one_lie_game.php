<?php
session_start();

// Check if players are set in the session
if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    header('Location: index.php'); // Redirect to player input screen if no players are set
    exit;
}

// Players from the session
$players = $_SESSION['players'];
$totalPlayers = count($players);

// Initialize the current turn and chosen players
$currentTurn = isset($_SESSION['currentTurn']) ? $_SESSION['currentTurn'] : 0;
$chosenPlayer = isset($_SESSION['chosenPlayer']) ? $_SESSION['chosenPlayer'] : null;
$confirmPlayer = isset($_SESSION['confirmPlayer']) ? $_SESSION['confirmPlayer'] : null;
$timerRunning = isset($_SESSION['timerRunning']) ? $_SESSION['timerRunning'] : false;

// Function to select a random player who will confirm the truth/lie
function getRandomConfirmPlayer($chosenPlayer) {
    global $players;
    $remainingPlayers = array_diff($players, [$chosenPlayer]);
    return $remainingPlayers[array_rand($remainingPlayers)];
}

// Choose the current player to share two truths and one lie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nextTurn'])) {
    // Move to the next turn (pick new players)
    $currentTurn = ($currentTurn + 1) % $totalPlayers;
    $chosenPlayer = $players[$currentTurn];
    $confirmPlayer = getRandomConfirmPlayer($chosenPlayer);

    $_SESSION['currentTurn'] = $currentTurn;
    $_SESSION['chosenPlayer'] = $chosenPlayer;
    $_SESSION['confirmPlayer'] = $confirmPlayer;

    // Reset the timer for the next player
    $_SESSION['timerRunning'] = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two Truths and One Lie</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }
        .game-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .instructions {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }
        .player-name {
            font-size: 20px;
            margin-bottom: 20px;
            color: #3498db;
        }
        .game-status {
            font-size: 18px;
            color: #2ecc71;
            margin-top: 20px;
        }
        .timer {
            font-size: 30px;
            color: #e74c3c;
            font-weight: bold;
            margin-top: 20px;
        }
        .action-button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .action-button:hover {
            background-color: #2980b9;
        }
        .back-button {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .back-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="game-container">
    <h1>Two Truths and One Lie</h1>

    <!-- Instructions for the game -->
    <div class="instructions">
        <p><strong>Instructions:</strong></p>
        <p>1. Each player takes turns sharing three statements about themselves. Two statements should be true, and one should be a lie.</p>
        <p>2. The other players must try to guess which statement is the lie.</p>
        <p>3. After the player shares their statements, the designated guesser will confirm whether the guess is correct.</p>
        <p>4. If the guesser guesses correctly, the player will reveal which statement was the lie. If the guesser is wrong, they will be asked a question.</p>
        <p>5. Once confirmed, the game moves to the next player.</p>
    </div>

    <?php if (!$chosenPlayer): ?>
        <p>Select the player to start.</p>
    <?php else: ?>
        <p class="player-name">It's <?= $chosenPlayer ?>'s turn!</p>
        <p class="game-status"><?= $chosenPlayer ?>, please share two truths and one lie to the group.</p>
        <p class="game-status">Then, <?= $confirmPlayer ?> will confirm which one is the lie.</p>
    <?php endif; ?>

    <!-- Timer Section -->
    <?php if ($chosenPlayer): ?>
        <div id="timer" class="timer">30</div>
        <script>
            let timeLeft = 30;
            const timerElement = document.getElementById("timer");

            function updateTimer() {
                if (timeLeft > 0) {
                    timeLeft--;
                    timerElement.innerText = timeLeft;
                } else {
                    clearInterval(timerInterval);
                    alert("Time's up! Moving to next player.");
                    document.querySelector("form").submit();
                }
            }

            const timerInterval = setInterval(updateTimer, 1000);
        </script>
    <?php endif; ?>

    <form method="POST">
        <button type="submit" name="nextTurn" class="action-button">Next Turn</button>
    </form>

    <button class="back-button" onclick="window.location.href='select_game.php'">Back to Game Selection</button>
</div>

</body>
</html>
