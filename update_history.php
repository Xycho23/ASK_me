<?php
session_start();

// Check if history session data is set and not empty
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];  // Initialize the history if it doesn't exist
}

// Example: Adding history item (this part should be done in your logic when questions are asked)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['player']) && isset($_POST['question'])) {
    $_SESSION['history'][] = [
        'player' => $_POST['player'],
        'question' => $_POST['question'],
    ];
}

// Ensure the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Retrieve the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['player']) || !isset($data['question'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

$player = htmlspecialchars($data['player'], ENT_QUOTES, 'UTF-8');
$question = htmlspecialchars($data['question'], ENT_QUOTES, 'UTF-8');

// Store history in a session variable or database
if (!isset($_SESSION['game_history'])) {
    $_SESSION['game_history'] = [];
}

$_SESSION['game_history'][] = [
    'player' => $player,
    'question' => $question,
    'timestamp' => date('Y-m-d H:i:s'),
];

// Respond with success
echo json_encode(['success' => true]);
?>
