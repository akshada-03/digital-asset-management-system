<?php

// Include required files
include_once "../config/Database.php";
include_once "../Category.php";

// Check if ID exists
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?msg=Invalid ID&type=danger");
    exit();
}

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Create Category object
$category = new Category($db);
$category->id = $_GET['id'];

// Load category data
$category->readOne();

// Store validation errors
$errors = [];

// Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');

    // Validation
    if (empty($name)) {
        $errors[] = "Category name is required.";
    }

    // Update category
    if (empty($errors)) {

        $category->name = $name;
        $category->description = $description;

        if ($category->update()) {

            header("Location: index.php?msg=Category updated successfully&type=success");
            exit();

        } else {

            $errors[] = "Failed to update category.";
        }
    }
}

include_once "../includes/header.php";
?>

<div class="container mt-4">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header">
                    <h4 class="mb-0">Edit Category</h4>
                </div>

                <div class="card-body">

                    <!-- Error Messages -->
                    <?php if (!empty($errors)): ?>

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                <?php foreach ($errors as $error): ?>

                                    <li>
                                        <?php echo htmlspecialchars($error); ?>
                                    </li>

                                <?php endforeach; ?>

                            </ul>

                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Category Name *
                            </label>

                            <input type="text" name="name" class="form-control"
                                value="<?php echo htmlspecialchars($category->name); ?>" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description" class="form-control"
                                rows="4"><?php echo htmlspecialchars($category->description); ?></textarea>

                        </div>

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-warning">
                                Update Category
                            </button>

                            <a href="index.php" class="btn btn-secondary">
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include_once "../includes/footer.php"; ?>