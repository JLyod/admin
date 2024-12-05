<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Function to get analytics data
function getAnalyticsData() {
    // Database connection details
    $servername = "localhost";  
    $username = "root";         
    $password = "";            
    $dbname = "it-pid"; 

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to get user count per month
    $sql = "SELECT DATE_FORMAT(created_at, '%m') AS month, 
            COUNT(user_id) AS user_count
            FROM users
            GROUP BY month
            ORDER BY month ASC";
    $result = $conn->query($sql);

    // Initialize data arrays for storing results
    $data = ['labels' => [], 'userData' => []];

    // Fetch data from the result set
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
           $month = DateTime::createFromFormat('m', $row['month'])->format('F');
           $data['labels'][] = $month;
           $data['userData'][] = (int) $row['user_count'];
        }
    } else {
        $data = [
            'labels' => ['June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'userData' => [5, 3, 7, 8, 4, 6, 2]
        ];
    }

    $conn->close();
    return $data;
}

// Fetch all Subscription
function getSubscriptionAnalyticsData() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "it-pid";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to get active subscriptions count per month
    $sql = "SELECT DATE_FORMAT(start_date, '%m') AS month, 
            COUNT(id) AS subscription_count
            FROM user_subscriptions
            WHERE status = 'active'
            GROUP BY month
            ORDER BY month ASC";
    $result = $conn->query($sql);

    // Initialize data arrays for storing results
    $data = ['labels' => [], 'subscriptionData' => []];

    // Fetch data from the result set
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
           $month = DateTime::createFromFormat('m', $row['month'])->format('F');
           $data['labels'][] = $month;
           $data['subscriptionData'][] = (int) $row['subscription_count'];
        }
    } else {
        $data = [
            'labels' => ['June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'subscriptionData' => [0, 0, 0, 0, 0, 0, 0]
        ];
    }

    $conn->close();
    return $data;
}

// Fetch all users for display in the modal
function getUsers() {
    // Database connection details
    $servername = "localhost";  
    $username = "root";         
    $password = "";            
    $dbname = "it-pid"; 

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT user_id, username, f_name, l_name, email FROM users WHERE is_admin = FALSE"; // Exclude admin user
    $result = $conn->query($sql);

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row; // Store user data in an array
        }
    }

    $conn->close();
    return $users;
}

// Handle user deletion if `user_id` is passed in the URL (GET request)
if (isset($_GET['user_id'])) {
    // Database connection details
    $servername = "localhost";  
    $username = "root";         
    $password = "";            
    $dbname = "it-pid"; 

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = intval($_GET['user_id']); // Ensure it's an integer to prevent SQL injection

    // Start a transaction to ensure data consistency
    $conn->begin_transaction();

    try {
        // List of tables that reference user_id
        $tables = ['balances', 'budgets', 'budget_alerts', 'cumulative_balance', 'expenses', 'goals', 'incomes', 'notifications', 'user_subscriptions', 'subscription_payments'];

        // Loop through each table and delete related rows
        foreach ($tables as $table) {
            $sql = "DELETE FROM $table WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close(); // Close each statement after execution
        }

        // Delete from users table
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            // Commit the transaction if all deletions succeed
            $conn->commit();
            $_SESSION['status'] = "User Deleted Successfully";
        } else {
            throw new Exception("Error deleting user: " . $stmt->error);
        }

        // Redirect back to the page after deletion
        header("Location: admin-dashboard.php");
        exit;

    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();
        echo "<script>alert('Transaction failed: " . $e->getMessage() . "');</script>";
    }

    // Close the connection
    $conn->close();
}

?>