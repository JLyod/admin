<?php
session_start(); // Start the session
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "it-pid";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle video deletion
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    
    // Delete video record from the database
    $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['status'] = "Video deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting video: " . $stmt->error;
    }
    $stmt->close();
    header('Location: admin-dashboard.php');
    exit();
}

// Handle video insertion (if the form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $level = $_POST['level'];
    $description = $_POST['description'];
    $video_url = $_POST['video_url'];
    $duration = $_POST['duration'];
    $date_added = $_POST['date_added'];
    $status = $_POST['status'];

    // Handle thumbnail upload
    $thumbnail = $_FILES['thumbnail'];
    $thumbnail_name = $thumbnail['name'];
    $thumbnail_tmp = $thumbnail['tmp_name'];
    $thumbnail_error = $thumbnail['error'];
    $thumbnail_size = $thumbnail['size'];

    if ($thumbnail_error === 0) {
        // Extract file extension
        $thumbnail_ext = pathinfo($thumbnail_name, PATHINFO_EXTENSION);
        
        // Generate a filename based on the video title or ID
        $thumbnail_dest = 'assets/imgs/thumbnails/' . $title . '.' . $thumbnail_ext; // Use title (or video ID if available)
        
        // Move the uploaded thumbnail to the correct folder
        if (move_uploaded_file($thumbnail_tmp, $thumbnail_dest)) {
            // Insert video details into the database
            $stmt = $conn->prepare("INSERT INTO videos (title, category, level, description, thumbnail_url, video_url, duration, date_added, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $title, $category, $level, $description, $thumbnail_dest, $video_url, $duration, $date_added, $status);
            
            if ($stmt->execute()) {
                $_SESSION['status'] = "Video added successfully!";
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Error uploading thumbnail.";
        }
    } else {
        $_SESSION['message'] = "Error with the thumbnail upload.";
    }

    header('Location: admin-dashboard.php');
    exit();
}

// Close the database connection
$conn->close();
?>
