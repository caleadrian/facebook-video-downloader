<?php
class Magic {
    public function cleanStr($str){
        return html_entity_decode(strip_tags($str), ENT_QUOTES, 'UTF-8');
    }

    public function hd_finallink($curl_content){
        $regex = '/hd_src_no_ratelimit:"([^"]+)"/';
        if (preg_match($regex, $curl_content, $match)) {
            return $match[1];
        } else {return;}
    }

    public function sd_finallink($curl_content){
        $regex = '/sd_src_no_ratelimit:"([^"]+)"/';
        if (preg_match($regex, $curl_content, $match1)) {
            return $match1[1];
        } else {return;}
    }

    public function getTitle($curl_content){
        $title = null;
        if (preg_match('/h2 class="uiHeaderTitle"?[^>]+>(.+?)<\/h2>/', $curl_content, $matches)) {
            $title = $matches[1];
        } elseif (preg_match('/title id="pageTitle">(.+?)<\/title>/', $curl_content, $matches)) {
            $title = $matches[1];
        }
        return $this->cleanStr($title);
    }

    public function getRemoteFilesize($url, $formatSize = true, $useHead = true){
        if (false !== $useHead) {
            stream_context_set_default(array('http' => array('method' => 'HEAD')));
        }
        $head = array_change_key_case(get_headers($url, 1));
        // content-length of download (in bytes), read from Content-Length: field
        $clen = isset($head['content-length']) ? $head['content-length'] : 0;

        // cannot retrieve file size, return "-1"
        if (!$clen) {
            return -1;
        }
        if (!$formatSize) {
            return $clen; // return size in bytes
        }
        $size = $clen;
        switch ($clen) {
            case $clen < 1024:
                $size = $clen .' B'; break;
            case $clen < 1048576:
                $size = round($clen / 1024, 2) .' KB'; break;
            case $clen < 1073741824:
                $size = round($clen / 1048576, 2) . ' MB'; break;
            case $clen < 1099511627776:
                $size = round($clen / 1073741824, 2) . ' GB'; break;
        }
        return $size; // return formatted size
    }

    public function getThumb($url){
    $url = trim($url);
    if(substr($url, -1)=="/")
    {
        $url = substr($url, 0, -1);
    }
    $urls = [$url];
    $ids = [];
    foreach ($urls as $url) 
    {
        $tmp = explode('/', $url);
        if (strtolower($tmp[count($tmp) - 2] == 'videos')) {
            $ids[$url] = $tmp[count($tmp) - 1];
            continue;
        }
        
        parse_str(parse_url($url)['query'], $query);
        
        if (!empty($query['v']))
        {
            $ids[$url] = $query['v'];
        }
        if(substr($url, -1)=="r"){
            $ids[$url] = $tmp[6];
        }
        if(substr($url, -1)=="y"){
            $ids[$url] = $tmp[5];
        }
        
        if(substr($tmp["3"], 0, 1)=="v"){
            $matches = array();
            $t = preg_match('/=(.*?)\&/s', $tmp["3"], $matches);
            $ids[$url] = $matches[1];
        }
        if(strpos($tmp[3], "video") !== false & substr($tmp[3], -1)!="r")
        {
        $ids[$url] = str_replace("video.php?v=", "", $tmp[3]);
        }
    }
    $imgurl = $ids[$url];
    return "https://graph.facebook.com/$imgurl/picture/";
    }

}

$obj = new Magic();












?>