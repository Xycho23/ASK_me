<?php
session_start();

// Initialize the players and their dice rolls
if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    header('Location: index.php'); // Redirect to player input screen if no players are set
    exit;
}

$players = $_SESSION['players'];
$totalPlayers = count($players);

// Initialize game state
if (!isset($_SESSION['currentTurn'])) {
    $_SESSION['currentTurn'] = 0;  // Set the first player to start
}

$currentTurn = $_SESSION['currentTurn'];
$playerName = $players[$currentTurn];
$playerDice = isset($_SESSION['playerDice']) ? $_SESSION['playerDice'] : [];

// Dice Roll Simulation (1 to 6 for each player)
function rollDice($numDice = 5) {
    $dice = [];
    for ($i = 0; $i < $numDice; $i++) {
        $dice[] = rand(1, 6); // Roll a die (1 to 6)
    }
    return $dice;
}

// Initialize dice for players if not already set
if (empty($playerDice)) {
    $_SESSION['playerDice'] = rollDice();
    $playerDice = $_SESSION['playerDice'];
}

// Next Turn Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nextTurn'])) {
        $currentTurn = ($currentTurn + 1) % $totalPlayers; // Cycle to the next player
        $_SESSION['currentTurn'] = $currentTurn;
        $_SESSION['playerDice'] = rollDice();  // Roll new dice for the next player

        // Reset some game states as necessary
        $playerName = $players[$currentTurn];
        $playerDice = $_SESSION['playerDice'];
    }

    // Add logic for handling bids and challenges
    if (isset($_POST['bid']) && isset($_POST['bidValue'])) {
        $bidValue = $_POST['bidValue']; // Get the player's bid value
        $_SESSION['bid'] = $bidValue; // Save the bid value for the next player
    }

    if (isset($_POST['challenge'])) {
        $bid = isset($_SESSION['bid']) ? $_SESSION['bid'] : null;
        $result = checkBid($bid, $playerDice);
        if ($result) {
            $message = "You successfully challenged the bid!";
            $_SESSION['loser'] = $players[$currentTurn]; // Record the loser
        } else {
            $message = "The challenge failed. The bid was correct.";
            $_SESSION['loser'] = $players[($currentTurn + 1) % $totalPlayers]; // Next player loses
        }
    }
}

// Check the validity of the bid
function checkBid($bid, $playerDice) {
    // This is a basic check: You can expand it based on your rules.
    $totalDice = array_sum($playerDice);
    return $totalDice >= $bid;
}

// Twist for the Loser
function loserTwist() {
    $twists = [
        "Answer a random question.",
        "Do 10 push-ups.",
        "Imitate a celebrity for 1 minute.",
        "Sing a song loudly in the room.",
        "Make a funny face for the group for 30 seconds."
    ];
    return $twists[array_rand($twists)]; // Pick a random twist
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liar's Dice Game</title>
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
        .dice-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .dice {
            width: 50px;
            height: 50px;
            display: inline-block;
            background-color: #3498db;
            color: white;
            line-height: 50px;
            text-align: center;
            font-size: 24px;
            margin: 5px;
            border-radius: 8px;
        }
        .bid-section {
            margin-top: 20px;
        }
        .button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .button:hover {
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
    <h1>Liar's Dice Game</h1>

    <!-- Game Instructions -->
    <p><strong>Game Instructions:</strong></p>
    <p>Each player rolls five dice. Players take turns bidding on the number of dice showing a certain number, such as "three 4's".</p>
    <p>The next player must either raise the bid or challenge the previous player's bid. If the bid is challenged, the player must reveal their dice.</p>
    <p>If the bid was correct, the challenger loses. If the bid was incorrect, the bidder loses.</p>
    <p>The loser must perform a fun task (or answer a random question as a twist)!</p>

    <!-- Current Player Info -->
    <p class="player-name"><?= $playerName ?>'s Turn</p>

    <!-- Display Dice for Current Player -->
    <div class="dice-container">
        <?php foreach ($playerDice as $die): ?>
            <div class="dice"><?= $die ?></div>
        <?php endforeach; ?>
    </div>

    <!-- Roll Dice Button -->
    <form method="POST">
        <button type="submit" name="rollDice" class="button">Roll Dice</button>
    </form>

    <!-- Bid Section -->
    <div class="bid-section">
        <form method="POST">
            <label for="bidValue">Enter Your Bid:</label>
            <input type="number" id="bidValue" name="bidValue" required min="1" max="30">
            <button type="submit" name="bid" class="button">Submit Bid</button>
        </form>
    </div>

    <!-- Challenge Section -->
    <div class="bid-section">
        <form method="POST">
            <button type="submit" name="challenge" class="button">Challenge the Bid</button>
        </form>
    </div>

    <!-- Next Turn -->
    <form method="POST">
        <button type="submit" name="nextTurn" class="button">Next Turn</button>
    </form>

    <!-- Back to Game Selection -->
    <button class="back-button" onclick="window.location.href='select_game.php'">Back to Game Selection</button>

    <!-- Display Twist for the Loser -->
    <?php if (isset($_SESSION['loser'])): ?>
        <p class="game-status"><?= $_SESSION['loser'] ?> loses! They must: <?= loserTwist() ?></p>
        <?php unset($_SESSION['loser']); ?>
    <?php endif; ?>

    <?php if (isset($message)): ?>
        <p class="game-status"><?= $message ?></p>
    <?php endif; ?>
</div>

</body>
</html>
