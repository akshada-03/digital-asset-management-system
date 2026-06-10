<?php

include_once "../includes/header.php";
include_once "../config/Database.php";
include_once "../Asset.php";

// Database connection
$database = new Database();
$db = $database->getConnection();

// Create Asset object
$asset = new Asset($db);

// Get all assets
$assets = $asset->readAll();

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Assets</h2>

        <a href="create.php" class="btn btn-success">
            Upload Asset
        </a>

    </div>

    <?php if(isset($_GET['msg'])) : ?>

        <div class="alert alert-<?php echo $_GET['type'] ?? 'info'; ?>">

            <?php echo htmlspecialchars($_GET['msg']); ?>

        </div>

    <?php endif; ?>

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Asset List</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>File Type</th>
                        <th>File Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php if($assets->num_rows > 0) : ?>

                    <?php while($row = $assets->fetch_assoc()) : ?>

                        <tr>

                            <td><?php echo $row['id']; ?></td>

                            <td><?php echo htmlspecialchars($row['title']); ?></td>

                            <td>
                                <?php echo htmlspecialchars($row['category_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['file_type']); ?>
                            </td>

                            <td>
                                <?php echo Asset::formatSize($row['file_size']); ?>
                            </td>

                            <td>

                                <a href="../uploads/<?php echo htmlspecialchars($row['file_name']); ?>"
                                   target="_blank"
                                   class="btn btn-primary btn-sm">
                                   View
                                </a>
 
                                <a href="edit.php?id=<?php echo $row['id']; ?>"
                                   class="btn btn-warning btn-sm">
                                   Edit
                                </a>

                                <a href="delete.php?id=<?php echo $row['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this asset?')">
                                   Delete
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else : ?>

                    <tr>
                        <td colspan="6" class="text-center">
                            No assets found.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include_once "../includes/footer.php"; ?>