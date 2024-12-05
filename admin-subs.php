<?php
// Start the session to store status messages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "it-pid";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subscriptions using MySQLi
$sql = "SELECT * FROM subscription_payments";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($subscription = $result->fetch_assoc()) {
        echo '<tr id="subscription-row-' . $subscription['id'] . '">';
        echo '<td>' . $subscription['id'] . '</td>';
        echo '<td>' . $subscription['user_id'] . '</td>';
        echo '<td>' . $subscription['subscription_id'] . '</td>';
        echo '<td>' . $subscription['amount'] . '</td>';
        echo '<td>' . $subscription['payment_method'] . '</td>';
        echo '<td class="payment-status">' . ($subscription['payment_status'] == 'pending' && $subscription['admin_verified'] == 1 ? 'Completed' : $subscription['payment_status']) . '</td>';
        echo '<td>' . ($subscription['transaction_id']) . '</td>';
        echo '<td>' . $subscription['reference_number'] . '</td>';

        echo '</tr>';
    }
} else {
    echo "No subscriptions found.";
}

?>
