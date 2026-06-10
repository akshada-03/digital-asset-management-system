<?php

// Include required files
include_once "../config/Database.php";
include_once "../Category.php";
include_once "../includes/header.php";

// Store validation errors
$errors = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form values
    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');

    // Validation
    if (empty($name)) {
        $errors[] = "Category name is required.";
    }

    // If no validation errors
    if (empty($errors)) {

        // Database connection
        $database = new Database();
        $db = $database->getConnection();

        // Create Category object
        $category = new Category($db);

        // Assign values
        $category->name = $name;
        $category->description = $description;

        // Save category
        if ($category->create()) {

            // Redirect to category list page
            header("Location: index.php?msg=Category created successfully&type=success");
            exit();

        } else {
            $errors[] = "Failed to create category.";
        }
    }
}
?>

<div class="container mt-4">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header">
                    <h4 class="mb-0">Add New Category</h4>
                </div>

                <div class="card-body">

                    <!-- Error Messages -->
                    <?php if (!empty($errors)): ?>

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                <?php foreach ($errors as $error): ?>

                                    <li><?php echo htmlspecialchars($error); ?></li>

                                <?php endforeach; ?>

                            </ul>

                        </div>

                    <?php endif; ?>

                    <!-- Category Form -->
                    <form method="POST">

                        <!-- Category Name -->
                        <div class="mb-3">

                            <label class="form-label">
                                Category Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" class="form-control" placeholder="Enter category name"
                                value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>

                        </div>

                        <!-- Description -->
                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Enter category description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-primary">
                                Save Category
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