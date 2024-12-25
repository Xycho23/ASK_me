<?php
session_start();

// Check if players are set in the session
if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    header('Location: index.php'); // Redirect to player input screen if no players are set
    exit;
}

// Total number of players
$totalPlayers = 10; // Assuming you have 10 players (player1.jpg to player10.jpg)

// Check if the session has a current player index, if not, initialize it
if (!isset($_SESSION['currentPlayerIndex'])) {
    $_SESSION['currentPlayerIndex'] = 0;  // Start with player 1 (index 0)
}

// Check if the form has been submitted for the "Next Player" button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nextPlayer'])) {
    // Move to the next player after their turn
    $_SESSION['currentPlayerIndex'] = ($_SESSION['currentPlayerIndex'] + 1) % $totalPlayers;
}

// Get the current player index from session
$currentPlayerIndex = $_SESSION['currentPlayerIndex'];

// Calculate the current player's number (1-based, i.e., player 1, player 2, etc.)
$currentPlayerName = "Player " . ($currentPlayerIndex + 1);
$avatarPath = "avatars/player" . ($currentPlayerIndex + 1) . ".jpg";  // Adjust path to avatars


$truths = [
    "What is your biggest fear?",
    "Have you ever cheated in a game?",
    "What’s the most embarrassing thing you’ve done?",
    "What’s a secret you've never told anyone?",
    "What is the most awkward thing that has ever happened to you?",
    "Have you ever lied to get out of trouble?",
    "What’s the worst date you’ve ever been on?",
    "Have you ever broken someone’s heart?",
    "If you could switch lives with someone for a day, who would it be?",
    "What’s something you’ve never told your parents?",
    "What’s the craziest thing you’ve done for love?",
    "Have you ever pretended to be sick to skip school or work?",
    "Who in this room would you trust with your life?",
    "What’s the most childish thing you still do?",
    "What’s the most unusual thing you’ve ever eaten?",
    "Have you ever had a crush on someone in this room?",
    "What’s the worst thing you’ve done while angry?",
    "What’s your most embarrassing habit?",
    "If you could relive one moment in your life, what would it be?",
    "What’s the most rebellious thing you’ve done?",
    "Have you ever lied about your age?",
    "What’s your biggest pet peeve?",
    "What’s the most expensive thing you’ve ever bought?",
    "What’s the worst lie you’ve ever told?",
    "Have you ever had a secret crush on a friend?",
    "What’s the worst gift you’ve ever received?",
    "What’s the weirdest dream you’ve ever had?",
    "What’s the most trouble you’ve gotten into at school or work?",
    "Have you ever sent a text to the wrong person?",
    "Have you ever stolen something?",
    "What’s something you’ve always wanted to try but haven’t?",
    "What’s the biggest risk you’ve ever taken?",
    "Have you ever kissed someone you regret?",
    "What’s a habit you’re trying to break?",
    "Have you ever had a crush on a teacher or boss?",
    "What’s the last thing you searched for on your phone?",
    "What’s the worst advice you’ve ever given?",
    "What’s the most embarrassing thing in your internet history?",
    "What’s the most trouble you’ve gotten into with your parents?",
    "Have you ever spread a rumor?",
    "What’s your biggest insecurity?",
    "If you could be invisible for a day, what would you do?",
    "What’s the longest you’ve gone without showering?",
    "Have you ever been caught doing something you shouldn’t?",
    "What’s the worst haircut you’ve ever had?",
    "Have you ever had an embarrassing nickname?",
    "What’s the worst text message you’ve ever sent?",
    "Have you ever had a wardrobe malfunction?",
    "What’s the most childish thing you own?",
    "What’s something you’ve never done but would like to try?",
    "Have you ever been caught sneaking out?",
    "What’s the craziest thing you’ve done on a dare?",
    "What’s the funniest thing that’s ever happened to you in public?",
    "Have you ever stolen something from a store?",
    "What’s something you’ve done to get attention?",
    "What’s the most awkward date you’ve ever been on?",
    "What’s the most embarrassing thing you’ve said to someone you liked?",
    "What’s your biggest turn-off?",
    "Have you ever kissed someone and regretted it?",
    "What’s the last thing you did before going to bed?",
    "What’s the worst thing you’ve ever done to a friend?",
    "Have you ever lied about being in a relationship?",
    "What’s something you’re ashamed of?",
    "What’s the most awkward situation you’ve been in with a friend?",
    "What’s the most embarrassing thing you’ve done in public?",
    "What’s the worst prank you’ve ever played on someone?",
    "What’s the most shocking thing you’ve done?",
    "Have you ever told a lie to get out of a situation?",
    "What’s something you’d never do for money?",
    "Have you ever had a crush on a celebrity?",
    "What’s the most embarrassing thing you’ve done at work?",
    "What’s the weirdest thing you’ve done to impress someone?",
    "What’s the most uncomfortable situation you’ve been in?",
    "What’s the most embarrassing thing you’ve done in school?",
    "What’s something you’ve done while drunk that you regret?",
    "Have you ever been caught eavesdropping?",
    "What’s the worst thing you’ve done to get revenge?",
    "Have you ever been in a fight?",
    "What’s something you’ve said that you wish you could take back?",
    "Have you ever told a secret you weren’t supposed to?",
    "What’s the most awkward conversation you’ve had with someone?",
    "What’s the most awkward moment you’ve had in front of a crush?",
    "Have you ever broken something and not admitted it?",
    "What’s something you did as a child that you still remember vividly?",
    "What’s the most embarrassing thing you’ve posted online?",
    "What’s the most immature thing you’ve done recently?",
    "Have you ever made a fool of yourself on social media?",
    "What’s the most embarrassing thing your parents have caught you doing?"
];


$dareTasks = [
    "Dance for one minute.",
    "Do 20 push-ups.",
    "Pretend to be a waiter and take drink orders.",
    "Sing your favorite song loudly.",
    "Try to lick your elbow.",
    "Do your best impersonation of someone in the room.",
    "Act like a chicken for two minutes.",
    "Try to touch your toes while standing for one minute.",
    "Do 10 jumping jacks in a row.",
    "Speak in an accent for the next three rounds.",
    "Post an embarrassing photo on social media.",
    "Do 20 sit-ups.",
    "Let someone draw on your face with a marker.",
    "Imitate a famous person of the group’s choice.",
    "Wear socks on your hands for the next round.",
    "Eat a spoonful of hot sauce.",
    "Do your best dance move in front of everyone.",
    "Try to balance a spoon on your nose for one minute.",
    "Let someone give you a makeover.",
    "Try to touch your nose with your tongue.",
    "Do 10 push-ups while singing a song.",
    "Act like a baby for five minutes.",
    "Wear socks on your hands for the next three rounds.",
    "Talk in a high-pitched voice for the next round.",
    "Do 10 burpees.",
    "Try to juggle three items.",
    "Do an impression of a cartoon character.",
    "Speak only in song lyrics for the next two rounds.",
    "Post a video of you doing a funny dance on your social media.",
    "Try to do a cartwheel.",
    "Let someone else pick an embarrassing song for you to sing.",
    "Do 30 seconds of the chicken dance.",
    "Do 15 squats while saying a tongue twister.",
    "Act like a monkey for two minutes.",
    "Do your best impression of an animal of the group’s choice.",
    "Eat a piece of fruit without using your hands.",
    "Walk backward for one minute.",
    "Try to sing while holding your nose.",
    "Let someone else choose an outfit for you to wear for the next round.",
    "Do 20 crunches.",
    "Speak in only questions for the next five minutes.",
    "Do your best impression of a famous singer.",
    "Take a silly selfie and send it to your best friend.",
    "Dance with no music for one minute.",
    "Pretend to be a mannequin for two minutes.",
    "Try to wiggle your ears.",
    "Do your best impersonation of a teacher or boss.",
    "Let someone else do your makeup.",
    "Imitate a random animal for two minutes.",
    "Try to touch your toes while standing for one minute.",
    "Do 10 push-ups while singing a song.",
    "Give a 30-second fashion show with your current clothes.",
    "Pretend to be a news reporter and give a live report on something.",
    "Act out a movie scene for the group.",
    "Do 15 squats in a row.",
    "Let someone draw on your face with a marker.",
    "Wear your clothes inside out for the next round.",
    "Call a friend and sing a random song to them.",
    "Do a dramatic reading of the last text message you received.",
    "Act like a famous person for three minutes.",
    "Let someone do your hair however they like.",
    "Pretend to be a waiter/waitress and take orders from the group.",
    "Do a 30-second stand-up comedy routine.",
    "Pretend to be a model on a runway.",
    "Do 10 push-ups while singing a song.",
    "Take a spoonful of mustard or ketchup.",
    "Pretend you’re a cat for two minutes.",
    "Do an impression of a movie character for one minute.",
    "Pretend to be a different person for the next five minutes.",
    "Try to juggle with three random objects.",
    "Wear a funny hat for the rest of the game.",
    "Imitate a baby for one minute.",
    "Do 10 jumping jacks while saying your ABCs.",
    "Talk with your mouth full for the next round.",
    "Do 20 jumping jacks.",
    "Do an impression of someone in the group.",
    "Do 15 squats and sing a song.",
    "Give someone a piggyback ride.",
    "Make a funny face and hold it for 30 seconds.",
    "Try to walk like a penguin for one minute.",
    "Let someone pick a song for you to sing.",
    "Act like a robot for two minutes.",
    "Pretend you’re in a horror movie for one minute.",
    "Do a dramatic reading of a song lyric.",
    "Try to do a split.",
    "Do 10 squats while singing your favorite song.",
    "Take a silly photo and share it with the group.",
    "Do 15 push-ups and then give a speech about your favorite food."
];



// Check if the form has been submitted for the "Next Player" button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nextPlayer'])) {
    // Move to the next player after their turn
    $_SESSION['currentPlayerIndex'] = ($_SESSION['currentPlayerIndex'] + 1) % $totalPlayers;
}

// Get the current player index from session
$currentPlayerIndex = $_SESSION['currentPlayerIndex'];

// Handle the Truth/Dare selection and show appropriate question/task
$selectedOption = isset($_POST['choice']) ? $_POST['choice'] : '';
$questionOrTask = '';

if ($selectedOption === 'truth') {
    $questionOrTask = $truths[array_rand($truths)];
} elseif ($selectedOption === 'dare') {
    $questionOrTask = $dareTasks[array_rand($dareTasks)];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="ask-me.png">
    <title>Truth or Dare Game</title>
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

        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 10px;
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
        /* Timer Styles */
        .timer {
            font-size: 24px;
            margin: 20px 0;
            color: #e74c3c;
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
    <h1>Truth or Dare</h1>
    

    <!-- Display current player's avatar and name -->
    <div class="player-info">
        <img src="<?= $avatarPath ?>" alt="Avatar" class="avatar">
        <p class="player-name">It's <?= $currentPlayerName ?>'s turn!</p>
    </div>

    <!-- Timer display -->
    <div class="timer" id="timer">
        1:00
    </div>

    <!-- Display the Truth or Dare Flip Card -->
    <?php if ($selectedOption): ?>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <strong><?= ucfirst($selectedOption) ?></strong>
                </div>
                <div class="flip-card-back">
                    <p><?= $questionOrTask ?></p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Display the options to choose Truth or Dare -->
        <form method="POST">
            <button type="submit" class="game-option" name="choice" value="truth">Truth</button>
            <button type="submit" class="game-option" name="choice" value="dare">Dare</button>
        </form>
    <?php endif; ?>

    <!-- Next Player Button (Form Submission) -->
    <form method="POST" action="truth_or_dare_game.php">
        <button type="submit" class="next-button" name="nextPlayer">Next Player</button>
    </form>

    <!-- Back to Game Selection -->
    <button class="back-button" onclick="window.location.href='select_game.php'">Back to Game Selection</button>
</div>

<!-- JavaScript for Timer Countdown -->
<script>
    // Timer logic (1 minute countdown)
    let timeLeft = 300; // 1 minute in seconds
    const timerElement = document.getElementById('timer');

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            // Timer finished - Trigger action (e.g., next player)
            alert("Time's up! Proceeding to the next player.");
            window.location.href = 'truth_or_dare_game.php'; // Change this to your next player logic
        } else {
            timeLeft--;
        }
    }

    // Start the timer
    const timerInterval = setInterval(updateTimer, 1000);
</script>

</body>
</html>