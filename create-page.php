<?php
$page_title = "Create";
include('_dbconnect.php');
include('authentication.php');
include('includes/header.php'); 

$selected_page = isset($_POST['page']) ? $_POST['page'] : 'page2';

// Functions
function showPage1() {
    echo "<div class='card mb-4 justify-content-center'>
                    <div class='card-header'>
                        Add Income
                    </div>
                    <div class='card-body'>
                        <form method='post'>
                            <div class='mb-3'>
                                <label for='income_name' class='form-label'>Income Name</label>
                                <select class='form-select flex-grow-1 me-2' name='income_name'>
                                <option value='Salary'>Salary</option>
                                <option value='Bonus'>Bonus</option>
                                <option value='Commission'>Commission</option>
                                <option value='Overtime Pay'>Overtime Pay</option>
                                <option value='Tips'>Tips</option>
                                <option value='Freelance Payment'>Freelance Payment</option>
                                <option value='Allowance'>Allowance</option>
                                </select>
                            </div>
                            <div class='mb-3'>
                                <label for='income_amount' class='form-label'>Amount</label>
                                <input type='text' class='form-control' name='income_amount' required>
                            </div>
                            <button type='submit' class='btn btn-primary w-100' name='income_btn'>+ Add Income</button>
                        </form>
                    </div>
                </div>";
}

function showPage2() {
    $current_month = date('Y-m');
    echo "<div class='container'>
            <div class='row justify-content-center'>
                <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header'>
                    Create a Budget (" . date('F') . ")
                    </div>
                    <div class='card-body'>
                    <form method='post'>
                        <input type='hidden' name='budget_month' value='$current_month'>
                        <div class='mb-3'>
                        <label for='budget_name' class='form-label'>Budget Name</label>
                        <div class='d-flex align-items-center'>
                            <select class='form-select flex-grow-1 me-2' name='budget_name'>
                            <option value='General'>General</option>
                            <option value='Food'>Food</option>
                            <option value='Housing'>Housing</option>
                            <option value='Groceries'>Groceries</option>
                            <option value='Transportation'>Transportation</option>
                            <option value='Health'>Health</option>
                            <option value='Clothing'>Clothing</option>
                            <option value='Insurance'>Insurance</option>
                            <option value='Education'>Education</option>
                            <option value='Utilities'>Utilities</option>
                            <option value='Entertainment'>Entertainment</option>
                            <option value='Pets'>Pets</option>
                            <option value='Kids'>Kids</option>
                            <option value='Technology'>Technology</option>
                            <option value='Travel'>Travel</option>
                            <option value='Taxes'>Taxes</option>
                            <option value='Gifts'>Gifts</option>
                            </select>
                            <button type='button' class='btn btn-primary btn-sm' id='add-category-btn'>+</button>
                        </div>
                        </div>
                        <div class='mb-3'>
                        <label for='budget_amount' class='form-label'>Amount</label>
                        <input type='text' class='form-control' name='budget_amount' required>
                        </div>
                        <button type='submit' class='btn btn-primary w-100' name='budget_btn'>+ Add Budget</button>
                    </form>
                    </div>
                </div>
                </div>
            </div>
            </div>";
}

function showPage3() {
    $current_month = date('Y-m');
    echo "<div class='card'>
            <div class='card-header'>
                Add Expense (" . date('F') . ")
            </div>
            <div class='card-body'>
                <form method='post'>
                    <div class='mb-3'>
                            <label class='mb-2'>Budget Category</label>
                            <select class='form-select' name='budget_category'>
                                ";
                                fetchBudgetCategories();
                                echo "
                            </select>
                    </div>
                    <div class='mb-3'>
                        <label for='expense_amount' class='form-label'>Amount</label>
                        <input type='text' class='form-control' name='expense_amount' required>
                    </div>
                    <div class='mb-3'>
                        <label for='expense_comment' class='form-label'>Comment</label>
                        <textarea class='form-control' name='expense_comment' rows='3' maxlength='100' placeholder='Max 100 characters'></textarea>
                    </div>
                    <button type='submit' class='btn btn-primary w-100' name='expense_btn'>+ Add Expense</button>
                </form>
            </div>
        </div>";
}

// Fetch Budget Categories
function fetchBudgetCategories() {
    global $conn;
    $userId = $_SESSION['auth_user']['user_id'];
    $current_month = date('Y-m');
    $sql = "SELECT id, name FROM budgets WHERE user_id = '$userId' AND month = '$current_month'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
        }
    } else {
        echo "<option value='No categories found'>No categories found</option>";
    }
}
?>

<!-- HTML -->
<div class="py-3">
    <div class="container">
        <a class="btn btn-secondary btn-sm mb-4" href="dashboard-page.php">X</a>
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">

                <form method="post" action="" id="pageForm">
                    <input type="hidden" name="submitted_page" value="<?php echo $selected_page; ?>">
                    <div class="btn-group" role="group" aria-label="Page Selection">
                        <input type="radio" class="btn-check" name="page" id="page1" value="page1" autocomplete="off" <?php echo ($selected_page == 'page1') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary" for="page1">Income</label>

                        <input type="radio" class="btn-check" name="page" id="page2" value="page2" autocomplete="off" <?php echo ($selected_page == 'page2') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary" for="page2">Budget</label>

                        <input type="radio" class="btn-check" name="page" id="page3" value="page3" autocomplete="off" <?php echo ($selected_page == 'page3') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary" for="page3">Expense</label>
                    </div>
                    <div class="content mt-4">
                        <?php
                            if ($selected_page == 'page1') {
                                showPage1();
                            } elseif ($selected_page == 'page2') {
                                showPage2();
                            } elseif ($selected_page == 'page3') {
                                showPage3();
                            }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Process -->
<?php
// Add New Budget Process
if (isset($_POST['budget_btn'])) {
    $budgetName = $_POST['budget_name'];
    $budgetAmount = $_POST['budget_amount'];
    $budgetMonth = $_POST['budget_month'];
    $userId = $_SESSION['auth_user']['user_id']; 

    // Check if a budget with the same name already exists for the current month
    $checkSql = "SELECT * FROM budgets WHERE user_id = '$userId' AND name = '$budgetName' AND month = '$budgetMonth'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('A budget with this name already exists for the current month!');</script>";
    } else {
        $sql = "INSERT INTO budgets (user_id, name, amount, month) VALUES ('$userId', '$budgetName', '$budgetAmount', '$budgetMonth')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Budget added successfully for " . date('F Y', strtotime($budgetMonth)) . "!');</script>";
        } else {
            echo "<script>alert('Error adding budget: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// Add New Category Process
if (isset($_POST['addCategoryBtn'])) {
    $newCategoryName = $_POST['newCategoryName'];
    $newCategoryAmount = $_POST['newCategoryAmount'];
    $userId = $_SESSION['auth_user']['user_id'];

    $sql = "INSERT INTO budgets (user_id, name, amount) VALUES ('$userId', '$newCategoryName', '$newCategoryAmount')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>alert('Custom Category added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding category!');</script>";
    }
}

// Add New Expense Process
if (isset($_POST['expense_btn'])) {
    $budgetCategory = $_POST['budget_category'];
    $expenseAmount = $_POST['expense_amount'];
    $expenseComment = substr($_POST['expense_comment'], 0, 100);
    $userId = $_SESSION['auth_user']['user_id'];
    $currentDate = date('Y-m-d'); // Current date
    $currentMonth = date('Y-m');

    // Check if the budget category exists for the current month
    $checkBudgetSql = "SELECT id FROM budgets WHERE id = '$budgetCategory' AND user_id = '$userId' AND month = '$currentMonth'";
    $checkBudgetResult = mysqli_query($conn, $checkBudgetSql);

    if (mysqli_num_rows($checkBudgetResult) > 0) {
        $sql = "INSERT INTO expenses (user_id, amount, category_id, date, comment) VALUES ('$userId', '$expenseAmount', '$budgetCategory', '$currentDate', '$expenseComment')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Expense added successfully for $currentMonth!');</script>";
        } else {
            echo "<script>alert('Error adding expense!');</script>";
        }
    } else {
        echo "<script>alert('Error: The selected budget category does not exist for the current month.');</script>";
    }
}

// Add New Income Process
if (isset($_POST['income_btn'])) {
    $incomeName = $_POST['income_name'];
    $incomeAmount = $_POST['income_amount'];
    $userId = $_SESSION['auth_user']['user_id'];

    $sql = "INSERT INTO incomes (user_id, name, amount) VALUES ('$userId', '$incomeName', '$incomeAmount')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>alert('Income added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding income!');</script>";
    }
}
?>

<!-- Modal-1 -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addCategoryModalLabel">Add Custom Category</h1>
      </div>
      <div class="modal-body">
        <form method="post">
            <div class="mb-3">
                <label for="newCategoryName" class="form-label">Custom Category Name</label>
                <input type="text" class="form-control" name="newCategoryName" required>
            </div>
            <div class='mb-3'>
                <label for='budget_amount' class='form-label'>Amount</label>
                <input type='text' class='form-control' name='newCategoryAmount' required>
            </div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
            <button type="submit" class="btn btn-primary" name="addCategoryBtn">+ Add</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal-1 Script -->
<script>
document.querySelectorAll('input[name="page"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('pageForm').submit();
    });
});

// Add New Category Modal Script
document.getElementById('add-category-btn').addEventListener('click', function() {
    // Show the modal
    var myModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
    myModal.show();
});
</script>

<?php include('includes/footer.php') ?>
