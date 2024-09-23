<?php
include('_dbconnect.php');
include('authentication.php');
$page_title = "Create";
include('includes/header.php'); 

$selected_page = isset($_POST['page']) ? $_POST['page'] : 'page1';

function showPage1() {
    echo "<div class='container'>
            <div class='row justify-content-center'>
                <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header'>
                    Create a Budget Category
                    </div>
                    <div class='card-body'>
                    <form method='post'>
                        <div class='mb-3'>
                        <label for='budget_name' class='form-label'>Budget Category</label>
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
                        <input type='text' class='form-control' id='budget_amount' name='budget_amount'>
                        </div>
                        <button type='submit' class='btn btn-primary w-100' name='budget_btn'>+ Add Budget</button>
                    </form>
                    </div>
                </div>
                </div>
            </div>
            </div>";
}

function showPage2() {
    echo "<div class='card'>
            <div class='card-header'>
                Add New Expense
            </div>
            <div class='card-body'>
                <form method='post'>
                    <div class='mb-3'>
                        <label for='expense_name' class='form-label'>Expense Name</label>
                        <input type='text' class='form-control' id='expense_name'>
                    </div>
                    <div class='mb-3'>
                        <label for='expense_amount' class='form-label'>Amount</label>
                        <input type='text' class='form-control' id='expense_amount'>
                    </div>
                    <div class='mb-3'>
                        <label class='mb-2'>Budget Category</label>
                        <select class='form-select'>
                            <option value='1'>One</option>
                            <option value='2'>Two</option>
                            <option value='3'>Three</option>
                        </select>
                    </div>
                    <button type='submit' class='btn btn-primary' name='expense_btn'>+ Add Expense</button>
                </form>
            </div>
        </div>";
}
?>
<div class="py-3">
    <div class="container">
        <a class="btn btn-danger btn-sm mb-3" href="dashboard-page.php"><</a>
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <form method="post" action="" id="pageForm">
                    <input type="hidden" name="submitted_page" value="<?php echo $selected_page; ?>">
                    <div class="btn-group" role="group" aria-label="Page Selection">
                        <input type="radio" class="btn-check" name="page" id="page1" value="page1" autocomplete="off" <?php echo ($selected_page == 'page1') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary" for="page1">Budget</label>

                        <input type="radio" class="btn-check" name="page" id="page2" value="page2" autocomplete="off" <?php echo ($selected_page == 'page2') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary" for="page2">Expense</label>
                    </div>
                    <div class="content mt-4">
                        <?php
                            if ($selected_page == 'page1') {
                                showPage1();
                            } elseif ($selected_page == 'page2') {
                                showPage2();
                            }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['budget_btn'])) {
    $budgetName = $_POST['budget_name'];
    $budgetAmount = $_POST['budget_amount'];

    $sql = "INSERT INTO budgets (name, amount) VALUES ('$budgetName', '$budgetAmount')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>alert('Budget added successfully!');</script>";
        $_SESSION['notification'] = "Budget '$budgetName' added successfully!"; 
        header("Location: notifications-page.php"); 
        exit();
    } else {
        echo "<script>alert('Error adding budget!');</script>";
    }
}
?>

<script>
document.querySelectorAll('input[name="page"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('pageForm').submit();
    });
});
</script>

<!-- Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addCategoryModalLabel">Add New Category</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addCategoryForm">
            <div class="mb-3">
                <label for="newCategoryName" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="newCategoryName" name="newCategoryName" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveCategoryBtn">+ Add</button>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('input[name="page"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('pageForm').submit();
    });
});

document.getElementById('add-category-btn').addEventListener('click', function() {
    // Show the modal
    var myModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
    myModal.show();
});
</script>

<?php include('includes/footer.php') ?>
