<?php
 session_start();
 echo 'hello '. $_SESSION['username']. "</br>";



 $improve = 'improve';
 $path = 'uploads'. DIRECTORY_SEPARATOR . $_SESSION['username'];
if(file_exists($path)){
 $dir = scandir($path);
 $scanned_directory = array_diff($dir, array('..', '.'));
 $file = $path. DIRECTORY_SEPARATOR;
 $count = 1;
 foreach($scanned_directory as $directory){
  echo '<label>' .$count. '.&nbsp;</label>';
// echo '<a href="'.$file. $directory .'" download>'. $directory .'</a> <span>size '. filesize($file. $directory).'</span></br>';
  echo '<a href="dowlnoad.php?file='. $directory .'">'. $directory .'</a> <span>'. filesize($file. $directory).'</span></br>';
  $count++;
 }
}else{
 echo "There is no any uploaded files please uploaded here: <a href='upload.php'>Uploaded page</a>";
}


?>