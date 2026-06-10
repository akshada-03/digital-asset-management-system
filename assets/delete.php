<?php

include_once "../config/Database.php";
include_once "../Asset.php";

// Check ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: index.php?msg=Invalid ID&type=danger");
    exit();

}

// Database connection
$database = new Database();
$db = $database->getConnection();

// Asset object
$asset = new Asset($db);
$asset->id = $_GET['id'];

// Delete asset
if ($asset->delete()) {

    header("Location: index.php?msg=Asset deleted successfully&type=success");

} else {

    header("Location: index.php?msg=Failed to delete asset&type=danger");

}

exit();
?>