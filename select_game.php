<?php
session_start();
if (!isset($_SESSION['players'])) {
    header('Location: index.php'); // Redirect to the player input screen if no players are set
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASK ME - Select Game</title>
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
        header {
            margin-bottom: 20px;
        }
        .logo {
            width: 100px;
            margin-bottom: 10px;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            position: relative;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .game-option {
            background-color: #3498db;
            color: white;
            margin: 10px auto;
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
        .question-icon {
            font-size: 30px;
            color: #3498db;
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            text-align: left;
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
        .modal-header {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .modal-body {
            margin-bottom: 20px;
        }
        .close-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .close-btn:hover {
            background-color: #c0392b;
        }
        .start-game-btn {
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .start-game-btn:hover {
            background-color: #2ecc71;
        }
        @media (max-width: 768px) {
            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
<header>
    <img src="ask-me.png" alt="Game Logo" class="logo">
</header>
<header>
    <a href="index class="back-btn">Back</a>
</header>
<div class="form-container">
    <h1>Choose Your Game</h1>
    <button class="game-option" onclick="selectGame('spin_the_bottle')">Spin the Bottle</button>
    <button class="game-option" onclick="selectGame('truth_or_dare')">Truth or Dare</button>
    <button class="game-option" onclick="selectGame('charades')">Charades</button>
    
    <button class="game-option" onclick="selectGame('never_have_i_ever')">Never Have I Ever</button>
    <button class="game-option" onclick="selectGame('kings_cup')">Kings Cup</button>
    <button class="game-option" onclick="selectGame('hot_potato')">Hot Potato</button>
    <button class="game-option" onclick="selectGame('two_truths_one_lie')">Two Truths and a Lie</button>
    <button class="game-option" onclick="selectGame('liars_dice')">Liar's Dice</button>
    <button class="game-option" onclick="selectGame('rock_paper_scissors')">Rock, Paper, Scissors</button>
    <button class="game-option" onclick="selectGame('the_question_game')">The Question Game</button>

    <!-- Question Mark Icon to Open Modal -->
    <div class="question-icon" onclick="openModal()">?</div>
</div>

<!-- Modal with Instructions -->
<div id="instructionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header" id="modalTitle">Game Instructions</div>
        <div class="modal-body" id="modalBody">
            <!-- Specific game instructions will be injected here by JavaScript -->
        </div>
        <button class="close-btn" onclick="closeModal()">Close</button>
        <button class="start-game-btn" id="startGameBtn" onclick="startGame()">Start Game</button>
    </div>
</div>

<script>
    let selectedGame = '';

    // Function to set the selected game and open the modal
    function selectGame(game) {
        selectedGame = game;
        openModal();
    }

    // Function to open the modal
    function openModal() {
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        const startGameBtn = document.getElementById('startGameBtn');

        // Set the game-specific instructions
        switch (selectedGame) {
            case 'spin_the_bottle':
                modalTitle.textContent = 'Spin the Bottle Instructions';
                modalBody.innerHTML = '<p>The game "Spin the Bottle" is a fun game where players take turns spinning a bottle. When it stops, the player to whom the bottle points must either kiss the person or do a dare.</p>';
                break;
            case 'truth_or_dare':
                modalTitle.textContent = 'Truth or Dare Instructions';
                modalBody.innerHTML = '<p>Players take turns asking each other "Truth or Dare?". If the player chooses "Truth", they must answer a question honestly. If they choose "Dare", they must perform a task given by the asker.</p>';
                break;
            case 'charades':
                modalTitle.textContent = 'Charades Instructions';
                modalBody.innerHTML = '<p>One player acts out a word or phrase without speaking, and the others try to guess it. The faster the guess, the more points the team gets, .</p>';
                break;
            
            case 'never_have_i_ever':
                modalTitle.textContent = 'Never Have I Ever Instructions';
                modalBody.innerHTML = '<p>Players take turns saying a statement starting with "Never have I ever...". If anyone in the group has done that thing, they take a drink or perform a task.</p>';
                break;
            case 'kings_cup':
                modalTitle.textContent = 'Kings Cup Instructions';
                modalBody.innerHTML = '<p>Players take turns drawing cards and must follow the rules associated with each card. The goal is to avoid drawing the last card, which leads to the penalty of drinking the King\'s Cup.</p>';
                break;
            case 'hot_potato':
                modalTitle.textContent = 'Hot Potato Instructions';
                modalBody.innerHTML = '<p>Players sit in a circle and pass a "hot potato" (an object) around while music plays. When the music stops, the player holding the potato is out.</p>';
                break;
            case 'two_truths_one_lie':
                modalTitle.textContent = 'Two Truths and a Lie Instructions';
                modalBody.innerHTML = '<p>Each player takes turns saying three statements: two true and one false. The other players have to guess which statement is the lie.</p>';
                break;
            case 'liars_dice':
                modalTitle.textContent = 'Liar\'s Dice Instructions';
                modalBody.innerHTML = '<p>Each player rolls dice and hides their roll. They then make bids on how many of a certain number are rolled across all players. Other players can challenge the bid if they think it is a lie.</p>';
                break;
            case 'rock_paper_scissors':
                modalTitle.textContent = 'Rock, Paper, Scissors Instructions';
                modalBody.innerHTML = '<p>Each player simultaneously forms a hand gesture representing rock, paper, or scissors. Rock beats scissors, scissors beats paper, and paper beats rock.</p>';
                break;
            case 'the_question_game':
                modalTitle.textContent = 'The Question Game Instructions';
                modalBody.innerHTML = '<p>Players take turns asking each other questions. The first player to fail to answer a question or give a wrong answer loses the round.</p>';
                break;
            default:
                modalTitle.textContent = 'Game Instructions';
                modalBody.innerHTML = '<p>Instructions will be provided based on the selected game.</p>';
        }

        document.getElementById('instructionModal').style.display = 'flex';
    }

    // Function to start the selected game
    function startGame() {
        switch (selectedGame) {
            case 'spin_the_bottle':
                window.location.href = 'spin_the_bottle.php';
                break;
            case 'truth_or_dare':
                window.location.href = 'truth_or_dare_game.php';
                break;
            case 'charades':
                window.location.href = 'charades_game.php';
                break;
            
               
            case 'never_have_i_ever':
                window.location.href = 'never_have_i_ever_game.php';
                break;
            case 'kings_cup':
                window.location.href = 'kings_cup_game.php';
                break;
            case 'hot_potato':
                window.location.href = 'hot_potato_game.php';
                break;
            case 'two_truths_one_lie':
                window.location.href = 'two_truths_one_lie_game.php';
                break;
            case 'liars_dice':
                window.location.href = 'liars_dice_game.php';
                break;
            case 'rock_paper_scissors':
                window.location.href = 'rock_paper_scissors_game.php';
                break;
            case 'the_question_game':
                window.location.href = 'the_question_game_game.php';
                break;
            default:
                alert('Unknown game selected!');
                break;
        }
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('instructionModal').style.display = 'none';
    }
</script>

</body>
</html>
