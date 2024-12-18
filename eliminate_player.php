<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['loser']) && isset($_SESSION['remaining_players'])) {
        $loserIndex = array_search($data['loser'], $_SESSION['remaining_players']);
        if ($loserIndex !== false) {
            unset($_SESSION['remaining_players'][$loserIndex]);
            $_SESSION['remaining_players'] = array_values($_SESSION['remaining_players']); // Re-index array
        }
    }
}
