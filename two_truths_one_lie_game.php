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
$chosenPlayer = isset($_SESSION['chosenPlayer']) ? $_SESSION['chosenPlayer'] : $players[$currentTurn];
$confirmPlayer = isset($_SESSION['confirmPlayer']) ? $_SESSION['confirmPlayer'] : null;
$followUpQuestion = isset($_SESSION['followUpQuestion']) ? $_SESSION['followUpQuestion'] : false;

// Function to select a random player who will confirm the truth/lie
function getRandomConfirmPlayer($chosenPlayer, $players) {
    $remainingPlayers = array_diff($players, [$chosenPlayer]);
    return $remainingPlayers[array_rand($remainingPlayers)];
}

// Handle form submission for next turn or confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nextTurn'])) {
        // Reset follow-up question flag
        $followUpQuestion = false;

        // Move to the next turn (pick new players)
        $currentTurn = ($currentTurn + 1) % $totalPlayers;
        $chosenPlayer = $players[$currentTurn];
        $confirmPlayer = getRandomConfirmPlayer($chosenPlayer, $players);

        $_SESSION['currentTurn'] = $currentTurn;
        $_SESSION['chosenPlayer'] = $chosenPlayer;
        $_SESSION['confirmPlayer'] = $confirmPlayer;
        $_SESSION['followUpQuestion'] = $followUpQuestion;

    } elseif (isset($_POST['confirmGuess'])) {
        // Check if the guess was correct
        if ($_POST['guessResult'] === 'correct') {
            // Move to the next turn
            $currentTurn = ($currentTurn + 1) % $totalPlayers;
            $chosenPlayer = $players[$currentTurn];
            $confirmPlayer = getRandomConfirmPlayer($chosenPlayer, $players);

            $_SESSION['currentTurn'] = $currentTurn;
            $_SESSION['chosenPlayer'] = $chosenPlayer;
            $_SESSION['confirmPlayer'] = $confirmPlayer;
            $followUpQuestion = false; // Reset follow-up question flag
        } else {
            // Set the follow-up question flag
            $followUpQuestion = true;
            $_SESSION['followUpQuestion'] = $followUpQuestion;
        }
    }
}

$currentPlayerName = $chosenPlayer; // For displaying the current player's name
$avatarPath = "avatars/player" . ($currentTurn + 1) . ".jpg"; // Adjust path to avatars
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="ask-me.png">
    <title>Two Truths and One Lie</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
 body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }
        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 10px;
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
        .action-button, .back-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .action-button {
            background-color: #3498db;
            color: white;
        }
        .action-button:hover {
            background-color: #2980b9;
        }
        .back-button {
            background-color: #e74c3c;
            color: white;
        }
        .back-button:hover {
            background-color: #c0392b;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }
        /* Same styles as before */
    </style>
</head>
<body>

<div class="game-container">
<img src="ask-me.png" alt="Game Logo" class="logo">
    <h1>Two Truths and One Lie</h1>

    <!-- Instructions for the game -->
    <div class="instructions">
        <p><strong>Instructions:</strong></p>
        <p>1. Each player takes turns sharing three statements about themselves to the group. Two statements should be true, and one should be a lie.</p>
        <p>2. The other players must try to guess which statement is the lie.</p>
        <p>3. The designated guesser will confirm whether the guess is correct.</p>
    </div>
    <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Avatar" class="avatar">
    <p class="player-name">It's <?= htmlspecialchars($currentPlayerName) ?>'s turn!</p>

    <?php if ($followUpQuestion): ?>
        <!-- Follow-up question section -->
        <p class="game-status"><?= htmlspecialchars($confirmPlayer) ?>, your guess was incorrect. Please ask a follow-up question to <?= htmlspecialchars($chosenPlayer) ?>.</p>
        <form method="POST">
            <button type="submit" name="nextTurn" class="action-button">Proceed to Next Turn</button>
        </form>
    <?php else: ?>
        <p class="game-status"><?= htmlspecialchars($chosenPlayer) ?>, please share two truths and one lie.</p>
        <p class="game-status"><?= htmlspecialchars($confirmPlayer) ?> will confirm which one is the lie.</p>

        <form method="POST">
            <label>
                <input type="radio" name="guessResult" value="correct" required>
                The guess was correct
            </label><br>
            <label>
                <input type="radio" name="guessResult" value="wrong" required>
                The guess was wrong
            </label><br>
            <button type="submit" name="confirmGuess" class="action-button">Confirm Guess</button>
        </form>
    <?php endif; ?>

    <button class="back-button" onclick="window.location.href='select_game.php'">Back to Game Selection</button>
</div>

</body>
</html>
