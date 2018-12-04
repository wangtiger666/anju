<?php

class DoneAction extends Action {

    public function index() {

        $base = APP_ROOT;

        $nowtime = time();
        $basedir = C("UPLOAD_FILE") . C("UPLOAD_BAG");
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
    }

}

?>
