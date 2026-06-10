<?php
include_once "../includes/header.php";
include_once "../config/Database.php";
include_once "../Category.php";

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Create Category object
$category = new Category($db);

// Get all categories
$categories = $category->readAll();
?>

<div class="container mt-4">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Categories</h2>

        <a href="create.php" class="btn btn-primary">
            Add Category
        </a>

    </div>

    <!-- Success Message -->
    <?php if (isset($_GET['msg'])): ?>

        <div class="alert alert-<?php echo $_GET['type'] ?? 'info'; ?>">

            <?php echo htmlspecialchars($_GET['msg']); ?>

        </div>

    <?php endif; ?>

    <!-- Categories Table -->
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Category List</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-light">

                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if ($categories->num_rows > 0): ?>

                        <?php while ($row = $categories->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?php echo $row['id']; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </td>

                                <td>
                                    <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                                </td>

                                <td>

                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="5" class="text-center text-muted">

                                No categories found.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include_once "../includes/footer.php"; ?>