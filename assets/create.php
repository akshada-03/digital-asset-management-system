<?php

include_once "../config/Database.php";
include_once "../Category.php";
include_once "../Asset.php";

$errors = [];

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$categories = $category->readAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST['title']))) {
        $errors[] = "Asset title is required.";
    }

    if (empty($_POST['category_id'])) {
        $errors[] = "Please select a category.";
    }

    if (empty($_FILES['asset_file']['name'])) {
        $errors[] = "Please choose a file.";
    }

    if (empty($errors)) {

        $asset = new Asset($db);
        $fileErrors = $asset->validateFile($_FILES['asset_file']);
        $errors = array_merge($errors, $fileErrors);

        if (empty($errors)) {

            $newFile = $asset->uploadFile($_FILES['asset_file']);

            if ($newFile) {

                $asset->category_id = (int)$_POST['category_id'];
                $asset->title = trim($_POST['title']);
                $asset->description = trim($_POST['description'] ?? '');
                $asset->file_name = $newFile;
                $asset->file_type = $_FILES['asset_file']['type'];
                $asset->file_size = (int)$_FILES['asset_file']['size'];

                if ($asset->create()) {
                    header("Location: index.php?msg=Asset uploaded successfully&type=success");
                    exit();
                } else {
                    $errors[] = "Database error. Could not save asset.";
                }
            } else {
                $errors[] = "File upload failed. Check folder permissions.";
            }
        }
    }
}

include_once "../includes/header.php";
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Upload New Asset</h2>
        <a href="index.php" class="btn btn-secondary btn-sm">← Back</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">

            <form action="create.php" method="POST" enctype="multipart/form-data">

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Asset Title <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="form-control"
                        value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                        placeholder="Enter asset title"
                    >
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">-- Select Category --</option>
                        <?php while ($row = $categories->fetch_assoc()): ?>
                            <option
                                value="<?php echo $row['id']; ?>"
                                <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $row['id']) ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars($row['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea
                        name="description"
                        id="description"
                        class="form-control"
                        rows="3"
                        placeholder="Optional description"
                    ><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>

                <!-- File -->
                <div class="mb-3">
                    <label for="asset_file" class="form-label">File <span class="text-danger">*</span></label>
                    <input type="file" name="asset_file" id="asset_file" class="form-control">
                    <div class="form-text">Allowed: JPG, PNG, GIF, PDF, DOC, DOCX, MP4, TXT. Max 10MB.</div>
                </div>

                <button type="submit" class="btn btn-success">Upload Asset</button>
                <a href="index.php" class="btn btn-outline-secondary ms-2">Cancel</a>

            </form>

        </div>
    </div>

</div>

<?php include_once "../includes/footer.php"; ?>