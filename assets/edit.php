<?php

include_once "../config/Database.php";
include_once "../Category.php";
include_once "../Asset.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: index.php?msg=Invalid ID&type=danger");
    exit();

}

$database = new Database();
$db = $database->getConnection();

$asset = new Asset($db);
$asset->id = $_GET['id'];

$asset->readOne();

$category = new Category($db);
$categories = $category->readAll();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST['title']))) {
        $errors[] = "Title is required.";
    }

    if (empty($_POST['category_id'])) {
        $errors[] = "Please select a category.";
    }

    if (empty($errors)) {

        $asset->title = trim($_POST['title']);
        $asset->category_id = $_POST['category_id'];
        $asset->description = trim($_POST['description']);

        if ($asset->update()) {

            header("Location: index.php?msg=Asset updated successfully&type=success");
            exit();

        } else {

            $errors[] = "Failed to update asset.";

        }
    }
}

include_once "../includes/header.php";
?>