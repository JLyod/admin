<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Establish a database connection
$conn = new mysqli("localhost", "root", "", "it-pid");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_budget'])) {
        $name = $_POST['name'];
        $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login
    
        // Include user_id in the INSERT query
        $sql = "INSERT INTO budgets (name, user_id) VALUES ('$name', '$user_id')";
    
        if ($conn->query($sql)) {
            $_SESSION['status'] = "New budget category added successfully!";
            header('Location: admin-dashboard.php'); // Redirect to dashboard
            exit();
        } else {
            echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
        }
    }    

    if (isset($_POST['edit_id'])) {
        $id = $_POST['edit_id'];
        $result = $conn->query("SELECT * FROM budgets WHERE id = $id");

        if ($row = $result->fetch_assoc()) {
            echo '
            <form class="p-3 border rounded bg-light" action="admin-budget-management.php" method="POST">
                <div class="mb-3">
                    <a href="admin-dashboard.php" class="btn btn-success">Back to Dashboard</a>
                </div>
                <input type="hidden" name="update_id" value="' . htmlspecialchars($row['id']) . '">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" value="' . htmlspecialchars($row['name']) . '" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input type="number" class="form-control" name="amount" value="' . htmlspecialchars($row['amount']) . '" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Month</label>
                    <input type="text" class="form-control" name="month" value="' . htmlspecialchars($row['month']) . '" required>
                </div>
                <button type="submit" name="update_budget" class="btn btn-primary">Update</button>
            </form>';
        }
    }

    if (isset($_POST['update_budget'])) {
        $id = $_POST['update_id'];
        $name = $_POST['name'];
        $amount = $_POST['amount'];
        $month = $_POST['month'];
        
        $sql = "UPDATE budgets SET name='$name', amount='$amount', month='$month' WHERE id=$id";

        if ($conn->query($sql)) {
            $_SESSION['status'] = "Budget updated successfully!";
            header('Location: admin-dashboard.php'); // Redirect to dashboard
            exit();
        } else {
            echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
        }
    }

    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        $sql = "DELETE FROM budgets WHERE id = $id";

        if ($conn->query($sql)) {
            $_SESSION['status'] = "Budget deleted successfully!";
            header('Location: admin-dashboard.php'); // Redirect to dashboard
            exit();
        } else {
            echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
        }
    }
}

$conn->close();
?>

<html>
<body>
    <!-- Bootstrap JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
