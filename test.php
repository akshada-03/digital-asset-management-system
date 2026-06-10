<?php

require 'config/database.php';
require 'Category.php';
REQUIRE 'Asset.php';

$database = new Database();
$db = $database->getConnection();
$asset = new Asset($db);

$category = new Category($db);

$category->name = "Electronics";
$category->description = "Electronic products";

if ($category->readOne()) {
    echo "Category Created Successfully";
} else {
    echo "Category Creation Failed";
}


$result = $asset->readAll();
echo "<h2>All Assets</h2>";

while ($row = $result->fetch_assoc()) {

    echo "Title: " . $row['title'];
    echo " | Category: " . $row['category_name'];
    echo "<br>";
}


$asset->category_id = 1;

$result = $asset->readByCategory();

while($row = $result->fetch_assoc())
{
    echo "Title: " . $row['title'];
    echo " | Category: " . $row['category_name'];
    echo "<br>";
}


$asset->id = 1;

$asset->readOne();

echo "Title: " . $asset->title . "<br>";
echo "Description: " . $asset->description . "<br>";
echo "File Name: " . $asset->file_name . "<br>";
echo "File Type: " . $asset->file_type . "<br>";
echo "File Size: " . $asset->file_size . "<br>";