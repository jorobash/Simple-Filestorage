<?php
session_start();

//download file with "CURLOPT_FILE" option
function dowlnoadfile($file){

    $downloadFile = fopen(__DIR__. DIRECTORY_SEPARATOR . 'curl' . DIRECTORY_SEPARATOR. basename($file), 'wb+' );

    $ch = curl_init($file);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_BUFFERSIZE, 65536);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'downloadProgress');
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_FILE, $downloadFile );
    $response =   curl_exec($ch);

    //check if there is any an error from curl
    if(curl_error($ch) || curl_errno($ch)){
        echo 'Request Error: '.curl_error($ch). "</br>" . 'Request Error: ' . curl_errno($ch);
    }

    $httpCode = curl_getinfo($ch);

    // Check if the response from the server is 200 ok
    if($httpCode['http_code'] === 200){
        echo 'The file was downloaded successfully </br>';
    }else {
        echo 'The file was not downloaded successfully';
    }

    curl_close($ch);
    fclose($downloadFile);

}

// create progress bar for downloaded file
function create_progress() {
    // First create our basic CSS that will control
    // the look of this bar:
    echo "
<style>
#text {
  position: absolute;
  top: 100px;
  left: 50%;
  margin: 0px 0px 0px -150px;
  font-size: 18px;
  text-align: center;
  width: 300px;
}
  #barbox_a {
  position: absolute;
  top: 130px;
  left: 50%;
  margin: 0px 0px 0px -160px;
  width: 304px;
  height: 24px;
  background-color: black;
}
.per {
  position: absolute;
  top: 130px;
  font-size: 18px;
  left: 50%;
  margin: 1px 0px 0px 150px;
  background-color: #FFFFFF;
}

.bar {
  position: absolute;
  top: 132px;
  left: 50%;
  margin: 0px 0px 0px -158px;
  width: 0px;
  height: 20px;
  background-color: #0099FF;
}

.blank {
  background-color: white;
  width: 300px;
}
</style>
";

    // Now output the basic, initial, XHTML that
    // will be overwritten later:
    echo "
<div id='text'>Script Progress</div>
<div id='barbox_a'></div>
<div class='bar blank'></div>

";

    // Ensure that this gets to the screen
    // immediately:
    // Empty php buffer
    flush();
}

// A function that you can pass a percentage as
// a whole number and it will generate the
// appropriate new div's to overlay the
// current ones:

function update_progress($percent) {
    // First let's recreate the percent with
    // the new one:
    echo "<div class='per'>{$percent}
    %</div>";

    // Now, output a new 'bar', forcing its width
    // to 3 times the percent, since we have
    // defined the percent bar to be at
    // 300 pixels wide.
    echo "<div class='bar' style='width: ",
        $percent * 3, "px'></div>";

    // Now, again, force this to be
    // immediately displayed:
    flush();
}

// Ok, now to use this, first create the
// initial bar info:
//create_progress();

// Second function with curl which can be used to downloaded files
function getImage($img) {
    $path = fopen(__DIR__. DIRECTORY_SEPARATOR . 'curl' . DIRECTORY_SEPARATOR. basename($img), 'w+' );
    $ch = curl_init($img);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'downloadProgress');
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //from php 7.1.0 it's not needed to use
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    $rawData = curl_exec($ch);
    curl_close($ch);

    // you can comment "fwrite" function and curl will save the file anyway
    fwrite($path, $rawData);
    fclose($path);
}

function get($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,  $url);
    curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, 'downloadProgress');
    curl_setopt($curl, CURLOPT_NOPROGRESS, false);
    curl_setopt($curl, CURLOPT_VERBOSE, true);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, TRUE);
    $output = curl_exec($curl);
    if(curl_errno($curl)){
      echo  'Request Error:' . curl_error($curl);
    }
    curl_close($curl);
    return $output;
}

 function downloadFile($url, $path)
{
    $newfname = $path;
    $file = fopen ($url, 'rb');
    if ($file) {
        $newf = fopen ($newfname, 'wb');
        if ($newf) {
            while(!feof($file)) {
                fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
            }
        }
    }
    if ($file) {
        fclose($file);
    }
    if ($newf) {
        fclose($newf);
    }
    header('Location: files.php');
}

// get the downloaded size of the file and pass it to the progress bar preview function
function downloadProgress ($resource, $download_size, $downloaded_size, $upload_size, $uploaded_size)
{

    if ($download_size != 0) {
        $percen = (($downloaded_size / $download_size) * 100);
        update_progress($percen);
    }
}

// Check if there is any get file set it
if(isset($_GET['file'])){
    $name = trim($_GET['file']);
    $type = explode('.', $name);
    // Two way to get the current path of the requested file
//    $file = __DIR__. DIRECTORY_SEPARATOR. "uploads" . DIRECTORY_SEPARATOR . $_SESSION['username']. DIRECTORY_SEPARATOR .$name;
    $path = dirname(__FILE__). DIRECTORY_SEPARATOR .'uploads' .
        DIRECTORY_SEPARATOR . $_SESSION['username'] . DIRECTORY_SEPARATOR . $name;

    if(file_exists($path)){
        $file = __DIR__. DIRECTORY_SEPARATOR . 'curl' . DIRECTORY_SEPARATOR. basename($path);
        downloadFile($path, $file);

//     $output =  get("https://upload.wikimedia.org/wikipedia/commons/thumb/c/c8/Altja_j%C3%B5gi_Lahemaal.jpg/1920px-Altja_j%C3%B5gi_Lahemaal.jpg");
////        dowlnoadfile("https://upload.wikimedia.org/wikipedia/commons/thumb/c/c8/Altja_j%C3%B5gi_Lahemaal.jpg/1920px-Altja_j%C3%B5gi_Lahemaal.jpg");
////        var_dump($output);die;
//        $file = fopen(__DIR__. DIRECTORY_SEPARATOR . 'curl' . DIRECTORY_SEPARATOR. basename($path), 'w+' );
//          $file = fopen(_DIR__. DIRECTORY_SEPARATOR . 'curl' . DIRECTORY_SEPARATOR);
//        fwrite($file, $output);
//        fclose($file);
//        header("Content-Description: File Transfer");
//        header('Content-Type: application/force-download; charset=utf-8');

        // force download file with headers
//        header("Content-Description: File Transfer");
//        header("Content-Type: application/octet-stream");
//        header('Content-Disposition: attachment; filename=' . $type[0] . '_' . Date("d-m-y H:i:s"). "." .$type[1]);


//        header('Content-Transfer-Encoding: binary');
////        header('Expires: 0');
////        header('Cache-Control: must-revalidate');
////        header('Pragma: public');
////        header('Content-Length: ' . filesize($file));
//        ob_clean();
//        flush();
        readfile($path);
        exit();
    }


}