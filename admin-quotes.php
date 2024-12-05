<?php
session_start(); // Start the session
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Handle adding a new quote
    if (isset($_POST['quote_text']) && !isset($_POST['update_id'])) {
        // Retrieve form data for adding new quote
        $quote_text = $_POST['quote_text'];
        $author = $_POST['author'];
        $category = $_POST['category'];
        $created_at = $_POST['created_at']; // Get the user input date for created_at

        // Prepare and bind for inserting data
        $stmt = $conn->prepare("INSERT INTO quotes (content, author, category, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $quote_text, $author, $category, $created_at);

        // Execute the query
        if ($stmt->execute()) {
            $_SESSION['status'] = "Data Added Successfully";
            header('Location: admin-dashboard.php'); // Redirect to dashboard
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Handle deleting a quote
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];

        // Prepare and bind for deleting data
        $stmt = $conn->prepare("DELETE FROM quotes WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();

        $stmt->close();
        $_SESSION['status'] = "Data Deleted Successfully";
        header('Location: admin-dashboard.php'); // Redirect to dashboard
        exit();
    }
}

$conn->close();
?>
