<?php
$url = $_REQUEST['url'];
$title = $_REQUEST['title'];

function downloadFile($yoururl, $path)
{
    $newfname = $path;
    $file = fopen($yoururl, 'rb');
    if ($file) {
        $newf = fopen($newfname, 'wb');
        if ($newf) {
            while(!feof($file)) {
                fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
            }
        }

        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }
        return true;
    }else{
        return false;
    }

}

echo downloadFile($url, "../videos/". $title.".mp4");
?>