<?php
define('UPLOAD_ROOT', str_replace("\\", '/', dirname(__FILE__)));
$allbase = UPLOAD_ROOT;
$basearr = explode("/App/", $allbase);
$base = $basearr[0];

$nowtime = time();
$basedir = "/uploads/station";
if (is_dir($base . $basedir) == FALSE) {
    mkdir($base . $basedir);
}

$filetype = explode(".", $_FILES['Filedata']['name']);
$len = count($filetype) - 1;
$filetype = $filetype[$len];
$pinfo = basename($_FILES['Filedata']['tmp_name']);
$pinfo = explode(".", $pinfo);
$pinfo = $nowtime . $pinfo[0] . "." . $filetype; //生成文件名称
$pinfo = str_replace("php", "", $pinfo);
$fileurl = $basedir . "/" . $pinfo;

if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $base . $fileurl)) {
    echo $fileurl;
}
?>
