<?php

class BackgroundAction extends MemberAction{
    function index(){
        $inpath = APP_ROOT."/Public/member/images/background";

        $dh = dir($inpath);
        $bgname = array();
        while (($file = $dh->read()) !== false) {
            if ($file != "." && $file != ".." && !is_dir("$inpath/$file")) {
                $bgname[] = $file;
            }
        }

        $where = array(
            "member_id" => $this->member_id
        );
        $row = M("Background")->where($where)->find();

        $this->assign("nowbg", $row['bgimg']);
        $this->assign("bgname", $bgname);
        $this->display();
    }
}

?>
