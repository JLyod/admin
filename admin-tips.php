<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '_dbconnect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Handle adding a new article
    if (isset($_POST['title']) && !isset($_POST['update_id'])) {
        // Retrieve form data for adding new article
        $title = $_POST['title'];
        $category = $_POST['category'];
        $preview = $_POST['preview'];
        $content = $_POST['content'];
        $quiz_data = $_POST['quiz_data']; // Capture quiz data
        $date_published = $_POST['date_published'];
        $created_at = date("Y-m-d H:i:s"); // Set created_at to the current timestamp
        $status = $_POST['status'];

        // Validate quiz_data as JSON
        if (json_decode($quiz_data) === null) {
            $_SESSION['status'] = "Invalid JSON format for quiz data.";
            header('Location: admin-dashboard.php'); // Redirect to dashboard
            exit();
        }

        // Prepare and bind for inserting data
        $stmt = $conn->prepare("INSERT INTO articles (title, category, preview, content, quiz_data, date_published, created_at, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $title, $category, $preview, $content, $quiz_data, $date_published, $created_at, $status);

        // Execute the query
        if ($stmt->execute()) {
            $_SESSION['status'] = "Tip Added Successfully";
            header('Location: admin-dashboard.php'); // Redirect to dashboard
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Handle deleting an article
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];

        // Prepare and bind for deleting data
        $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();

        $stmt->close();
        $_SESSION['status'] = "Budget Deleted Successfully";
        header("Location: admin-dashboard.php");
        exit();
    }

    // Handle updating an article
    if (isset($_POST['update_id'])) {
        // Retrieve form data for updating the article
        $id = $_POST['update_id'];
        $title = $_POST['title'];
        $category = $_POST['category'];
        $preview = $_POST['preview'];
        $content = $_POST['content'];
        $quiz_data = $_POST['quiz_data']; // Capture quiz data
        $status = $_POST['status'];
        $date_published = $_POST['date_published'];

        // Validate quiz_data as JSON
        if (json_decode($quiz_data) === null) {
            echo "Invalid JSON format for quiz data.";
            exit;
        }

        // SQL query to update the article in the database
        $sql = "UPDATE articles 
                SET title = ?, category = ?, preview = ?, content = ?, quiz_data = ?, status = ?, date_published = ? 
                WHERE id = ?";

        // Prepare and bind the SQL statement
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssssi", $title, $category, $preview, $content, $quiz_data, $status, $date_published, $id);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['status'] = "Update Successfully";
                header("Location: admin-dashboard.php"); // Redirect to dashboard
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>
