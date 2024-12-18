<?php
session_start();

// Check if players are set in the session
if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    header('Location: index.php'); // Redirect to player input screen if no players are set
    exit;
}

$players = $_SESSION['players'];
$totalPlayers = count($players);
$currentPlayerIndex = isset($_SESSION['currentPlayerIndex']) ? $_SESSION['currentPlayerIndex'] : 0;
$currentPlayer = $players[$currentPlayerIndex];

// Kings Cup Card Rules
$cardRules = [
    "Ace" => "Waterfall: Everyone starts drinking, you can't stop until the person to your right stops.",
    "2" => "You: Choose someone to drink.",
    "3" => "Me: You drink.",
    "4" => "Floor: Everyone must touch the floor, last person drinks.",
    "5" => "Guys: All the guys drink.",
    "6" => "Chicks: All the girls drink.",
    "7" => "Heaven: Everyone points to the sky, last person drinks.",
    "8" => "Mate: Choose a mate to drink with you.",
    "9" => "Rhyme: Say a word, everyone must rhyme with it, first to fail drinks.",
    "10" => "Categories: Pick a category, everyone must name something in that category, first to fail drinks.",
    "Jack" => "Never Have I Ever: Say something you've never done, everyone who has done it drinks.",
    "Queen" => "Question Master: Ask questions, anyone who answers must drink.",
    "King" => "King's Cup: Pour your drink into the King's Cup. Whoever draws the fourth King must drink it."
];

// Game logic to determine the next player after their turn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Randomly draw a card
    $card = array_rand($cardRules);

    // Store the drawn card and associated rule in the session
    $_SESSION['drawnCard'] = $card;
    $_SESSION['cardRule'] = $cardRules[$card];

    // Move to the next player after their turn
    $currentPlayerIndex = ($currentPlayerIndex + 1) % $totalPlayers;
    $_SESSION['currentPlayerIndex'] = $currentPlayerIndex;
}

// Get the drawn card and rule for display
$drawnCard = isset($_SESSION['drawnCard']) ? $_SESSION['drawnCard'] : null;
$cardRule = isset($_SESSION['cardRule']) ? $_SESSION['cardRule'] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kings Cup Game</title>
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

        .player-name {
            font-size: 20px;
            margin-bottom: 20px;
            color: #3498db;
        }

        .card-display {
            font-size: 30px;
            margin-top: 20px;
            font-weight: bold;
            color: #e74c3c;
        }

        .card-rule {
            font-size: 18px;
            color: #3498db;
            margin-top: 20px;
        }

        .next-button {
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .next-button:hover {
            background-color: #2ecc71;
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
    <h1>Kings Cup Game</h1>
    <p class="player-name">It's <?= $currentPlayer ?>'s turn!</p>
    
    <?php if ($drawnCard): ?>
        <p class="card-display"><?= $drawnCard ?></p>
        <p class="card-rule"><?= $cardRule ?></p>
    <?php else: ?>
        <p class="card-display">Draw a card to start the game!</p>
    <?php endif; ?>

    <!-- Next Player Button -->
    <form method="POST">
        <button type="submit" class="next-button">Draw a Card</button>
    </form>

    <!-- Back to Game Selection -->
    <button class="back-button" onclick="window.location.href='select_game.php'">Back to Game Selection</button>
</div>

</body>
</html>
