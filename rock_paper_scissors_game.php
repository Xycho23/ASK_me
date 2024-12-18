<?php
session_start();

// Initialize players
if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    header('Location: index.php'); // Redirect to player input screen if no players are set
    exit;
}

$players = $_SESSION['players'];
$totalPlayers = count($players);

// Select a random player for the current turn
if (!isset($_SESSION['currentTurn'])) {
    $_SESSION['currentTurn'] = array_rand($players); // Randomly select a starting player
}

$currentTurn = $_SESSION['currentTurn'];
$playerName = $players[$currentTurn];

// Define the hand gestures
$gestures = ['rock', 'paper', 'scissors'];
$questions = [
    "What is your favorite childhood memory?",
    "If you could live anywhere in the world, where would it be?",
    "What's a secret talent you have?",
    "What’s the most embarrassing thing you’ve ever done?",
    "If you could have dinner with any historical figure, who would it be?",
];

$playerChoice = '';
$computerChoice = '';
$result = '';
$randomQuestion = '';

// Process player's choice and determine the result
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['choice'])) {
        $playerChoice = $_POST['choice'];
        $computerChoice = $gestures[array_rand($gestures)]; // Randomly select computer's gesture
        $result = determineWinner($playerChoice, $computerChoice);

        if ($result === 'You win!') {
            // If the player wins, randomly select another player
            $_SESSION['currentTurn'] = array_rand($players);
        } elseif ($result === 'Computer wins!') {
            // If the player loses, ask a random question
            $randomQuestion = $questions[array_rand($questions)];
        }
    }
}

// Function to determine the winner
function determineWinner($playerChoice, $computerChoice)
{
    if ($playerChoice === $computerChoice) {
        return 'It\'s a tie!';
    }

    if (
        ($playerChoice === 'rock' && $computerChoice === 'scissors') ||
        ($playerChoice === 'scissors' && $computerChoice === 'paper') ||
        ($playerChoice === 'paper' && $computerChoice === 'rock')
    ) {
        return 'You win!';
    }

    return 'Computer wins!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rock, Paper, Scissors Game</title>
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
            max-width: 600px;
            width: 100%;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .player-name {
            font-size: 20px;
            margin-bottom: 20px;
            color: #3498db;
        }
        .gesture-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .gesture {
            width: 100px;
            height: 100px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .gesture:hover {
            transform: scale(1.1);
        }
        .gesture img {
            width: 100%;
            height: 100%;
        }
        .result, .question {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
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
    <h1>Rock, Paper, Scissors Game</h1>

    <p class="player-name"><?= $playerName ?>'s Turn</p>

    <!-- Hand Gesture Selection -->
    <div class="gesture-container">
        <form method="POST">
            <button type="submit" name="choice" value="rock">
                <img class="gesture" src="fist.png" alt="rock">
            </button>
            <button type="submit" name="choice" value="paper">
                <img class="gesture" src="paper.png" alt="Paper">
            </button>
            <button type="submit" name="choice" value="scissors">
                <img class="gesture" src="scissors.png" alt="Scissors">
            </button>
        </form>
    </div>

    <!-- Show result -->
    <?php if ($result): ?>
        <div class="result">
            <p>You chose <strong><?= ucfirst($playerChoice) ?></strong></p>
            <p>The computer chose <strong><?= ucfirst($computerChoice) ?></strong></p>
            <p><?= $result ?></p>
        </div>
    <?php endif; ?>

    <!-- Show random question if player loses -->
    <?php if ($randomQuestion): ?>
        <div class="question">
            <p>Computer asks: <?= $randomQuestion ?></p>
        </div>
    <?php endif; ?>

    <!-- Back to Game Selection -->
    <button class="back-button" onclick="window.location.href='select_game.php'">Back to Game Selection</button>
</div>

</body>
</html>
