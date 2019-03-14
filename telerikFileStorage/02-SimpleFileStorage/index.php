<?php
session_start();
if (!isset($_SESSION['isLogged'])) {
    header('Location: login.php');
    exit;
}
if (isset($_GET['del'])) {
    if (file_exists('userFolders' . DIRECTORY_SEPARATOR . $_SESSION['username'] . DIRECTORY_SEPARATOR . $_GET['del'])) {
        unlink('userFolders' . DIRECTORY_SEPARATOR . $_SESSION['username'] . DIRECTORY_SEPARATOR . $_GET['del']);
        header('Location: index.php?succdel=1');
    }
}
require 'inc/header.php';
?>
<div id="navigation">
    <form method="POST" action="processupload.php" enctype="multipart/form-data">
        <ul id="menu">
            <li><input type="file" id="fname" name="upload" onchange="document.getElementById('fname').setAttribute('id', 'fnameLoaded');" /></li>
            <li><input type="submit" name="submit" value="Upload" />
            </li>
            <li style="float: right;"><a href="logout.php">Logout</a></li>
            <li style="float: right;"><a href="#">Hello, <?= $_SESSION['username'] ?></a></li>
        </ul>
    </form>
</div>
<table>
    <?php
    if (isset($_GET['succdel'])) {
        echo '<tr><td colspan="6" style="color: #ff491f;">';
        echo 'File Was Deleted Permanently!';
        echo '</td></tr>';
    }
    else if (isset($_GET['succ'])) {
        echo '<tr><td colspan="6" style="color: #93c72e;">';
        echo 'The File was uploaded!';
        echo '</td></tr>';
    }
    else if (isset($_GET['err']) && $_GET['err'] == 1) {
        echo '<tr><td colspan="6" style="color: #ff491f;">';
        echo 'Invalid File Format/Size!';
        echo '</td></tr>';
    }
    else if (isset($_GET['err']) && $_GET['err'] == 2) {
        echo '<tr><td colspan="6" style="color: #ff491f;">';
        echo 'Upload Error!';
        echo '</td></tr>';
    }
    ?>
    <tr>
        <td>№</td>
        <td>Name</td>
        <td>Type</td>
        <td>Date</td>
        <td>Size</td>
        <td>Del</td>
    </tr>
    <?php
    $folderData = scandir('userFolders' . DIRECTORY_SEPARATOR . $_SESSION['username']);
    if (count($folderData) <= 2) {
        echo '<tr><td colspan="6" style="color: #ffc000;">';
        echo 'Your directory is still empty!<br/>Upload your first File!';
        echo '</td></tr>';
    }
    $counter = 1;
    for ($i = 2; $i < count($folderData); $i++) {
        $filePath = 'userFolders' . DIRECTORY_SEPARATOR . $_SESSION['username'] . DIRECTORY_SEPARATOR . $folderData[$i];
        $fileLastEdit = date('d/m/Y', filemtime($filePath));
        $fileSize = number_format(filesize($filePath) / 1048576, 2);
        $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
        echo '<tr>';
        echo '<td>' . $counter . '.</td>';
        echo '<td style="text-align:left;"><a href="' . $filePath . '">' . $folderData[$i] . '</a></td>';
        echo '<td>' . $fileType . '</td>';
        echo '<td>' . $fileLastEdit . '</td>';
        echo '<td>' . $fileSize . '&nbsp;mb</td>';
        echo '<td><a class="btn del" title="Delete" href="?del=' . urlencode($folderData[$i]) . '">del</a></td>';
        echo '</tr>';
        $counter++;
    }
    ?>
    <tr>
        <td>№</td>
        <td>Name</td>
        <td>Type</td>
        <td>Date</td>
        <td>Size</td>
        <td>Del</td>
    </tr>
</table>
<div style="clear:both;">&nbsp;</div>
<span style="color: #d4d4d4;">Allowed File types:</span> <span style="color: #949494;">txt, png, jpe, jpeg, jpg, gif, bmp, pdf, doc, rtf, xls, ppt</span>
<?php
include 'inc/footer.php';
?>