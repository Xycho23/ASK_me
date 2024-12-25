// Set the initial timer duration in seconds
let timeLeft = 330; // Example: 630 seconds (10 minutes and 30 seconds)

// Function to start the countdown timer
function startTimer() {
    const timerDisplay = document.getElementById('timer-display'); // Get the timer display element
    const interval = setInterval(() => {
        // Calculate minutes and seconds
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;

        // Update the display with remaining time in "MM:SS" format
        timerDisplay.textContent = `Remaining Time: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

        // Decrease the time left
        timeLeft--;

        // Stop the timer when it reaches 0
        if (timeLeft < 0) {
            clearInterval(interval);
            timerDisplay.textContent = "Time's up!";
        }
    }, 1000); // Update every second
}

// Automatically start the timer on page load
document.addEventListener('DOMContentLoaded', () => {
    startTimer();
});
