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
$currentPlayer = $players[$currentPlayerIndex];

// Never Have I Ever statements
$neverHaveIEverStatements = [
    "Never have I ever traveled to another country.",
    "Never have I ever gone skydiving.",
    "Never have I ever eaten sushi.",
    "Never have I ever stayed up all night playing video games.",
    "Never have I ever lied about my age.",
    "Never have I ever gone on a blind date.",
    "Never have I ever broken a bone.",
    "Never have I ever skipped school or work.",
    "Never have I ever sung karaoke.",
    "Never have I ever met a celebrity."
];

// Random follow-up questions (for when a player answers "I Have")
$randomQuestions = [
    "What was the most memorable experience from that?",
    "How did it feel when you did that?",
    "Would you do it again? Why or why not?",
    "Who was with you when you did that?",
    "What did you learn from that experience?",
    "What’s the most embarrassing thing you’ve ever done?",
    "If you could trade lives with someone for a day, who would it be?",
    "What’s your biggest fear?",
    "Have you ever lied to get out of trouble?",
    "If you had a time machine, what would you change about your past?",
    "What’s the weirdest food you’ve ever tried?",
    "If you could live anywhere in the world, where would it be?",
    "What’s your guilty pleasure TV show?",
    "Have you ever been caught talking to yourself?",
    "What’s the funniest joke you know by heart?",
    "If you could only eat one meal for the rest of your life, what would it be?",
    "What’s your biggest pet peeve?",
    "Have you ever had a dream so strange you couldn’t stop thinking about it?",
    "What’s a skill you’ve always wanted to learn?",
    "If you were invisible for a day, what would you do?",
    "What’s the craziest dare you’ve ever taken?",
    "Do you believe in aliens?",
    "If you won the lottery tomorrow, what’s the first thing you’d buy?",
    "What’s the most spontaneous thing you’ve ever done?",
    "Have you ever accidentally sent a text to the wrong person?",
    "If you could have dinner with any fictional character, who would it be?",
    "What’s your go-to karaoke song?",
    "Have you ever completely forgotten someone’s name during a conversation?",
    "What’s the most ridiculous argument you’ve ever had?",
    "What’s your favorite childhood memory?",
    "If you could instantly master any language, which one would you choose?",
    "Have you ever cried at a movie? If so, which one?",
    "What’s the most unusual thing you’ve ever purchased?",
    "If you had to live without one of your senses, which one would you choose?",
    "What’s your dream job?",
    "If you could time travel, would you go to the past or the future?",
    "Have you ever had a paranormal experience?",
    "What’s your favorite holiday tradition?",
    "What’s the most expensive thing you’ve ever lost?",
    "Do you believe in fate or coincidence?",
    "Have you ever walked into a glass door?",
    "If you could have any superpower, what would it be?",
    "What’s the weirdest talent you have?",
    "Have you ever been mistaken for someone else?",
    "If you could switch lives with a celebrity, who would it be?",
    "What’s the worst haircut you’ve ever had?",
    "What’s your favorite conspiracy theory?",
    "Have you ever tried to learn an instrument but failed?",
    "What’s the worst movie you’ve ever seen?",
    "If you could relive one day of your life, which would it be?",
    "What’s your favorite way to procrastinate?",
    "Have you ever said something you immediately regretted?",
    "What’s the funniest thing that’s ever happened to you at work?",
    "Do you believe in love at first sight?",
    "What’s the worst advice you’ve ever received?",
    "If you could visit any fictional world, where would you go?",
    "Have you ever had a wardrobe malfunction?",
    "What’s your weirdest quirk?",
    "If you could have an unlimited supply of one thing, what would it be?",
    "What’s the last thing you Googled?",
    "If you could trade places with an animal for a day, which one would it be?",
    "What’s the worst gift you’ve ever received?",
    "Have you ever accidentally insulted someone?",
    "What’s your favorite ice cream flavor?",
    "If you could erase one thing from history, what would it be?",
    "What’s the most awkward situation you’ve ever been in?",
    "Do you believe in karma?",
    "If you could live in any historical period, which one would you choose?",
    "Have you ever laughed so hard you cried?",
    "What’s your most irrational fear?",
    "What’s the most random fact you know?",
    "If you could be famous for something, what would it be?",
    "Have you ever won something in a contest or raffle?",
    "What’s the worst job you’ve ever had?",
    "If you could meet your childhood hero, who would it be?",
    "Have you ever broken something and blamed it on someone else?",
    "What’s the most boring thing you’ve ever done?",
    "What’s your favorite comfort food?",
    "If you could swap wardrobes with anyone, who would it be?",
    "What’s your least favorite household chore?",
    "Have you ever forgotten an important date or event?",
    "What’s the longest you’ve ever gone without sleep?",
    "If you could start a band, what would it be called?",
    "What’s the most unusual compliment you’ve ever received?",
    "Do you believe in ghosts?",
    "If you had to eat the same meal every day for a week, what would it be?",
    "What’s the weirdest thing you’ve ever Googled?",
    "Have you ever been caught singing in public?",
    "If you could create a new holiday, what would it celebrate?",
    "What’s your biggest regret?",
    "Have you ever forgotten where you parked your car?",
    "What’s the funniest prank you’ve ever pulled?",
    "What’s your least favorite food?",
    "If you could live in any movie universe, which one would it be?",
    "What’s your favorite childhood TV show?",
    "Have you ever had a crush on a fictional character?",
    "What’s the most unusual thing you’ve ever found?",
    "If you could invent something, what would it be?",
    "What’s your favorite kind of weather?",
    "Have you ever been in a talent show?",
    "What’s the worst date you’ve ever been on?",
    "What’s your favorite holiday?",
    "If you could teleport anywhere right now, where would you go?",
    "What’s the most adventurous thing you’ve ever done?",
    "Have you ever gotten lost in a foreign place?",
    "What’s the most ridiculous thing you’ve ever bought online?",
    "What’s your go-to pizza topping?",
    "If you could master any art form, what would it be?",
    "What’s the best compliment you’ve ever received?",
    "Have you ever been on TV or in a movie?",
    "What’s the strangest coincidence that’s ever happened to you?",
    "What’s the weirdest dream you’ve ever had?",
    "If you could own any animal as a pet, what would it be?",
    "What’s the most awkward thing you’ve ever overheard?",
    "What’s your most prized possession?",
    "If you had a robot assistant, what would you name it?",
    "Have you ever gotten a really bad haircut?",
    "What’s your favorite dessert?",
    "If you could create a new emoji, what would it look like?",
    "Have you ever accidentally spilled a big secret?",
    "What’s your dream vacation destination?",
    "What’s the worst advice you’ve ever given?",
    "If you could switch places with a family member for a day, who would it be?",
    "What’s your favorite hobby?",
    "Have you ever eaten something you didn’t realize was expired?",
    "If you could make any one rule for the world to follow, what would it be?",
    "What’s your favorite type of music?",
    "If you could instantly learn any sport, what would it be?",
    "Have you ever been caught doing something embarrassing?",
    "What’s your favorite childhood toy?",
    "If you could create a theme park, what would it be based on?",
    "What’s the worst thing you’ve ever cooked?",
    "If you could live in any book, which one would it be?",
    "What’s your go-to dance move?",
    "Have you ever locked yourself out of your house or car?",
    "What’s the most unusual pet you’ve ever owned?",
    "If you could time travel, would you rather be famous in the past or anonymous in the future?",
    "What’s the strangest coincidence you’ve ever experienced?",
    "If you could have a superpower for one day, what would it be?",
    "What’s the most creative excuse you’ve ever come up with?",
    "Have you ever accidentally texted the wrong person?",
    "What’s your least favorite mode of transportation?",
    "If you could design your dream home, what would it look like?",
    "What’s your favorite movie genre?",
    "Have you ever stayed up all night binge-watching something?",
    "If you could invent a holiday, what would it celebrate?",
    "What’s the weirdest place you’ve ever fallen asleep?",
    "What’s the most random thing in your bag or pocket right now?",
    "If you could only watch one TV show for the rest of your life, what would it be?",
    "What’s the funniest thing you’ve ever misheard?",
    "What’s the worst fashion trend you’ve ever participated in?",
    "If you could instantly travel to any planet, which one would you choose?",
    "What’s the funniest meme you’ve ever seen?",
    "Have you ever tried something you saw on the internet and failed?",
    "What’s your favorite family tradition?",
    "If you could only listen to one song for the rest of your life, what would it be?",
    "What’s the strangest thing you’ve ever witnessed in public?",
    "What’s your favorite guilty pleasure snack?",
    "If you could meet any historical figure, who would it be?",
    "What’s the worst lie you’ve ever told?",
    "What’s the most awkward text you’ve ever received?",
    "If you could instantly know any piece of trivia, what would it be?",
    "What’s your favorite board game?",
    "What’s the funniest nickname you’ve ever had?",
    "If you could relive one embarrassing moment, which one would it be?",
    "What’s the weirdest superstition you’ve ever followed?",
    "Have you ever tried to cook something and set off the smoke alarm?",
    "If you could make a guest appearance on any TV show, what would it be?",
    "What’s your favorite childhood game?",
    "What’s the weirdest rumor you’ve ever heard about yourself?",
    "If you could design your dream job, what would it be?",
    "What’s the most ridiculous thing you’ve ever done for a dare?",
    "If you could create a new law, what would it be?",
    "What’s the most unusual talent you have?",
    "If you could live in any animated world, which one would it be?",
    "What’s the most useless skill you’ve ever learned?",
    "Have you ever been caught in a super awkward situation?",
    "If you could have dinner with three people, alive or dead, who would they be?",
    "What’s your favorite memory from school?",
    "What’s the worst thing you’ve ever spilled?",
    "If you could change one thing about yourself, what would it be?",
    "What’s the most unusual compliment you’ve ever given someone?",
    "If you could live in any country, where would it be?",
    "What’s your go-to excuse when you’re late?",
    "What’s the most embarrassing thing you’ve ever been caught doing?",
    "If you could switch jobs with anyone for a week, who would it be?",
    "What’s the funniest animal video you’ve ever seen?",
    "If you could create your own TV show, what would it be about?",
    "What’s your favorite way to spend a lazy day?",
    "What’s the most unusual dream you’ve ever had?",
    "If you could own any piece of art, what would it be?",
    "What’s the funniest thing you’ve ever witnessed at work?",
    "If you could instantly solve one world problem, what would it be?",
    "What’s your favorite thing to do on a rainy day?",
    "If you could create a new ice cream flavor, what would it be?",
    "What’s the worst text typo you’ve ever made?",
    "If you could live in any decade, which one would it be?",
    "What’s the strangest thing you’ve ever found in your pocket?",
    "If you could have any celebrity as your best friend, who would it be?",
    "What’s the weirdest way you’ve ever made a friend?",
    "If you could live in any city in the world, where would it be?",
    "What’s the funniest thing you’ve ever overheard?",
    "If you could trade lives with a character in a book, who would it be?",
    "What’s your favorite thing about weekends?",
    "If you could pick a new name for yourself, what would it be?",
    "What’s the weirdest outfit you’ve ever worn?",
    "If you could have a magical pet, what would it be?",
    "What’s the funniest misunderstanding you’ve ever had?",
    "If you could visit any fictional place, where would you go?",
    "What’s your favorite comfort movie?",
    "If you could own any vehicle, what would it be?",
    "What’s the most unusual thing you’ve ever eaten by accident?",
    "What’s your favorite childhood memory involving food?",
    "If you could swap lives with a movie character, who would it be?",
    "What’s the weirdest thing you’ve ever done for a laugh?",
    "If you could build your dream house, what would be its coolest feature?",
    "What’s the most unusual place you’ve ever visited?",
    "If you could create your own theme song, what would it sound like?",
    "What’s the most bizarre thing you’ve ever seen in a store?",
    "If you could write a book, what would it be about?",
    "What’s the funniest advice you’ve ever received?",
    "If you could design your dream vacation, what would it include?",
    "What’s the most random skill you’ve ever tried to learn?",
    "If you could have an unlimited supply of one food, what would it be?",
    "What’s the most awkward email or text you’ve ever sent?",
    "If you could be any character from a video game, who would it be?",
    "What’s the weirdest thing you’ve ever tried to DIY?",
    "If you could have a lifetime supply of any candy, what would it be?",
    "What’s the funniest pet name you’ve ever heard?",
    "If you could switch lives with a cartoon character, who would it be?",
    "What’s the most awkward family moment you’ve experienced?",
    "If you could make a cameo in any movie, what would it be?",
    "What’s the most ridiculous costume you’ve ever worn?",
    "If you could own a time machine, where would you go first?",
    "What’s your favorite thing to do when you’re bored?",
    "If you could live on a boat, plane, or train, which one would you choose?",
    "What’s the strangest thing you’ve ever done on a dare?",
    "If you could have any celebrity guest at your birthday party, who would it be?",
    "What’s the funniest photo you’ve ever taken?",
    "If you could star in any reality TV show, which one would it be?",
    "What’s the most random thing you’ve ever Googled out of curiosity?",
    "If you could be any mythical creature, what would you be?",
    "What’s your favorite thing about your hometown?",
    "If you could instantly redecorate your home, what would you change?",
    "What’s the most unusual thing you’ve ever seen on a menu?",
    "If you could create your own festival, what would it celebrate?",
    "What’s the most ridiculous goal you’ve ever set for yourself?",
    "If you could be any character in a TV series, who would it be?",
    "What’s the funniest commercial you’ve ever seen?",
    "If you could live in any historical era, which one would it be?",
    "What’s the most awkward question you’ve ever been asked?",
    "If you could own any fictional gadget, what would it be?",
    "What’s the weirdest excuse you’ve ever made up?",
    "If you could be the protagonist in any book, who would you be?",
    "What’s your favorite memory from a holiday celebration?",
    "If you could make up a new sport, what would it involve?",
    "What’s the most awkward moment you’ve experienced with a stranger?",
    "If you could redesign any piece of technology, what would it be?",
    "What’s your weirdest memory from childhood?",
    "If you could instantly become an expert in any hobby, what would it be?",
    "What’s your favorite memory of a random act of kindness?",
];

// Game logic to determine the next player after their turn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['response'])) {
        // Check if response was "I Have" and generate a random follow-up question
        if ($_POST['response'] === "I Have") {
            $_SESSION['follow_up_question'] = $randomQuestions[array_rand($randomQuestions)];
        }
    }
    // Move to the next player after their turn
    $currentPlayerIndex = ($currentPlayerIndex + 1) % $totalPlayers;
    $_SESSION['currentPlayerIndex'] = $currentPlayerIndex;
}

// Get the current statement for the player
$neverHaveIEverStatement = $neverHaveIEverStatements[array_rand($neverHaveIEverStatements)];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Never Have I Ever Game</title>
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
            font-size: 18px;
            margin-top: 20px;
        }

        .follow-up-question {
            display: none; /* Initially hidden */
            margin-top: 20px;
            font-size: 18px;
            color: #3498db;
        }
    </style>
</head>
<body>

<div class="game-container">
    <h1>Never Have I Ever Game</h1>
    <p class="player-name">It's <?= $currentPlayer ?>'s turn!</p>
    <p><strong>Statement:</strong> <?= $neverHaveIEverStatement ?></p>

    <!-- Options for the player to answer -->
    <form method="POST">
        <button type="submit" name="response" value="I Have" class="game-option">I Have</button>
        <button type="submit" name="response" value="I Haven't" class="game-option">I Haven't</button>
    </form>

    <?php if (isset($_SESSION['follow_up_question'])): ?>
        <p class="follow-up-question" id="follow-up-question"><?= $_SESSION['follow_up_question'] ?></p>
    <?php endif; ?>

    <!-- Timer -->
    <div id="timer" class="timer">Time Remaining: <span id="time-left">30</span> seconds</div>

    <script>
        let timeLeft = 30; // Timer duration (30 seconds)
        const timerElement = document.getElementById('time-left');
        const followUpQuestionElement = document.getElementById('follow-up-question');
        
        // Start Timer
        function startTimer() {
            const interval = setInterval(() => {
                if (timeLeft > 0) {
                    timeLeft--;
                    timerElement.textContent = timeLeft;
                } else {
                    clearInterval(interval);
                    alert('Time is up! Proceeding to the next player.');
                    window.location.reload(); // Reload to go to the next player
                }
            }, 1000);
        }

        // Show follow-up question when the player clicks "I Have"
        if (document.querySelector('form').elements['response'].value === 'I Have') {
            followUpQuestionElement.style.display = 'block';
        }

        // Function to hide the follow-up question when the next player is selected
        function hideFollowUpQuestion() {
            followUpQuestionElement.style.display = 'none';
            window.location.reload(); // Move to next player
        }

        startTimer(); // Start the timer as soon as the page loads

        // When "Next Player" button is clicked, hide follow-up question and reset
        document.querySelector('.next-button').addEventListener('click', hideFollowUpQuestion);
    </script>

    <!-- Next Player Button -->
    <button class="next-button" onclick="window.location.href='never_have_i_ever_game.php'">Next Player</button>

    <!-- Back to Game Selection -->
    <button class="back-button" onclick="window.location.href='select_game.php'">Back to Game Selection</button>
</div>

</body>
</html>
