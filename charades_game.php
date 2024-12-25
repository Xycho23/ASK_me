<?php
session_start();

// Check if players are set in the session
if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    header('Location: index.php'); // Redirect to player input screen if no players are set
    exit;
}

// Initialize game variables
$players = $_SESSION['players'];
$totalPlayers = count($players);
$currentPlayerIndex = isset($_SESSION['currentPlayerIndex']) ? $_SESSION['currentPlayerIndex'] : 0;


$avatarPath = "avatars/player" . ($currentPlayerIndex + 1) . ".jpg";  // Adjust path to avatars
$currentPlayerName = "Player " . ($currentPlayerIndex + 1);

$charadesWords = [
    "Act like a monkey.",
    "Pretend to be a superhero.",
    "Imitate a famous actor.",
    "Mimic a cat.",
    "Pretend you’re swimming.",
    "Pretend to be a chef cooking a meal.",
    "Act like you're flying.",
    "Pretend you’re stuck in traffic.",
    "Pretend you're in a scary movie.",
    "Act like a robot.",
    "Yelling at a referee",
    "Stretching like a cat",
    "Pretending to be a mime",
    "Acting like a statue",
    "Doing a cartwheel",
    "Juggling imaginary objects",
    "Climbing an invisible ladder",
    "Diving into a pool (without water)",
    "Shaking hands with an invisible person",
    "Whispering a secret",
    "Eating an imaginary delicacy",
    "Drinking from an invisible cup",
    "Taking a selfie",
    "Sending a text message",
    "Hacking a computer",
    "Solving a Rubik's Cube",
    "Being incredibly bored",
    "Feeling extreme excitement",
    "Experiencing deep sadness",
    "Being furious",
    "Pretending to be in love",
    "Feeling guilty",
    "Being surprised",
    "Feeling nostalgic",
    "Daydreaming",
    "Meditating",
    "A detective solving a mystery",
    "A superhero saving the world",
    "A villain plotting evil",
    "A teacher giving a lecture",
    "A doctor performing surgery",
    "A chef cooking a gourmet meal",
    "A rockstar performing on stage",
    "A comedian telling jokes",
    "A magician performing a trick",
    "A scientist conducting an experiment",
    "A monkey swinging from vines",
    "A snake slithering",
    "A bird flying",
    "A fish swimming",
    "A horse galloping",
    "A cow mooing",
    "A pig oinking",
    "A chicken clucking",
    "A duck quacking",
    "A dog barking",
    "A clock",
    "A book",
    "A phone",
    "A computer",
    "A car",
    "A bike",
    "A tree",
    "A flower",
    "A house",
    "A ball"
];

// Truth or Dare question for timeout
$timeoutQuestions = [
    "What’s your most embarrassing moment?",
    "Who is your crush right now?",
    "What’s the worst thing you’ve done in school?",
    "What’s a secret you’ve never told anyone?",
    "If you could switch lives with someone for a day, who would it be and why?"
];

// Game logic to determine the next player after their turn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Move to the next player after their turn
    $currentPlayerIndex = ($currentPlayerIndex + 1) % $totalPlayers;
    $_SESSION['currentPlayerIndex'] = $currentPlayerIndex;
}

// Randomly select a player to act and guess
$actorIndex = rand(0, $totalPlayers - 1);
do {
    $guesserIndex = rand(0, $totalPlayers - 1); // Ensure the guesser is not the same as the actor
} while ($guesserIndex === $actorIndex);

// Get the selected players
$actor = $players[$actorIndex];
$guesser = $players[$guesserIndex];

// Charades word for the actor
$charadesPhrase = $charadesWords[array_rand($charadesWords)];

// Question for the guesser if time runs out
$questionToAsk = '';

// Handle the guess and next player logic
if (isset($_POST['guess_correct']) && $_POST['guess_correct'] === 'yes') {
    // Proceed to next player if the guess was correct
    $questionToAsk = ''; // No question for the guesser, continue the game
} elseif (isset($_POST['guess_correct']) && $_POST['guess_correct'] === 'no') {
    // Ask a question if the guess was incorrect
    $questionToAsk = $timeoutQuestions[array_rand($timeoutQuestions)];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="ask-me.png">
    <title>Charades Game</title>
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

        .game-option {
            background-color: #3498db;
            color: white;
            margin: 10px;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 90%;
            display: block;
        }

        .game-option:hover {
            background-color: #2980b9;
        }

        /* Flip Card Styles */
        .flip-card {
            width: 300px;
            height: 300px;
            perspective: 1000px;
            margin: 20px auto;
        }

        .flip-card-inner {
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            position: relative;
        }

        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            color: white;
            border-radius: 8px;
        }
        .avatar {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .flip-card-front {
            background-color: #3498db;
        }

        .flip-card-back {
            background-color: #2ecc71;
            transform: rotateY(180deg);
            color: black;
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

        .timer {
            font-size: 20px;
            margin-top: 20px;
            color: red;
        }
        .instruction-button {
        background-color: #3498db;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .instruction-button:hover {
        background-color: #2980b9;
        transform: scale(1.1);
    }

    .instruction-button:active {
        transform: scale(1);
    }

    .instruction-button svg {
        fill: #ffffff;
    }

        /* Overlay styles */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .overlay-content {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            width: 90%;
            max-width: 400px;
            position: relative;
        }

        .overlay-content h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .overlay-content p {
            font-size: 16px;
            color: #333;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 16px;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        .close-button:hover {
            background-color: #c0392b;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="game-container">
<img src="ask-me.png" alt="Game Logo" class="logo">
    <h1>Charades Game</h1>

    <!-- Instruction Button -->
    <button class="instruction-button" id="instructionBtn" aria-label="Instructions">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#FFFFFF">
        <path d="M0 0h24v24H0V0z" fill="none"/>
        <path d="M11 9h2v2h-2zm0 4h2v4h-2zm1-9C6.48 4 2 8.48 2 14s4.48 10 10 10 10-4.48 10-10S17.52 4 12 4zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
    </svg>
</button>

    <!-- Display current player's avatar and name -->
    <p class="player-name">It's <?= $currentPlayerName ?>'s turn!</p>
        <img src="<?= $avatarPath ?>" alt="Avatar" class="avatar">
    <!-- Show charades word if actor is acting -->
    <?php if (!$questionToAsk): ?>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <strong>Act This Out!</strong>
                </div>
                <div class="flip-card-back">
                    <p><?= $charadesPhrase ?></p>
                </div>
            </div>
        </div>

        <form method="POST">
            <label for="guess_correct">Was the guess correct?</label><br>
            <button type="submit" name="guess_correct" value="yes" class="game-option">Yes</button>
            <button type="submit" name="guess_correct" value="no" class="game-option">No</button>
        </form>
    <?php elseif ($questionToAsk): ?>
        <!-- If the guess is wrong, ask a question to the guesser -->
        <div>
            <h3>Question for <?= $guesser ?>:</h3>
            <p><?= $questionToAsk ?></p>
            <form method="POST">
                <button type="submit" class="game-option">Answer and Next Player</button>
            </form>
        </div>
    <?php endif; ?>
<!-- Next Player Button (Form Submission) -->
<form method="POST" action="charades_game.php">
        <button type="submit" class="next-button" name="nextPlayer">Next Player</button>
    </form>
    
    <!-- Back to Game Selection -->
    <button class="back-button" onclick="window.location.href='select_game.php'">Back to Game Selection</button>
</div>
<!-- Overlay for Instructions -->
<div class="overlay" id="instructionOverlay">
    <div class="overlay-content">
        <button class="close-button" id="closeOverlayBtn">&times;</button>
        <h2>How to Play Charades</h2>
        <p>
            1. A player acts out a word or phrase without speaking.<br>
            2. Other players must guess the word or phrase based on the actions.<br>
            3. The first correct guesser earns a point, and the turn passes to the next player.<br>
            4. Be creative and have fun!
        </p>
    </div>
</div>

<!-- JavaScript for Overlay -->
<script>
    const instructionBtn = document.getElementById('instructionBtn');
    const instructionOverlay = document.getElementById('instructionOverlay');
    const closeOverlayBtn = document.getElementById('closeOverlayBtn');

    // Show the overlay when "Instructions" button is clicked
    instructionBtn.addEventListener('click', () => {
        instructionOverlay.style.display = 'flex';
    });

    // Hide the overlay when "X" button is clicked
    closeOverlayBtn.addEventListener('click', () => {
        instructionOverlay.style.display = 'none';
    });

    // Hide the overlay if clicked outside the content
    instructionOverlay.addEventListener('click', (e) => {
        if (e.target === instructionOverlay) {
            instructionOverlay.style.display = 'none';
        }
    });
</script>
<script>
    // Timer countdown logic can be added if necessary
</script>

</body>
</html>
