<?php

session_start();


if(isset($_FILES['file']) && isset($_FILES['file']['name']) && count($_FILES) > 0){

    $path = dirname(__FILE__). DIRECTORY_SEPARATOR .'uploads' .
        DIRECTORY_SEPARATOR . $_SESSION['username'];
    if(!is_dir($path)){
        mkdir($path,  0777, true);
    }
    $dirImage = $path . DIRECTORY_SEPARATOR . $_FILES['file']['name'];
    if(!file_exists($dirImage)){
        move_uploaded_file($_FILES['file']['tmp_name'], $dirImage);
    }else {
        echo 'The file was already uploaded';
    }

}
?>
<form method="POST" enctype="multipart/form-data">
    <label for="file">Качи файл</label><br>
    <input type="file" name="file" id="file"></br>
    <input type="submit" value="save">
</form>