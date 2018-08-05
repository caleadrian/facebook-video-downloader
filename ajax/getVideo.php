<?php
include_once '../php/action.php';

$url = $_REQUEST['url'];
$context = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.47 Safari/537.36",
    ],
];
$context = stream_context_create($context);
$data = file_get_contents($url, false, $context);
$hdlink = $obj->hd_finallink($data);
$sdlink = $obj->sd_finallink($data);
$title = $obj->getTitle($data);
$message = array();

if ($sdlink != "") {
    $message = array(
        'type' => 'success',
        'title' => $title,
        'hd_download_url' => $hdlink,
        'sd_download_url' =>$sdlink,
        'hd_size' => $obj->getRemoteFilesize($hdlink),
        'sd_size' => $obj->getRemoteFilesize($sdlink),
        'thumbnail' => $obj->getThumb($url)
    );
} else {
    $message = array(
        'type' => 'failure',
        'message' => 'Error retrieving the download link for the url. Please try again later',
    );
}

print json_encode($message);
?>