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
$currentPlayerIndex = isset($_SESSION['currentPlayerIndex']) ? $_SESSION['currentPlayerIndex'] : 0;
$currentPlayer = $players[$currentPlayerIndex];

// Hot Potato Game Logic
$eliminatedPlayers = isset($_SESSION['eliminatedPlayers']) ? $_SESSION['eliminatedPlayers'] : [];
$gameOver = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If the timer goes off, eliminate the current player and move to the next one
    if (isset($_POST['startGame'])) {
        $potatoHolderIndex = array_rand($players);  // Randomly select the holder
        $_SESSION['potatoHolderIndex'] = $potatoHolderIndex;
        $potatoHolder = $players[$potatoHolderIndex];
        
        // Eliminate the current potato holder after the "timer" runs out
        $eliminatedPlayers[] = $potatoHolder;
        $_SESSION['eliminatedPlayers'] = $eliminatedPlayers;
        
        // Remove the player from the game
        unset($players[$potatoHolderIndex]);
        $_SESSION['players'] = array_values($players); // Reindex the array
        
        // Check if there is only one player left (game over)
        if (count($players) <= 1) {
            $gameOver = true;
        } else {
            // Update current player index after elimination
            $currentPlayerIndex = ($currentPlayerIndex + 1) % count($players);
            $_SESSION['currentPlayerIndex'] = $currentPlayerIndex;
        }
    }
}

$remainingPlayers = implode(', ', $players); // Show remaining players
$eliminatedList = implode(', ', $eliminatedPlayers); // Show eliminated players
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hot Potato Game</title>
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
        .game-status {
            font-size: 18px;
            color: #2ecc71;
            margin-top: 20px;
        }
        .eliminated {
            font-size: 18px;
            color: #e74c3c;
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

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            overflow: auto;
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Timer styles */
        .timer {
            font-size: 30px;
            color: #e74c3c;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="game-container">
    <h1>Hot Potato Game</h1>
    <p class="player-name">Current Player: <?= $currentPlayer ?></p>
    
    <?php if ($gameOver): ?>
        <p class="game-status">Game Over! The winner is: <?= $players[0] ?></p>
        <p>Game over! Would you like to play again?</p>
        <button class="action-button" onclick="window.location.href='index.php'">Play Again</button>
    <?php else: ?>
        <p class="game-status">Remaining Players: <?= $remainingPlayers ?></p>
        <p class="eliminated">Eliminated Players: <?= $eliminatedList ?></p>
        
        <div class="timer" id="timer">30</div>

        <form method="POST">
            <button type="submit" name="startGame" class="action-button" id="startBtn">Pass the Potato!</button>
        </form>
    <?php endif; ?>

    <button class="back-button" onclick="window.location.href='select_game.php'">Back to Game Selection</button>
    <button class="action-button" id="instructionsBtn">How to Play</button>
</div>

<!-- Modal -->
<div id="instructionsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>How to Play Hot Potato</h2>
        <p>The game starts with all players sitting in a circle. One player holds the "potato" (a virtual item in this case), and the goal is to pass the potato without being the one holding it when the timer runs out.</p>
        <p>The game will randomly select a player to hold the potato, and after a short timer, that player is eliminated. The remaining players continue until there is only one player left, who wins the game.</p>
        <p>Remember: The potato is passed around randomly, and players must act quickly before the timer runs out!</p>
        <button class="action-button" onclick="closeModal()">Close</button>
    </div>
</div>

<!-- Background Music -->
<audio id="backgroundMusic" autoplay loop>
    <source src="bg.mp3" type="audio/mp3">
    Your browser does not support the audio element.
</audio>

<script>
// Modal functionality
var modal = document.getElementById("instructionsModal");
var btn = document.getElementById("instructionsBtn");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
    modal.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

function closeModal() {
    modal.style.display = "none";
}

// Close the modal if the user clicks anywhere outside of the modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Timer functionality
var timerDisplay = document.getElementById('timer');
var startButton = document.getElementById('startBtn');
var countdown = 30;  // Initial countdown time (in seconds)

startButton.addEventListener('click', function(event) {
    event.preventDefault();
    startCountdown();
});

function startCountdown() {
    var interval = setInterval(function() {
        countdown--;
        timerDisplay.innerText = countdown;

        if (countdown <= 0) {
            clearInterval(interval);
            alert("Time's up! The player holding the potato is eliminated.");
            document.forms[0].submit();  // Submit the form to eliminate the player
        }
    }, 1000);
}
</script>

</body>
</html>
