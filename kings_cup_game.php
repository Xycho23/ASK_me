<?php
session_start();

// Check if players are set in the session
if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    header('Location: index.php'); // Redirect to player input screen if no players are set
    exit;
}

$players = $_SESSION['players'];
$totalPlayers = count($players);
$currentPlayerIndex = $_SESSION['currentPlayerIndex'] ?? 0;
$currentPlayerName = "Player " . ($currentPlayerIndex + 1);

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
$avatarPath = "avatars/player" . ($currentPlayerIndex + 1) . ".jpg";
// Game logic to determine the next player after their turn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Randomly draw a card
    $card = array_rand($cardRules);

    // Store the drawn card and associated rule in the session
    $_SESSION['drawnCard'] = $card;
    $_SESSION['cardRule'] = $cardRules[$card];

    // Move to the next player
    $_SESSION['currentPlayerIndex'] = ($currentPlayerIndex + 1) % $totalPlayers;
    header("Location: " . $_SERVER['PHP_SELF']); // Refresh to avoid resubmission
    exit;
}

// Get the drawn card and rule for display
$drawnCard = $_SESSION['drawnCard'] ?? null;
$cardRule = $_SESSION['cardRule'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="ask-me.png">
    <title>Kings Cup Game</title>
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
            display: inline-block;
            width: 100px;
            height: 140px;
            background-color:rgb(255, 187, 0);
            border: 2px solid #ddd;
            border-radius: 8px;
            line-height: 140px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-rule {
            font-size: 18px;
            color: #3498db;
            margin-top: 20px;
        }
        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .button {
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #2ecc71;
        }

        .back-button {
            background-color: #e74c3c;
        }

        .back-button:hover {
            background-color: #c0392b;
        }

        .instructions-button {
            background-color: #3498db;
        }

        .instructions-button:hover {
            background-color: #2980b9;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            text-align: left;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="game-container">
    <h1>Kings Cup Game</h1>
    
    <div class="player-info">
        <img src="<?= $avatarPath ?>" alt="Avatar" class="avatar">
        <p class="player-name">It's <?= $currentPlayerName ?>'s turn!</p>
    </div>

    <?php if ($drawnCard): ?>
        <div class="card-display"><?= $drawnCard ?></div>
        <p class="card-rule"><?= $cardRule ?></p>
    <?php else: ?>
        <div class="card-display">?</div>
        <p class="card-rule">Draw a card to start the game!</p>
    <?php endif; ?>

    <form method="POST">
        <button type="submit" class="button">Draw a Card</button>
    </form>

    <button class="button back-button" onclick="window.location.href='select_game.php'">Back to Game Selection</button>
    <button class="button instructions-button" onclick="document.getElementById('instructions-modal').style.display='block'">Instructions</button>
</div>

<div id="instructions-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('instructions-modal').style.display='none'">&times;</span>
        <h2>Game Instructions</h2>
        <ul>
            <?php foreach ($cardRules as $card => $rule): ?>
                <li><strong><?= $card ?>:</strong> <?= $rule ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<script>
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('instructions-modal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>

</body>
</html>
