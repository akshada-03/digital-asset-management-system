<?php

require 'Asset.php';

$asset = new Asset(null);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = $asset->validateFile($_FILES['myfile']);

    if (empty($errors)) {
        echo "File validation passed!";
    } else {
        echo "<pre>";
        print_r($errors);
        echo "</pre>";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="myfile">
    <button type="submit">Upload</button>
</form>