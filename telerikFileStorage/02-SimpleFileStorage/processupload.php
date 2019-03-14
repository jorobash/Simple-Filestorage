<?php
session_start();
if (!isset($_SESSION['isLogged'])) {
    header('Location: login.php');
    exit;
}
$mime_types = array(
    'txt' => 'text/plain',
    'png' => 'image/png',
    'jpe' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'jpg' => 'image/jpeg',
    'gif' => 'image/gif',
    'bmp' => 'image/bmp',
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'rtf' => 'application/rtf',
    'xls' => 'application/vnd.ms-excel',
    'ppt' => 'application/vnd.ms-powerpoint',
);
if (isset($_POST['submit']) && isset($_FILES['upload'])) {
    $fileTokens = explode('.', $_FILES['upload']['name']);
    $extension = end($fileTokens);
    
    if (in_array($_FILES['upload']['type'], $mime_types)
            && key_exists($extension, $mime_types)
            && $_FILES['upload']['size'] < 2048000) {
        if (move_uploaded_file($_FILES['upload']['tmp_name'], 'userFolders'.DIRECTORY_SEPARATOR.$_SESSION['username'].DIRECTORY_SEPARATOR.$_FILES['upload']['name'])) {
            header('Location: index.php?succ=1');
        }
        else
        {
            header('Location: index.php?err=2');
        }
    }
    else
    {
        header('Location: index.php?err=1');
    }
}
?>
