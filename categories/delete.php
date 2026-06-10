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

// Set category ID
$category->id = $_GET['id'];

// Delete category
if ($category->delete()) {

    header("Location: index.php?msg=Category deleted successfully&type=success");

} else {

    header("Location: index.php?msg=Failed to delete category&type=danger");

}

exit();
?>