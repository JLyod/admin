<?php
$page_title = "IT-PID · Admin";
include('includes/header.php');
include ('admin-analytics.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch analytics data
$analytics = getAnalyticsData();
// Fetch users for the modal
$users = getUsers();
?>

<!--<link rel="stylesheet" href="./assets/css/landing_page.css">-->

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_title; ?></title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <section class="hero">
            <div class="container my-5">
                <?php
                    if(isset($_SESSION['status'])){
                    ?>
                        <div class="alert alert alert-light alert-dismissible fade show" role="alert">
                            <strong></strong> <?php echo $_SESSION['status']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                    <?php
                    unset($_SESSION['status']);
                    }                                
                ?>
                <!-- Logout Button -->
                <div class="d-flex justify-content-between align-items-center">
                    <a class="btn btn-danger w-10" href="logout.php">Log out</a>
                </div> 
                <br>
                <div class="row g-4">
                    <!-- View Data on Users, Premium, and Revenue -->
                    <div class="col-md-4">
                        <div class="bento-item bento-tall">
                            <canvas id="dataAnalyticsChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bento-item bento-tall" data-bs-toggle="modal" data-bs-target="#subscriptionAnalyticsModal">
                            <canvas id="subscriptionAnalyticsChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bento-item bento-tall">
                            <div class="modal-body">
                                <canvas id="revenueChart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Tutorial, Budgeting tips, and Quotes -->
                    <div class="col-md-3">
                        <div class="bento-item" data-bs-toggle="modal" data-bs-target="#tutorialsModal">
                            Manage Video Tutorials
                        </div>
                    </div>
                    <!-- Budgeting Tips -->
                    <div class="col-md-3">
                        <div class="bento-item" data-bs-toggle="modal" data-bs-target="#budgetingTipsModal">
                            Manage Budgeting Tips
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bento-item" data-bs-toggle="modal" data-bs-target="#quotesModal">
                            Manage Quotes
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bento-item" data-bs-toggle="modal" data-bs-target="#budgetCategoryModal">
                            Manage Budget Category
                        </div>
                    </div>
                </div>
                
                <!-------------------------------------- Modals -------------------------------------->
                
                <!-- Modal for Manage Users -->
                <div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content glassmorphism-modal">
                            <div class="modal-header">
                                <h5 class="modal-title" id="usersModalLabel">Manage Users</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h6 class="text-white">Click on a user to delete:</h6>
                                <ul id="userList" class="list-group">
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <?php echo htmlspecialchars($user['f_name'] . ' ' . $user['l_name']); ?>
                                                <a href="admin-analytics.php?user_id=<?php echo $user['user_id']; ?>" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                    Delete
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item">No users found.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> <!-- End for Users -->

                <!-- Modal for Manage Subscription -->
                <div class="modal fade" id="subscriptionAnalyticsModal" tabindex="-1" aria-labelledby="subscriptionAnalyticsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content glassmorphism-modal">
                            <div class="modal-header">
                                <h5 class="modal-title" id="subscriptionAnalyticsModalLabel">Subscription</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Subscription Management Table -->
                                <div class="container mt-3">
                                    <h4>Manage Subscriptions</h4>
                                    
                                    <!-- Placeholder for session messages -->
                                    <?php if (isset($_SESSION['status'])): ?>
                                        <div class="alert alert-info">
                                            <?php
                                                echo $_SESSION['status'];
                                                unset($_SESSION['status']); // Clear session message after displaying
                                            ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Display Subscriptions Table -->
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User ID</th>
                                                <th>Subscription ID</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Payment Status</th>
                                                <th>Transaction ID</th>
                                                <th>Reference Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic content loaded via PHP -->
                                            <?php include 'admin-subs.php'; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div><!-- End for Subscription -->

                <!-- Modal for Manage Quotes -->
                <div class="modal fade" id="quotesModal" tabindex="-1" aria-labelledby="quotesModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content glassmorphism-modal">
                            <div class="modal-header">
                                <h5 class="modal-title" id="quotesModalLabel">Manage Quotes</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Add Quote Form -->
                                <form action="admin-quotes.php" method="POST">
                                    <div class="mb-3">
                                        <label for="quote_text" class="form-label text-white">Quote Text</label>
                                        <textarea class="form-control" id="quote_text" name="quote_text" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="author" class="form-label text-white">Author</label>
                                        <input type="text" class="form-control" id="author" name="author" placeholder="Anonymous">
                                    </div>
                                    <div class="mb-3">
                                        <label for="category" class="form-label text-white">Category</label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="finance">Finance</option>
                                            <option value="savings">Savings</option>
                                            <option value="investment">Investment</option>
                                            <option value="budgeting">Budgeting</option>
                                            <option value="motivation">Motivation</option>
                                            <option value="wealth">Wealth</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="created_at" class="form-label text-white">Created At</label>
                                        <input type="datetime-local" class="form-control" id="created_at" name="created_at" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Post Quote</button>
                                </form>
                                <!-- Quotes Table with Delete and Update Feature -->
                                <hr>
                                <h6 class="text-white">Existing Quotes</h6>
                                <div style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="text-white">
                                                <th>ID</th>
                                                <th>Content</th>
                                                <th>Author</th>
                                                <th>Category</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Establish database connection
                                            $conn = new mysqli("localhost", "root", "", "it-pid");

                                            // Fetch data from the "quotes" table
                                            $result = $conn->query("SELECT * FROM quotes");
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                        <td>{$row['id']}</td>
                                                        <td>{$row['content']}</td>
                                                        <td>{$row['author']}</td>
                                                        <td>{$row['category']}</td>
                                                        <td>{$row['created_at']}</td>
                                                        <td>
                                                            <!-- Delete Form -->
                                                            <form action='admin-quotes.php' method='POST' style='display:inline-block;'>
                                                                <input type='hidden' name='delete_id' value='{$row['id']}'>
                                                                <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>";
                                            }
                                            // Close the connection
                                            $conn->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End for Quotes -->

                <!-- Modal for Manage Video Tutorials -->
                <div class="modal fade" id="tutorialsModal" tabindex="-1" aria-labelledby="tutorialsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
                        <div class="modal-content glassmorphism-modal"> <!-- Apply glassmorphism style here -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="tutorialsModalLabel">Manage Tutorials</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form to Add Video Tutorials -->
                                <form action="admin-vids.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="title" class="form-label text-white">Video Title</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category" class="form-label text-white">Category</label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="basics">Basics</option>
                                            <option value="savings">Savings</option>
                                            <option value="goals">Goals</option>                            
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="level" class="form-label text-white">Level</label>
                                        <select class="form-select" id="level" name="level" required>
                                            <option value="beginner">Beginner</option>
                                            <option value="intermediate">Intermediate</option>
                                            <option value="advanced">Advanced</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-white">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="thumbnail" class="form-label text-white">Upload Thumbnail</label>
                                        <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="video_url" class="form-label text-white">Video URL</label>
                                        <input type="url" class="form-control" id="video_url" name="video_url" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="duration" class="form-label text-white">Duration</label>
                                        <input type="text" class="form-control" id="duration" name="duration" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date_added" class="form-label text-white">Date Added</label>
                                        <input type="datetime-local" class="form-control" id="date_added" name="date_added" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-white">Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-success">Add Video</button>
                                </form>

                                <!-- Divider -->
                                <hr>
                                
                                <!-- Existing Video Tutorials Table -->
                                <h6 class="text-white">Existing Video Tutorials</h6>
                                <div style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="text-white">
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Category</th>
                                                <th>Level</th>
                                                <th>Duration</th>
                                                <th>Date Added</th>
                                                <th>Status</th>
                                                <th>Thumbnail</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Establish database connection
                                            $conn = new mysqli("localhost", "root", "", "it-pid");

                                            // Fetch data from the "videos" table
                                            $result = $conn->query("SELECT * FROM videos");
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                        <td>{$row['id']}</td>
                                                        <td>{$row['title']}</td>
                                                        <td>{$row['category']}</td>
                                                        <td>{$row['level']}</td>
                                                        <td>{$row['duration']}</td>
                                                        <td>{$row['date_added']}</td>
                                                        <td>{$row['status']}</td>
                                                        <td>";
                                                
                                                // Display thumbnail if available
                                                if (!empty($row['thumbnail_url'])) {
                                                    echo "<img src='{$row['thumbnail_url']}' alt='Thumbnail' width='100' height='auto'>";
                                                } else {
                                                    echo "No Thumbnail";
                                                }

                                                echo "</td>
                                                        <td>
                                                            <!-- Delete Form -->
                                                            <form action='admin-vids.php' method='POST' style='display:inline-block;'>
                                                                <input type='hidden' name='delete_id' value='{$row['id']}'>
                                                                <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>";
                                            }
                                            // Close the connection
                                            $conn->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End for Tutorial -->

                <!-- Modal for Manage Budgeting Tips -->
                <?php include 'admin-tips.php'; ?>
                <div class="modal fade" id="budgetingTipsModal" tabindex="-1" aria-labelledby="budgetingTipsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
                        <div class="modal-content glassmorphism-modal">
                            <div class="modal-header">
                                <h5 class="modal-title" id="budgetingTipsModalLabel">Manage Budgeting Tips</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Add Budgeting tips Form -->
                                <form action="admin-tips.php" method="POST">
                                    <div class="mb-3">
                                        <label for="title" class="form-label text-white">Tip Title</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category" class="form-label text-white">Category</label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="basics">Budgeting Basics</option>
                                            <option value="savings">Saving Tips</option>
                                            <option value="goals">Financial Goals</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="preview" class="form-label text-white">Preview</label>
                                        <input type="text" class="form-control" id="preview" name="preview" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="content" class="form-label text-white">Content</label>
                                        <textarea class="form-control" id="content" name="content" rows="6" required placeholder="Type your content. Use &lt;h4&gt; for title, &lt;p&gt; for paragraphs."></textarea>
                                    </div>

                                    <!-- Quiz Data Textarea (Same as Content Textarea) -->
                                    <div class="mb-3">
                                        <label for="quiz_data" class="form-label text-white">Quiz Data (JSON format)</label>
                                        <textarea class="form-control" id="quiz_data" name="quiz_data" rows="10" placeholder='Paste the quiz JSON here.' required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_published" class="form-label text-white">Date Published</label>
                                        <input type="date" class="form-control" id="date_published" name="date_published" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="created_at" class="form-label text-white">Created At</label>
                                        <input type="datetime-local" class="form-control" id="created_at" name="created_at" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-white">Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="published">Published</option>
                                            <option value="unpublished">Unpublished</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">Add Tip</button>
                                </form>
                                <!-- Tips Table with Delete and Update Feature -->
                                <hr>
                                <h6 class="text-white">Existing Budgeting Tips</h6>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="text-white">
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Preview</th>
                                            <th>Date Published</th>
                                            <th>Quiz</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Establish database connection
                                        $conn = new mysqli("localhost", "root", "", "it-pid");

                                        // Fetch data from the "articles" table
                                        $result = $conn->query("SELECT * FROM articles");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$row['id']}</td>
                                                    <td>{$row['title']}</td>
                                                    <td>{$row['category']}</td>
                                                    <td>{$row['preview']}</td>
                                                    <td>{$row['date_published']}</td>
                                                    <td>{$row['quiz_data']}</td>
                                                    <td>{$row['status']}</td>
                                                    <td>
                                                        <!-- Delete Form -->
                                                        <form action='admin-tips.php' method='POST' style='display:inline-block;'>
                                                            <input type='hidden' name='delete_id' value='{$row['id']}'>
                                                            <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                                                        </form>
                                                        <!-- Update Button -->
                                                        <button type='button' class='btn btn-primary btn-sm' 
                                                            data-bs-toggle='modal' 
                                                            data-bs-target='#updateModal' 
                                                            data-id='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' 
                                                            data-title='" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "' 
                                                            data-category='" . htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') . "' 
                                                            data-preview='" . htmlspecialchars($row['preview'], ENT_QUOTES, 'UTF-8') . "' 
                                                            data-content='" . htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8') . "' 
                                                            data-status='" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "' 
                                                            data-date_published='" . htmlspecialchars($row['date_published'], ENT_QUOTES, 'UTF-8') . "' 
                                                            data-quiz_data='" . htmlspecialchars($row['quiz_data'], ENT_QUOTES, 'UTF-8') . "'>
                                                            Update
                                                        </button>
                                                    </td>
                                                </tr>";
                                        }
                                        // Close the connection
                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Modal for Budgeting Tips -->
                <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content glassmorphism-modal">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update Budgeting Tip</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Update form -->
                                <form id="updateForm" method="POST" action="admin-tips.php">
                                    <input type="hidden" id="update_id" name="update_id">
                                    <div class="mb-3">
                                        <label for="update_title" class="form-label text-white">Tip Title</label>
                                        <input type="text" class="form-control" id="update_title" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="update_category" class="form-label text-white">Category</label>
                                        <select class="form-select" id="update_category" name="category" required>
                                            <option value="basics">Budgeting Basics</option>
                                            <option value="savings">Saving Tips</option>
                                            <option value="goals">Financial Goals</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="update_preview" class="form-label text-white">Preview</label>
                                        <input type="text" class="form-control" id="update_preview" name="preview" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="update_content" class="form-label text-white">Content</label>
                                        <textarea class="form-control" id="update_content" name="content" rows="6" required></textarea>
                                    </div>

                                    <!-- Update Quiz Data -->
                                    <div class="mb-3">
                                        <label for="update_quiz_data" class="form-label text-white">Quiz Data (JSON format)</label>
                                        <textarea class="form-control" id="update_quiz_data" name="quiz_data" rows="10" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="update_status" class="form-label text-white">Status</label>
                                        <select class="form-select" id="update_status" name="status" required>
                                            <option value="published">Published</option>
                                            <option value="unpublished">Unpublished</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="update_date_published" class="form-label text-white">Date Published</label>
                                        <input type="date" class="form-control" id="update_date_published" name="date_published" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Tip</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- End for Budgeting Tips -->

                <!-- Modal for Budget Categories -->
                <div class="modal fade" id="budgetCategoryModal" tabindex="-1" aria-labelledby="budgetCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content glassmorphism-modal">
                            <div class="modal-header">
                                <h5 class="modal-title" id="budgetCategoryModalLabel">Existing Budget Categories</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Existing Budgets Table -->
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Month</th>
                                            <th>Color</th>
                                            <th>Priority</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $conn = new mysqli("localhost", "root", "", "it-pid");
                                        $result = $conn->query("SELECT * FROM budgets");

                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$row['id']}</td>
                                                    <td>{$row['user_id']}</td>
                                                    <td>{$row['name']}</td>
                                                    <td>{$row['amount']}</td>
                                                    <td>{$row['date']}</td>
                                                    <td>{$row['month']}</td>
                                                    <td><span style='display:inline-block;width:20px;height:20px;border-radius:50%;background-color:" . htmlspecialchars($row['color']) . ";'></span></td>
                                                    <td>{$row['priority']}</td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <form action='admin-budget-management.php' method='POST' style='display:inline-block;'>
                                                            <input type='hidden' name='edit_id' value='{$row['id']}'>
                                                            <button type='submit' class='btn btn-primary btn-sm'>Edit</button>
                                                        </form>
                                                        
                                                        <!-- Delete Button -->
                                                        <form action='admin-budget-management.php' method='POST' style='display:inline-block;'>
                                                            <input type='hidden' name='delete_id' value='{$row['id']}'>
                                                            <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure to delete?\")'>Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>";
                                        }

                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- End for Budget Categories -->
            </div>
        </section>

        <!-- JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
        <script>
            
            // For Users
            // Fetch users and populate the modal
            document.getElementById('dataAnalyticsChart').addEventListener('click', function() {
                fetch('admin-analytics.php') // Update with the correct PHP script URL
                    .then(response => response.json())
                    .then(data => {
                        const userList = document.getElementById('userList');
                        userList.innerHTML = ''; // Clear any existing users
                        data.forEach(user => {
                            const li = document.createElement('li');
                            li.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                            li.textContent = `${user.username} (${user.email})`;
                            
                            // Create delete button
                            const deleteButton = document.createElement('button');
                            deleteButton.textContent = 'Delete';
                            deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
                            deleteButton.addEventListener('click', function() {
                                deleteUser(user.id);
                            });

                            li.appendChild(deleteButton);
                            userList.appendChild(li);
                        });
                    });

                // Show the modal
                var myModal = new bootstrap.Modal(document.getElementById('usersModal'));
                myModal.show();
            });

            // Delete User Function
            function deleteUser(userId) {
                if (confirm('Are you sure you want to delete this user?')) {
                    fetch('admin-analytics.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ userId: userId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('User deleted successfully!');
                            location.reload(); // Reload to update the list
                        } else {
                            alert('Error deleting user: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }

            // Fetch the user analytics data
            function getUserAnalyticsData() {
                return <?php echo json_encode($analytics); ?>;
            }

            var userAnalytics = getUserAnalyticsData();

            var userCtx = document.getElementById('dataAnalyticsChart').getContext('2d');
            var dataAnalyticsChart = new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: userAnalytics.labels,
                    datasets: [
                        {
                            label: 'Users',
                            data: userAnalytics.userData,
                            backgroundColor: 'rgba(57, 255, 20, 0.5)', 
                            borderColor: 'rgba(57, 255, 20, 1)',
                            borderWidth: 1
                        },
                    ]
                },
                options: {
                    plugins: {
                        tooltip: {
                            bodyColor: 'white',
                        },
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white',
                            }
                        },
                        x: {
                            ticks: {
                                color: 'white',
                            }
                        }
                    },
                    elements: {
                        point: {
                            backgroundColor: 'white'
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 20,
                            right: 20,
                            top: 20,
                            bottom: 20
                        }
                    }
                }
            }); // End of User Analytics
            
            // Fetch the subscription analytics data
            function getSubscriptionAnalyticsData() {
                return <?php echo json_encode(getSubscriptionAnalyticsData()); ?>;
            }

            var subscriptionAnalytics = getSubscriptionAnalyticsData();

            var subscriptionCtx = document.getElementById('subscriptionAnalyticsChart').getContext('2d');
            var subscriptionAnalyticsChart = new Chart(subscriptionCtx, {
                type: 'line',
                data: {
                    labels: subscriptionAnalytics.labels,
                    datasets: [
                        {
                            label: 'Active Subscriptions',
                            data: subscriptionAnalytics.subscriptionData,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)', 
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                    ]
                },
                options: {
                    plugins: {
                        tooltip: {
                            bodyColor: 'white',
                        },
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white',
                            }
                        },
                        x: {
                            ticks: {
                                color: 'white',
                            }
                        }
                    },
                    elements: {
                        point: {
                            backgroundColor: 'white'
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 20,
                            right: 20,
                            top: 20,
                            bottom: 20
                        }
                    }
                }
            }); // End for Subscription Analytics

            // For Update in Budgeting Tips
            document.addEventListener('DOMContentLoaded', function () {
                const updateModal = document.getElementById('updateModal');
                updateModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget; // Button that triggered the modal

                    // Extract data from the button's data-* attributes
                    const id = button.getAttribute('data-id');
                    const title = button.getAttribute('data-title');
                    const category = button.getAttribute('data-category');
                    const preview = button.getAttribute('data-preview');
                    const content = button.getAttribute('data-content');
                    const status = button.getAttribute('data-status');
                    const quiz_data = button.getAttribute('data-quiz_data');
                    const datePublished = button.getAttribute('data-date_published');

                    // Update the modal's fields
                    const modal = this;
                    modal.querySelector('#update_id').value = id;
                    modal.querySelector('#update_title').value = title;
                    modal.querySelector('#update_category').value = category;
                    modal.querySelector('#update_preview').value = preview;
                    modal.querySelector('#update_content').value = content;
                    modal.querySelector('#update_status').value = status;
                    modal.querySelector('#update_quiz_data').value = quiz_data;
                    modal.querySelector('#date_published').value = datePublished;

                    // Populate modal fields with data from the button's attributes
                    updateModal.querySelector('#update_id').value = button.getAttribute('data-id');
                    updateModal.querySelector('#update_title').value = button.getAttribute('data-title');
                    updateModal.querySelector('#update_category').value = button.getAttribute('data-category');
                    updateModal.querySelector('#update_preview').value = button.getAttribute('data-preview');
                    updateModal.querySelector('#update_content').value = button.getAttribute('data-content');
                    updateModal.querySelector('#update_status').value = button.getAttribute('data-status');
                    updateModal.querySelector('#update_quiz_data').value = button.getAttribute('data-quiz_data');
                    updateModal.querySelector('#date_published').value = button.getAttribute('data-date_published');
                });
            }); // End for Update Budgeting Tips

            // Revenue Data for Advertisement
            const revenueData = {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // Example weeks
                datasets: [{
                    label: 'Revenue in Advertisement',
                    data: [1200, 1800, 2500, 2200], // Example revenue data in Pesos
                    borderColor: 'rgba(255, 255, 255, 1)', // Set the line color to white
                    backgroundColor: 'rgba(255, 223, 0, 1)',
                    fill: true,
                    tension: 0.4
                }]
            };

            // Set up the chart
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(ctx, {
                type: 'line', // Choose 'line', 'bar', or other chart types
                data: revenueData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white' // Set the legend text color to white
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '₱' + context.raw; // Format the tooltip value with the Peso symbol
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white' // Set the x-axis tick text color to white
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white' // Set the y-axis tick text color to white
                            }
                        }
                    }
                }
            }); // End for Revenue in Advertisement
        </script>
    </body>
</html>

<style>
    /* Bento Grid  */
   .bento-item {
    height: 200px; /* Retain the original height */
    width: 100%; /* Ensure the item fills its column */
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    transition: transform 0.3s ease, background 0.3s ease; /* Add smooth transition for background */
    background: rgba(255, 255, 255, 0.1); /* Semi-transparent background */
    backdrop-filter: blur(10px); /* Apply blur effect */
    -webkit-backdrop-filter: blur(10px); /* Safari support */
    border: 1px solid rgba(255, 255, 255, 0.2); /* Light border */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); /* Shadow for depth */
}

.bento-item:hover {
    transform: scale(1.05);
    background: rgba(255, 255, 255, 0.2); /* Change background on hover */
}

.bento-tall {
    height: 425px; /* Retain the height for tall items */
}

.bento-item .modal-content {
    background: rgba(255, 255, 255, 0.1); /* Same glassmorphic style for modal */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Glassmorphism Style */
.glassmorphism-modal {
    background: rgba(255, 255, 255, 0.2); /* semi-transparent white */
    backdrop-filter: blur(10px); /* blur for frosted effect */
    border-radius: 15px; /* rounded corners */
    border: 1px solid rgba(255, 255, 255, 0.2); /* light border */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* subtle shadow */
}

.glassmorphism-modal .form-label {
    color: white;
}

/* Font for dashboard */
h5 {
    color: white;
}

/* table */
.table {
    width: 100%;
    table-layout: fixed; /* Helps control column width */
}

/* Optional: Handle table cell overflow */
.table td {
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

/* Ensure the modal rotation transition */
.modal-content {
    transition: transform 0.5s ease;
}

/* Style for the table container */
.modal-body table {
    border-collapse: collapse;
    width: 100%;
    background: rgba(255, 255, 255, 0.1); /* Slightly transparent background */
    border-radius: 10px; /* Rounded corners */
    overflow: hidden; /* Ensures rounded corners apply to the entire table */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Smooth shadow */
}
body{
    background-color: #2D1B69 !important;
}
</style>

<?php include('includes/footer.php'); ?>