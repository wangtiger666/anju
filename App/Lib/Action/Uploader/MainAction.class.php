<?php

class MainAction extends Action {

    public function index() {
        $base = CC("web_root");
        $appbase = $base."/".APP_NAME;

        freshsetting();
        $upload_size = CC("upload_size")*1024*1024;
        $this->assign('upload_size',$upload_size);
        if (I("get.tp") == "image") {
            $filetp = "jpg|jpeg|gif|png|bmp";
        } else if (I("get.tp")  == "audio") {
            $filetp = "mp3|wma";
        } else if (I("get.tp")  == "all") {
            $filetp = "jpg|jpeg|gif|png|bmp|mp3|wma|flv|swf|mp4|m4v|zip";
        } else if (I("get.tp")  == "video") {
            $filetp = "wma|flv|swf|mp4|m4v";
        } else if (I("get.tp")  == "applevideo") {
            $filetp = "mp4|m4v";
        } else if (I("get.tp")  == "obj") {
            $filetp = "obj";
        } else if (I("get.tp")  == "mtl") {
            $filetp = "mtl";
        }
        $this->assign('filetp',$filetp);

        if (I("get.namecode") == "self") {
            $phpdir = $base."/index.php?s=/uploader/doneself/index";
        } else {
            $phpdir = $base."/App/Tools/Uploader/done.php";
        }
        $this->assign('phpdir',$phpdir);

        if (I("get.num") == 1) {
            $swfurl = "$appbase/Tools/Uploader/uploader.swf";
        } else {
            $swfurl = "$appbase/Tools/Uploader/uploaders.swf";
        }
        $this->assign('swfurl',$swfurl);
        $this->assign('tp',I("get.tp"));
        $this->assign('jsname',I("get.jsname"));

        $this->display();
    }

}

?>
