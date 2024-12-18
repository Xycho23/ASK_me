<?php
session_start();

// Check if players are set
if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    echo "<p style='color: red;'>No players found. Please go back to the player entry page and start the game.</p>";
    echo "<a href='select_game.php'>Go to Player Entry</a>";
    exit;
}

// Elimination handling
if (!isset($_SESSION['remaining_players'])) {
    $_SESSION['remaining_players'] = $_SESSION['players']; // Initialize with all players
}

$questions = [
    "life" => [
        "What motivates you to keep going?",
        "Biggest life regret?",
        "Your dream goal?",
        "If you could live anywhere, where would it be?",
        "What’s the best advice you’ve ever received?",
        "What’s one thing you’d change about your life?",
        "Who inspires you the most?",
        "What’s the most important lesson you’ve learned so far?",
        "How do you define success?",
        "What’s a habit you’re trying to build?",
        "What’s a habit you’re trying to break?",
        "What’s your proudest achievement?",
        "What’s the hardest decision you’ve ever made?",
        "If money weren’t an issue, what would you do?",
        "What’s something new you’ve tried recently?",
        "How do you handle stress?",
        "What do you value most in life?",
        "What’s a skill you wish you had?",
        "What’s a mistake you learned a lot from?",
        "What’s the best compliment you’ve received?",
        "What does a perfect day look like for you?",
        "What’s a dream you’ve had but never pursued?",
        "Who’s had the biggest impact on your life?",
        "What motivates you to get out of bed in the morning?",
        "What’s a book or movie that changed your perspective?",
        "What’s the most adventurous thing you’ve done?",
        "If you could relive a moment, what would it be?",
        "What’s your biggest hope for the future?",
        "What’s something you’re really passionate about?",
        "What’s one thing you want to achieve this year?",
        "What’s a risk you’ve taken that paid off?",
        "What’s your guilty pleasure?",
        "What’s a secret talent you have?",
        "What’s something that always cheers you up?",
        "What’s your biggest pet peeve?",
        "What motivates you when times get tough?",
        "What’s your favorite way to spend free time?",
        "What’s the best decision you’ve ever made?",
        "What’s something you wish you did more of?",
        "What’s something you wish you did less of?",
        "What’s a place that feels like home to you?",
        "What’s your go-to way to relax?",
        "What’s a personal motto or mantra you live by?",
        "What’s a small win you’ve had recently?",
        "What’s a relationship you’re grateful for?",
        "What’s something you’ve learned about yourself recently?",
        "What’s something you’re excited about right now?",
        "What’s one thing you want to improve about yourself?",
        "What’s your happiest memory?",
        "What’s something that makes you unique?"
    ],
    "relationship" => [
        "Have you ever cheated in a relationship?",
        "Ideal partner traits?",
        "Worst breakup story?",
        "What’s the most romantic thing you’ve ever done?",
        "How do you handle arguments in a relationship?",
        "What’s your love language?",
        "What’s a red flag for you in a relationship?",
        "What’s a green flag for you in a relationship?",
        "How do you show affection?",
        "What’s your idea of a perfect date?",
        "How do you handle long-distance relationships?",
        "What’s the best gift you’ve ever given?",
        "What’s the best gift you’ve ever received?",
        "Have you ever been in love?",
        "What’s the most important quality in a partner?",
        "What’s a dealbreaker for you in a relationship?",
        "How do you rebuild trust after it’s broken?",
        "What’s your most memorable romantic moment?",
        "What’s the longest relationship you’ve had?",
        "What’s your biggest lesson from past relationships?",
        "How do you balance independence in a relationship?",
        "What’s the funniest thing that’s happened on a date?",
        "How do you celebrate anniversaries?",
        "What’s the best advice you’ve received about love?",
        "What’s your biggest fear in a relationship?",
        "What’s your favorite memory with a partner?",
        "How do you handle jealousy?",
        "What’s your view on marriage?",
        "What’s the best thing about being in a relationship?",
        "What’s the hardest thing about being in a relationship?",
        "Have you ever had a crush on a friend?",
        "What’s the most awkward date you’ve been on?",
        "How do you know when you’re in love?",
        "What’s your opinion on online dating?",
        "What’s the best surprise you’ve planned for someone?",
        "How do you express commitment?",
        "What’s your stance on open relationships?",
        "What’s the most challenging part of dating?",
        "How do you handle cultural differences in a relationship?",
        "What’s the sweetest thing a partner has done for you?",
        "What’s your favorite romantic movie or book?",
        "How do you keep the spark alive in a relationship?",
        "What’s a funny misunderstanding you’ve had with a partner?",
        "What’s a relationship goal you have?",
        "What’s your view on second chances in love?",
        "How do you support your partner’s dreams?",
        "What’s something new you’d like to try in a relationship?",
        "How do you handle disagreements about the future?",
        "What’s the best relationship advice you’d give someone?"
    ],
    "problems" => [
        "What's your biggest fear?",
        "A stressful moment you overcame?",
        "How do you handle failure?",
        "What’s the toughest challenge you’ve faced?",
        "How do you deal with rejection?",
        "What’s something you’ve struggled with recently?",
        "How do you cope with anxiety?",
        "What’s a difficult decision you’ve had to make?",
        "What’s a problem you solved creatively?",
        "How do you manage time when overwhelmed?",
        "What’s the last thing you learned the hard way?",
        "How do you confront someone you disagree with?",
        "What’s a mistake you made at work or school?",
        "How do you handle criticism?",
        "What’s your strategy for staying positive?",
        "What’s a fear you’ve conquered?",
        "How do you deal with uncertainty?",
        "What’s a moment when you had to ask for help?",
        "How do you handle financial challenges?",
        "What’s something you regret not doing?",
        "How do you stay motivated during tough times?",
        "What’s a tough conversation you’ve had recently?",
        "How do you deal with toxic relationships?",
        "What’s a setback that taught you something valuable?",
        "How do you handle pressure in high-stakes situations?",
        "What’s the hardest part of making decisions?",
        "How do you stay calm in emergencies?",
        "What’s something you’ve done outside your comfort zone?",
        "How do you approach fixing a mistake?",
        "What’s the biggest challenge you’re facing right now?",
        "How do you deal with comparison to others?",
        "What’s a fear you’re working on overcoming?",
        "How do you resolve conflicts with friends or family?",
        "What’s the hardest part of achieving your goals?",
        "How do you find balance in your life?",
        "What’s a regret you’ve turned into a lesson?",
        "How do you handle unexpected changes?",
        "What’s something you’ve sacrificed for success?",
        "How do you address procrastination?",
        "What’s a recent moment you felt stuck?",
        "How do you keep going after a big failure?",
        "What’s a situation where you had to compromise?",
        "How do you find solutions under pressure?",
        "What’s a time when you had to start over?",
        "How do you build resilience?",
        "What’s a recent challenge that made you stronger?",
        "How do you deal with feeling unappreciated?",
        "What’s a hard truth you’ve learned recently?",
        "How do you face fears of the unknown?"
    ],
    "fun" => [
        "What's your wildest adventure?",
        "Embarrassing childhood memory?",
        "Funniest prank you pulled?",
        "What’s the weirdest food you’ve tried?",
        "What’s your go-to karaoke song?",
        "What’s the funniest thing that’s happened to you?",
        "What’s a skill you wish you had just for fun?",
        "What’s the silliest thing you’ve ever done?",
        "What’s the best costume you’ve worn?",
        "What’s your favorite party game?",
        "What’s the most impulsive thing you’ve done?",
        "What’s the most awkward moment you’ve had?",
        "What’s a random talent you have?",
        "What’s the craziest rumor you’ve heard about yourself?",
        "What’s the most unusual place you’ve visited?",
        "What’s a fun fact about you?",
        "What’s a funny misunderstanding you’ve had?",
        "What’s the most ridiculous thing you’ve bought?",
        "What’s your favorite childhood toy?",
        "What’s the most creative excuse you’ve made?",
        "What’s the funniest joke you know?",
        "What’s the weirdest dream you’ve had?",
        "What’s the funniest thing you’ve seen on the internet?",
        "What’s the strangest hobby you’ve tried?",
        "What’s a funny nickname you’ve been given?",
        "What’s the most unexpected compliment you’ve received?",
        "What’s the craziest dare you’ve accepted?",
        "What’s your most memorable road trip story?",
        "What’s the best prank you’ve been part of?",
        "What’s your funniest pet story?",
        "What’s a silly superstition you believe in?",
        "What’s the most embarrassing song on your playlist?",
        "What’s a random thing you collect?",
        "What’s a funny memory from school?",
        "What’s the most surprising thing you’ve learned recently?",
        "What’s the funniest show or movie you’ve watched?",
        "What’s a joke that always makes you laugh?",
        "What’s a food combination you love but others hate?",
        "What’s the funniest text you’ve sent or received?",
        "What’s a weird talent your friends don’t know about?",
        "What’s your most awkward encounter with a stranger?",
        "What’s the most ridiculous lie you’ve told?",
        "What’s the silliest way you’ve injured yourself?",
        "What’s the most random thing in your bag right now?",
        "What’s the most embarrassing moment you’ve had in public?",
        "What’s your favorite funny meme?",
        "What’s a moment you couldn’t stop laughing?",
        "What’s the quirkiest thing about your personality?",
        "What’s your funniest family tradition?",
        "What’s a silly goal you’ve set for yourself?"
    ]
];


// Function to get a random question
function getRandomQuestion() {
    global $questions;
    $categories = array_keys($questions);
    $randomCategory = $categories[array_rand($categories)];
    return $questions[$randomCategory][array_rand($questions[$randomCategory])];
}

// If no players remain
if (empty($_SESSION['remaining_players'])) {
    echo "<p style='color: green;'>All players have been asked a question! The game is over.</p>";
    echo "<a href='select_game.php'>Back to Game Selection</a>";
    session_destroy();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spin the Bottle</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: #f1f1f1;
            text-align: center;
        }

        header {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .back-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
        }

        .circle {
            position: relative;
            width: 80vw;
            max-width: 400px;
            height: 80vw;
            max-height: 400px;
            border: 5px solid #3498db;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .player-name {
            position: absolute;
            font-size: 14px;
            font-weight: bold;
            color: #333;
            text-align: center;
            transform-origin: center;
            border: 2px solid #3498db;
            border-radius: 50%;
            padding: 5px 10px;
            background: white;
        }

        .bottle {
            position: absolute;
            width: 100px;
            height: 100px;
            background: transparent;
            background-image: url('bottle-icon.png'); /* Replace with your bottle image path */
            background-size: contain;
            background-repeat: no-repeat;
            transform-origin: center 50%;
        }

        button {
            margin-top: 20px;
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c0392b;
        }

        .history {
            margin-top: 40px;
            text-align: left;
            padding: 10px;
            background-color: #ecf0f1;
            width: 300px;
            max-height: 300px;
            overflow-y: auto;
        }

        .history-item {
            margin-bottom: 15px;
        }

        .history-item span {
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <a href="select_game.php" class="back-btn">Back</a>
</header>
<h1>Spin the Bottle</h1>
<div class="circle" id="circle">
    <?php foreach ($_SESSION['remaining_players'] as $index => $player): 
        // Calculate the position of each player dynamically
        $angle = ($index * 360) / count($_SESSION['remaining_players']);
        $x = 50 + 40 * cos(deg2rad($angle));
        $y = 50 + 40 * sin(deg2rad($angle));
    ?>
        <div class="player-name" style="left: <?= $x ?>%; top: <?= $y ?>%; transform: translate(-50%, -50%);">
            <?= htmlspecialchars($player) ?>
        </div>
    <?php endforeach; ?>
    <div class="bottle" id="bottle"></div>
</div>
<button onclick="spinBottle()">Spin the Bottle</button>



<script>
    function spinBottle() {
        const bottle = document.getElementById('bottle');
        const players = <?= json_encode(array_values($_SESSION['remaining_players'])); ?>;
        const playerCount = players.length;

        // Random rotation: 2 full spins + a random angle
        const randomRotation = Math.floor(Math.random() * 360) + (720 * 2); 
        bottle.style.transition = 'transform 3s ease-out';
        bottle.style.transform = `rotate(${randomRotation}deg)`;

        setTimeout(() => {
            const finalAngle = randomRotation % 360;
            const playerIndex = Math.floor(finalAngle / (360 / playerCount));
            const loserName = players[playerIndex];
            const question = "<?= getRandomQuestion(); ?>";

            alert(`${loserName} is the loser! Question: "${question}"`);

            // Store the question history
            if (!window.historyList) window.historyList = [];
            window.historyList.push({ player: loserName, question: question });

            // Update the history container
            const historyContainer = document.getElementById('history-container');
            const historyItem = document.createElement('div');
            historyItem.className = 'history-item';
            historyItem.innerHTML = `<span>${loserName}</span>: ${question}`;
            historyContainer.prepend(historyItem);

            // Save history to session
            fetch('update_history.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ player: loserName, question: question })
            }).then(() => {
                // Remove the loser from the remaining players
                fetch('eliminate_player.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ loser: loserName })
                }).then(() => {
                    location.reload(); // Reload the page to update remaining players
                });
            });
        }, 3000);
    }
</script>
</body>
</html>
