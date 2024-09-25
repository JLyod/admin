<?php
$page_title = "Dashboard";
include('_dbconnect.php');
include('authentication.php');
include('includes/header.php');
include('includes/navbar.php');

// Function to fetch and sum expenses
function getExpensesTotal($userId) {
    global $conn;
    $sql = "SELECT SUM(e.amount) AS total_expenses FROM expenses e WHERE e.user_id = '$userId'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total_expenses'] ?? 0;
}

$totalExpenses = getExpensesTotal($_SESSION['auth_user']['user_id']);
?>
<div class="py-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <span>Hello, <?= $_SESSION['auth_user']['username']?>!</span>
            <a class="btn btn-dark btn-sm" href="notifications-page.php"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
            </svg></a>
        </div>

        <div class="card my-3 text-center">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">Expenses</th>
                                <th scope="col">Income</th>
                                <th scope="col">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= number_format($totalExpenses, 2) ?></td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row row-cols-2 g-4">
        <?php
        // Fetch budget data from the database
        $userId = $_SESSION['auth_user']['user_id'];
        $sql = "SELECT b.id, b.name, b.amount, SUM(e.amount) AS total_expenses FROM budgets b LEFT JOIN expenses e ON b.id = e.category_id WHERE b.user_id = '$userId' GROUP BY b.id, b.name, b.amount";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $budgetId = $row['id'];
                $budgetName = $row['name'];
                $budgetAmount = $row['amount'];
                $totalExpenses = $row['total_expenses'];
                $remainingBalance = $budgetAmount - $totalExpenses;
                $percentageUsed = ($totalExpenses / $budgetAmount) * 100;
        ?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column p-2">
                            <h6 class="card-title mb-2" style="font-size: 1rem; font-weight: bold;"><?= $budgetName ?></h6>
                            <div class="budget-info">
                                <p class="card-text mb-0" style="font-size: 0.85rem; line-height: 1.6;">Budget - ₱<?= number_format($budgetAmount, 2) ?></p>
                                <p class="card-text mb-0" style="font-size: 0.85rem; line-height: 1.6;">Spent - ₱<?= number_format($totalExpenses, 2) ?></p>
                                <p class="card-text mb-1" style="font-size: 0.85rem; line-height: 1.6;">Remaining - ₱<?= number_format($remainingBalance, 2) ?></p>
                            </div>
                            <div class="progress mt-auto">
                                <div class="progress-bar <?php if ($percentageUsed >= 90) { echo 'bg-danger'; } elseif ($percentageUsed >= 70) { echo 'bg-warning'; } else { echo 'bg-success'; } ?>" 
                                    role="progressbar" 
                                    style="width: <?= $percentageUsed ?>%;" 
                                    aria-valuenow="<?= $percentageUsed ?>" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                    <?= number_format($percentageUsed, 1) ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
        ?>
            <div class="col">
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="card-text">No budgets found.</p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        </div>

    </div>
</div>

<?php include('includes/footer.php') ?>
