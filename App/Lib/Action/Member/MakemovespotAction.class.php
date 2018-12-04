<?php

class MakemovespotAction extends MemberAction {

    function index() {
        $syswhere = array(
            "member_id" => 0,
            "mode" => "system",
            "type" => "movespot"
        );
        $sysrow = M("Spot")->where($syswhere)->select();
        $this->assign('sysrow', $sysrow);

        $selfwhere = array(
            "member_id" => $this->member_id,
            "mode" => "self",
            "type" => "movespot"
        );
        $selfrow = M("Spot")->where($selfwhere)->select();
        $this->assign('selfrow', $selfrow);

        $this->display();
    }
	
	function add(){
        if(I("post.dopost") == "save"){
            $title = trim(I("post.title"));
            if($title == ""){
                $this->error("标题不可以为空！");
                exit();
            }
            $file = I("post.file");
            if(is_file(APP_ROOT.$file)){
                $arr = getimagesize(APP_ROOT.$file);
                //$width = $arr[0];
                //$height = $arr[1];
				$width = 128;
                $height = 128;
                $len = 25;
                $speed = 30;

                $imgtype = $arr[2];
                if($imgtype == 1){
                    $this->error("图片格式不可以为GIF，请使用PNG图片！");
					exit();
                }
                $basename = basename($file);
                $fileurl = "/Public/material/self/movespot/".$this->member_id."/".$basename;
                $filedir = "/Public/material/self/movespot/".$this->member_id;
                createFolder(APP_ROOT.$filedir);
                rename(APP_ROOT.$file, APP_ROOT.$fileurl);
                $file = $fileurl;
            }else{
                $this->error("上传的文件有问题！");
                exit();
            }
            $data = array(
                "member_id" => $this->member_id,
                "mode" => "self",
                "type" => "movespot",
                "title" => $title,
                "file" => $file,
                "width" => $width,
                "height" => $height,
                "len" => $len,
                "speed" => $speed
            );
            M("Spot ")->add($data);
            $this->success("【{$title}】添加动态热点成功！", U("index"));
            exit();
        }
        $this->display();
    }

    function edit() {
        if (I("post.dopost") == "save") {
            $id = I("post.id");
            $title = trim(I("post.title"));
            if($title == ""){
                $this->error("标题不可以为空！");
                exit();
            }
            $speed = I("post.speed");
            $data = array(
                "title" => $title,
                "speed" => $speed,
            );
            $where = array(
                "id" => $id,
                "member_id" => $this->member_id
            );
            M("Spot ")->where($where)->save($data);
            $this->success("【{$title}】修改成功！", U("index"));
            exit();
        }
        if (I("get.id") != "") {
            $id = I("get.id");
            $where = array(
                "id" => $id,
                "member_id" => $this->member_id
            );
            $row = M("Spot")->where($where)->find();
            $this->assign("row", $row);

            $this->display();
        }
    }

	function del(){
        if(I("get.id") != ""){
            $id = I("get.id");
            $where = array(
                "id" => $id,
                "member_id" => $this->member_id
            );
            $row = M("Spot")->where($where)->find();

            $filedir = APP_ROOT.$row['file'];
            unlink($filedir);

            M("Spot")->where($where)->delete();
            $this->success("【{$row['title']}】删除成功！", U("index"));
        }
    }

}

?>
