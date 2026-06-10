<?php

include_once "includes/header.php";
include_once "config/Database.php";
include_once "Category.php";
include_once "Asset.php";

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Get counts for dashboard cards
$cat_obj = new Category($db);
$asset_obj = new Asset($db);

$categories = $cat_obj->readAll();
$cat_count = $categories->num_rows; // Total number of categories

$assets = $asset_obj->readAll();
$asset_count = $assets->num_rows;    // Total number of assets
?>

<h2 class="mb-4">Dashboard</h2>


<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h1 class="display-4">
                    <?= $cat_count ?>
                </h1>
                <p class="lead mb-0">Categories</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h1 class="display-4">
                    <?= $asset_count ?>
                </h1>
                <p class="lead mb-0">Total Assets</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body text-center">
                <h1 class="display-4">10MB</h1>
                <p class="lead mb-0">Max Upload Size</p>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Assets</h5>
        <a href="assets/create.php" class="btn btn-sm btn-success">Upload New</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Uploaded</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $asset_obj2 = new Asset($db);
                $recent = $asset_obj2->readAll();

                if ($recent->num_rows > 0):
                    while ($row = $recent->fetch_assoc()):
                        ?>
                        <tr>
                            <td>
                                <?= $row['id'] ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['title']) ?>
                            </td>
                            <td><span class="badge bg-secondary">
                                    <?= htmlspecialchars($row['category_name'] ?? 'N/A') ?>
                                </span></td>
                            <td>
                                <?= strtoupper($row['file_type']) ?>
                            </td>
                            <td>
                                <?= Asset::formatSize($row['file_size']) ?>
                            </td>
                            <td>
                                <?= date('d M Y', strtotime($row['uploaded_at'])) ?>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">No assets uploaded yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>