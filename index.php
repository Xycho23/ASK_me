<?php
session_start();

// Step 1: Input player names
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['players'])) {
    $players = array_map('trim', $_POST['players']);
    $_SESSION['players'] = $players;
    $_SESSION['loser'] = null;
    header('Location: select_game.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASK ME Game</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(245, 245, 245);
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
            margin-bottom: 30px;
            text-align: center;
        }

        .logo {
            width: 120px;
            margin-bottom: 15px;
        }

        .intro {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 90%;
        }

        h1 {
            font-size: 26px;
            margin-bottom: 20px;
            color: #333;
        }

        select, input[type="text"], button {
            width: 90%;
            padding: 12px;
            margin: 10px auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            display: block;
            text-align: center;
        }

        select {
            cursor: pointer;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .players-list {
            display: none;
        }

        .player-name {
            margin: 8px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .player-name input {
            flex-grow: 1;
            margin-left: 10px;
        }

        footer {
            margin-top: 30px;
            font-size: 14px;
            color: #aaa;
            text-align: center;
        }

        footer a {
            color: #3498db;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        footer .contact {
            margin-top: 10px;
            font-size: 16px;
            color: #444;
        }

        footer .contact a {
            color: #e74c3c;
        }

        /* Privacy Policy Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            justify-content: center;
            align-items: center;
            text-align: center;
            z-index: 1000;
        }

        .overlay-content {
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
        }

        .close-btn {
            background-color: red;
            padding: 10px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        .close-btn:hover {
            background-color: #c0392b;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 22px;
            }

            .intro {
                font-size: 16px;
            }

            select, input[type="text"], button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<header>
    <img src="ask-me.png" alt="ASK ME Logo" class="logo">
    <div class="intro">
        <strong>Welcome to ASK ME!</strong><br>
        A fun and exciting game that challenges your friends with random questions and tasks. Perfect for any gathering!
    </div>
</header>
<div class="form-container">
    <h1>Enter Player Names</h1>
    <div id="select-players">
        <label for="player_count">Select Number of Players:</label>
        <select id="player_count">
            <option value="0" selected>Select</option>
            <?php for ($i = 2; $i <= 10; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?> Players</option>
            <?php endfor; ?>
        </select>
        <button id="start-selection" onclick="generatePlayerFields()">Next</button>
    </div>
    <form method="POST" id="players-form" class="players-list">
        <div id="players-container"></div>
        <button type="submit">Start Game</button>
    </form>
</div>
<footer>
    <p>&copy; <?= date('Y') ?> ASK ME Game. Created with ❤️.</p>
    <p class="contact">For recommendations, contact us at <a href="mailto:askme.game@support.com">lurcabea22@gmail.com
    <p><a href="#" onclick="showPrivacyPolicy()">Privacy Policy</a></p>
</footer>

<!-- Privacy Policy Overlay -->
<div class="overlay" id="privacy-policy-overlay">
    <div class="overlay-content">
        <h2>Privacy Policy</h2>
        <p>Our website runs on sessions to track user interactions and ensure that the game experience is fluid and personalized. Player information such as names is stored temporarily during gameplay. We do not store sensitive personal data, and all information is erased once the session ends.</p>
        <button class="close-btn" onclick="closePrivacyPolicy()">Close</button>
    </div>
</div>

<script>
    function generatePlayerFields() {
        const playerCount = document.getElementById('player_count').value;
        const playerContainer = document.getElementById('players-container');
        const playersForm = document.getElementById('players-form');
        const selectPlayers = document.getElementById('select-players');

        if (playerCount > 0) {
            // Hide player count selection
            selectPlayers.style.display = 'none';
            playersForm.style.display = 'block';

            // Generate input fields for players
            playerContainer.innerHTML = ''; // Clear previous inputs
            for (let i = 1; i <= playerCount; i++) {
                playerContainer.innerHTML += `
                    <div class="player-name">
                        <label for="player_${i}">Player ${i}:</label>
                        <input type="text" id="player_${i}" name="players[]" placeholder="Enter Player ${i}'s Name" required>
                    </div>
                `;
            }
        } else {
            alert("Please select the number of players.");
        }
    }

    function showPrivacyPolicy() {
        document.getElementById('privacy-policy-overlay').style.display = 'flex';
    }

    function closePrivacyPolicy() {
        document.getElementById('privacy-policy-overlay').style.display = 'none';
    }
</script>
</body>
</html>
