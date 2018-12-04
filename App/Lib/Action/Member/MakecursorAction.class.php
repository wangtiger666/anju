<?php

class MakecursorAction extends MemberAction {

    function index() {
        $syswhere = array(
            "member_id" => 0,
            "type" => "system",
            "mode" => "drag"
        );
        $sysrow = M("Cursor")->where($syswhere)->select();
        $this->assign('sysrow', $sysrow);

        $selfwhere = array(
            "member_id" => $this->member_id,
            "type" => "self",
            "mode" => "drag"
        );
        $selfrow = M("Cursor")->where($selfwhere)->select();
        $this->assign('selfrow', $selfrow);

        cookie("web", __SELF__);

        $this->display();
    }

    function d4() {
        $syswhere = array(
            "member_id" => 0,
            "type" => "system",
            "mode" => "4way"
        );
        $sysrow = M("Cursor")->where($syswhere)->select();
        $this->assign('sysrow', $sysrow);

        $selfwhere = array(
            "member_id" => $this->member_id,
            "type" => "self",
            "mode" => "4way"
        );
        $selfrow = M("Cursor")->where($selfwhere)->select();
        $this->assign('selfrow', $selfrow);

        cookie("web", __SELF__);

        $this->display();
    }

    function d8() {
        $syswhere = array(
            "member_id" => 0,
            "type" => "system",
            "mode" => "8way"
        );
        $sysrow = M("Cursor")->where($syswhere)->select();
        $this->assign('sysrow', $sysrow);

        $selfwhere = array(
            "member_id" => $this->member_id,
            "type" => "self",
            "mode" => "8way"
        );
        $selfrow = M("Cursor")->where($selfwhere)->select();
        $this->assign('selfrow', $selfrow);

        cookie("web", __SELF__);

        $this->display();
    }

    function add2() {
        if (I("post.dopost") == "save") {
            $move = I("post.move");
            $drag = I("post.drag");

            $arr = getimagesize(APP_ROOT.$move);
            $w = $arr[0];
            $h = $arr[1];

            import("@.Class.BoluoGD");
            $width = 2 * $w;
            $height = $h;
            $BG = new BoluoGD();
            $BG->creatNoneImg($width, $height);

            $BG->copyImg(APP_ROOT . $move,array(0,0,$w, $h),array(0,0,$w, $h));
            $pw = $w * 1;
            $BG->copyImg(APP_ROOT . $drag,array(0,0,$w, $h),array($pw,0,$w, $h));

            $filename = "cursor" . time();
            $filedir = APP_ROOT . "/Public/material/self/cursor/" . $this->member_id . "/" . $filename;
            createFolder($filedir);
            $outfile = $filedir . "/cursor.png";
            $outfile2 = $filedir . "/cursor.jpg";

            $data = array(
                "type" => "self",
                "member_id" => $this->member_id,
                "mode" => "drag",
                "file" => $filename,
                "w" => $w,
                "h" => $h
            );


            $BG->save($outfile);
            rename(APP_ROOT . $move, $filedir . "/move.png");
            rename(APP_ROOT . $drag, $filedir . "/drag.png");


            M("Cursor")->add($data);

            $this->success("添加成功！", U("index"));
            exit();
        }
        $this->display();
    }

    function add4() {
        if (I("post.dopost") == "save") {
            $move = I("post.move");
            $drag = I("post.drag");
            $top = I("post.top");
            $down = I("post.down");
            $left = I("post.left");
            $right = I("post.right");
            $arr = getimagesize(APP_ROOT.$move);
            $w = $arr[0];
            $h = $arr[1];

            import("@.Class.BoluoGD");

            $width = 6 * $w;
            $height = $h;
            $BG = new BoluoGD();
            $BG->creatNoneImg($width, $height);

            $BG->copyImg(APP_ROOT . $move,array(0,0,$w, $h),array(0,0,$w, $h));
            $pw = $w * 1;
            $BG->copyImg(APP_ROOT . $drag,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 2;
            $BG->copyImg(APP_ROOT . $top,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 3;
            $BG->copyImg(APP_ROOT . $down,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 4;
            $BG->copyImg(APP_ROOT . $left,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 5;
            $BG->copyImg(APP_ROOT . $right,array(0,0,$w, $h),array($pw,0,$w, $h));


            $filename = "cursor" . time();
            $filedir = APP_ROOT . "/Public/material/self/cursor/" . $this->member_id . "/" . $filename;
            createFolder($filedir);
            $outfile = $filedir . "/cursor.png";

            $data = array(
                "type" => "self",
                "member_id" => $this->member_id,
                "mode" => "4way",
                "file" => $filename,
                "w" => $w,
                "h" => $h
            );

            $BG->save($outfile);
            rename(APP_ROOT . $move, $filedir . "/move.png");
            rename(APP_ROOT . $drag, $filedir . "/drag.png");
            rename(APP_ROOT . $top, $filedir . "/top.png");
            rename(APP_ROOT . $down, $filedir . "/down.png");
            rename(APP_ROOT . $left, $filedir . "/left.png");
            rename(APP_ROOT . $right, $filedir . "/right.png");

            M("Cursor")->add($data);

            $this->success("添加成功！", U("d4"));
            exit();
        }
        $this->display();
    }

    function add8() {
        if (I("post.dopost") == "save") {
            $move = I("post.move");
            $drag = I("post.drag");
            $top = I("post.top");
            $down = I("post.down");
            $left = I("post.left");
            $right = I("post.right");

            $lefttop = I("post.lefttop");
            $righttop = I("post.righttop");
            $leftdown = I("post.leftdown");
            $rightdown = I("post.rightdown");

            $arr = getimagesize(APP_ROOT.$move);
            $w = $arr[0];
            $h = $arr[1];

            import("@.Class.BoluoGD");
            $width = 10 * $w;
            $height = $h;
            $BG = new BoluoGD();
            $BG->creatNoneImg($width, $height);

            $BG->copyImg(APP_ROOT . $move,array(0,0,$w, $h),array(0,0,$w, $h));
            $pw = $w * 1;
            $BG->copyImg(APP_ROOT . $drag,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 2;
            $BG->copyImg(APP_ROOT . $top,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 3;
            $BG->copyImg(APP_ROOT . $down,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 4;
            $BG->copyImg(APP_ROOT . $left,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 5;
            $BG->copyImg(APP_ROOT . $right,array(0,0,$w, $h),array($pw,0,$w, $h));

            $pw = $w * 6;
            $BG->copyImg(APP_ROOT . $lefttop,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 7;
            $BG->copyImg(APP_ROOT . $righttop,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 8;
            $BG->copyImg(APP_ROOT . $rightdown,array(0,0,$w, $h),array($pw,0,$w, $h));
            $pw = $w * 9;
            $BG->copyImg(APP_ROOT . $leftdown,array(0,0,$w, $h),array($pw,0,$w, $h));


            $filename = "cursor" . time();
            $filedir = APP_ROOT . "/Public/material/self/cursor/" . $this->member_id . "/" . $filename;
            createFolder($filedir);
            $outfile = $filedir . "/cursor.png";
            $outfile2 = $filedir . "/cursor.jpg";

            $data = array(
                "type" => "self",
                "member_id" => $this->member_id,
                "mode" => "8way",
                "file" => $filename,
                "w" => $w,
                "h" => $h
            );

            $BG->save($outfile);
            rename(APP_ROOT . $move, $filedir . "/move.png");
            rename(APP_ROOT . $drag, $filedir . "/drag.png");
            rename(APP_ROOT . $top, $filedir . "/top.png");
            rename(APP_ROOT . $down, $filedir . "/down.png");
            rename(APP_ROOT . $left, $filedir . "/left.png");
            rename(APP_ROOT . $right, $filedir . "/right.png");
            rename(APP_ROOT . $lefttop, $filedir . "/lefttop.png");
            rename(APP_ROOT . $righttop, $filedir . "/righttop.png");
            rename(APP_ROOT . $leftdown, $filedir . "/leftdown.png");
            rename(APP_ROOT . $rightdown, $filedir . "/rightdown.png");

            M("Cursor")->add($data);

            $this->success("添加成功！", U("d8"));
            exit();
        }
        $this->display();
    }

    function del() {
        $id = I('get.id');
        $where = array(
            "member_id" => $this->member_id,
            "type" => "self",
            "id" => $id
        );
        $row = M("Cursor")->where($where)->find();
        if (is_array($row)) {
            $filename = $row['file'];
            $filedir = APP_ROOT . "/Public/material/self/cursor/" . $this->member_id . "/" . $filename;
            if (is_dir($filedir)) {
                RemoveDirFiles($filedir);
            }
            M("Cursor")->where($where)->delete();
        }

        $this->success("删除成功！", cookie("web"));
    }

}

?>
