<?php

include_once "includes/header.php";
include_once "config/Database.php";
include_once "Category.php";
include_once "Asset.php";

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$asset = new Asset($db);

$categoryResult = $category->readAll();
$totalCategories = $categoryResult->num_rows;


$assetResult = $asset->readAll();
$totalAssets = $assetResult->num_rows;
?>

<div class="container mt-4">

    <h2 class="mb-4">Dashboard</h2>

    <!-- Dashboard Cards -->
    <div class="row">

        <!-- Categories Card -->
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body text-center">
                    <h1><?php echo $totalCategories; ?></h1>
                    <h5>Total Categories</h5>
                </div>
            </div>
        </div>

        <!-- Assets Card -->
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body text-center">
                    <h1><?php echo $totalAssets; ?></h1>
                    <h5>Total Assets</h5>
                </div>
            </div>
        </div>

        <!-- Upload Size Card -->
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body text-center">
                    <h1>10 MB</h1>
                    <h5>Maximum Upload Size</h5>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Assets Section -->
    <div class="card mt-4 shadow">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Assets</h5>

            <a href="assets/create.php" class="btn btn-success btn-sm">
                Upload New Asset
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>File Type</th>
                            <th>File Size</th>
                            <th>Upload Date</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php

                    // Get assets list
                    $recentAssets = $asset->readAll();

                    if ($recentAssets->num_rows > 0) {

                        $count = 0;

                        while ($row = $recentAssets->fetch_assoc()) {

                            // Show only latest 5 assets
                            if ($count == 5) {
                                break;
                            }

                            echo "<tr>";

                            echo "<td>" . $row['id'] . "</td>";

                            echo "<td>" .
                                htmlspecialchars($row['title']) .
                                "</td>";

                            echo "<td>
                                    <span class='badge bg-secondary'>
                                        " .
                                        htmlspecialchars($row['category_name'] ?? 'No Category') .
                                    "
                                    </span>
                                  </td>";

                            echo "<td>" .
                                strtoupper($row['file_type']) .
                                "</td>";

                            echo "<td>" .
                                Asset::formatSize($row['file_size']) .
                                "</td>";

                            echo "<td>" .
                                date('d M Y', strtotime($row['uploaded_at'])) .
                                "</td>";

                            echo "</tr>";

                            $count++;
                        }

                    } else {

                        echo "
                        <tr>
                            <td colspan='6' class='text-center text-muted'>
                                No assets uploaded yet.
                            </td>
                        </tr>";
                    }

                    ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php include_once "includes/footer.php"; ?>