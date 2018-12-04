<?php

class MusicstoreAction extends MemberAction {

    public function index() {
        cookie("back", __SELF__);
        $where = array(
            "member_id" => $this->member_id
        );
        $row = M("music")->where($where)->select();
        $this->assign("row", $row);
        $this->display();
    }

    function add() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);
        if (I("post.dopost") == "save") {
            $title = trim(I("post.title"));
            $file = I("post.file");
            if ($title == "") {
                $this->error("请写好【音乐名称】！");
                exit();
            }
            $member_id = $this->member_id;
            if (is_file(APP_ROOT . $file)) {
                $filedir = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/store/music";
                createFolder($filedir);
                $filename = basename($file);
                rename(APP_ROOT . $file, $filedir . "/" . $filename);
                $newfile = "/uploads/user/" . substr(md5($member_id), 5, 15) . "/store/music/" . $filename;
            } else {
                $this->error("请上传音乐！");
                exit();
            }
            $data = array(
                "member_id" => $this->member_id,
                "title" => $title,
                "file" => $newfile
            );
            M("music")->add($data);
            $this->success("添加成功！", U("index"));
            exit();
        }
        $this->display();
    }

    function edit() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);
        if (I("post.dopost") == "save") {
            $id = I("post.id");
            $where = array(
                "id" => $id,
                "member_id" => $this->member_id
            );
            $row = M("music")->where($where)->find();

            $title = trim(I("post.title"));
            $file = I("post.file");
            if ($title == "") {
                $this->error("请写好【音乐名称】！");
                exit();
            }
            $member_id = $this->member_id;
            if (is_file(APP_ROOT . $file)) {
                if ($file != $row['file']) {
                    $filedir = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/store/music";
                    @unlink(APP_ROOT.$row['file']);
                    createFolder($filedir);
                    $filename = basename($file);
                    rename(APP_ROOT . $file, $filedir . "/" . $filename);
                    $newfile = "/uploads/user/" . substr(md5($member_id), 5, 15) . "/store/music/" . $filename;
                } else {
                    $newfile = $row['file'];
                }
            } else {
                $this->error("请上传音乐！");
                exit();
            }

            $data = array(
                "title" => $title,
                "file" => $newfile
            );
            M("music")->where($where)->save($data);
            $this->success("修改成功！", U("index"));
            exit();
        }
        $id = I("get.id");
        $where = array(
            "id" => $id,
            "member_id" => $this->member_id
        );
        $row = M("music")->where($where)->find();

        $this->assign("row", $row);
        $this->display();
    }

    function del(){
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);

        $id = I("get.id");
        $where = array(
            "id" => $id,
            "member_id" => $this->member_id
        );
        $row = M("music")->where($where)->find();
        if(is_array($row)){
            $music_file = APP_ROOT.$row['file'];
            unlink($music_file);
            M("music")->where($where)->delete();
            $usedwhere = array(
                "music_id" => $id,
                "member_id" => $this->member_id
            );
            $data = array(
                "open_music" =>0,
                "music_id" => 0
            );
            M("Pano")->where($usedwhere)->save($data);
            $this->redirect("index");
            exit();
        }
    }

    public function musicxml() {
        $this->display('./App/Tpl/Member/Musicstore/musicxml.xml', 'utf-8', 'text/xml');
    }

}

?>
