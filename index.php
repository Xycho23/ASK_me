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
    <link rel="icon" type="image/png" href="ask-me.png">
    <title>ASK ME </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #fda085;
        }
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
    </style>
</head>
<body>
<header class="text-center mb-4">
    <img src="ask-me.png" alt="ASK ME Logo" class="img-fluid" style="width: 100px;">
    <h1 class="fw-bold">Welcome to ASK ME!</h1>
    <p class="lead">A fun and exciting game to challenge your friends!</p>
</header>
<div class="form-container">
    <h2>Select Number of Players</h2>
    <div id="select-players">
        <label for="player_count" class="form-label">Number of Players:</label>
        <select id="player_count" class="form-select">
            <option value="0" selected>Select</option>
            <?php for ($i = 2; $i <= 10; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?> Players</option>
            <?php endfor; ?>
        </select>
        <button class="btn btn-primary mt-3" onclick="generatePlayerFields()">Next</button>
    </div>
    <form method="POST" class="row g-3 mt-4" id="players-form" style="display: none;">
        <div id="players-container" class="row"></div>
        <div class="col-12 text-center">
            <button type="button" class="btn btn-secondary me-2" onclick="goBack()">Back</button>
            <button type="submit" class="btn btn-success">Start Game</button>
        </div>
    </form>
</div>
<footer class="text-center mt-4">
    <p>&copy; <?= date('Y') ?> ASK ME Game. Created with ❤️.</p>
    <p>Contact us: <a href="mailto:lurcabea22@gmail.com" class="text-decoration-none text-primary">lurcabea22@gmail.com</a></p>
    <button class="btn btn-info mt-2 me-2" onclick="showInfo()">Info</button>
    <button class="btn btn-warning mt-2" onclick="showPrivacyPolicy()">Privacy Policy</button>
</footer>

<!-- Info Overlay -->
<div class="overlay" id="info-overlay">
    <div class="overlay-content">
        <h2>About ASK ME</h2>
        <p>ASK ME is an interactive game designed to bring friends closer together by challenging them with random tasks and questions. It's perfect for parties, family gatherings, and other events!</p>
        <button class="close-btn" onclick="closeInfo()">Close</button>
    </div>
</div>

<div class="overlay" id="info-overlay">
    <div class="overlay-content">
        <h2>About ASK ME</h2>
        <p>ASK ME is a fun, interactive game designed to challenge your friends with random questions and tasks. It’s the perfect addition to any gathering, sparking laughter and friendly competition!</p>
        <button class="close-btn" onclick="closeInfo()">Close</button>
    </div>
</div>

<div class="overlay" id="privacy-policy-overlay">
    <div class="overlay-content">
        <h2>Privacy Policy</h2>
        <p>Our website temporarily stores player names during gameplay for a personalized experience. No sensitive data is stored, and all data is erased after the session ends.</p>
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
            selectPlayers.style.display = 'none';
            playersForm.style.display = 'block';
            playerContainer.innerHTML = '';
            for (let i = 1; i <= playerCount; i++) {
                playerContainer.innerHTML += `
                    <div class="col-md-6 d-flex align-items-center">
                        <img src="avatars/player${i}.jpg" 
                             onerror="this.src='avatars/default.jpg'" 
                             alt="Player ${i} Avatar" 
                             class="avatar me-3">
                        <input type="text" class="form-control" name="players[]" placeholder="Enter Player ${i} Name" required>
                    </div>`;
            }
        } else {
            alert("Please select the number of players.");
        }
    }

    function goBack() {
        document.getElementById('players-form').style.display = 'none';
        document.getElementById('select-players').style.display = 'block';
    }

    function showInfo() {
        document.getElementById('info-overlay').style.display = 'flex';
    }

    function closeInfo() {
        document.getElementById('info-overlay').style.display = 'none';
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
