<?php

require 'config/database.php';
require 'Category.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

$category->name = "Electronics";
$category->description = "Electronic products";

if ($category->readOne()) {
    echo "Category Created Successfully";
} else {
    echo "Category Creation Failed";
}