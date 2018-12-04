<?php

class CubelistAction extends MemberAction{

    var $dex = "cubelist";

    public function index() {
        $this->assign("dex", $this->dex);
        cookie("cubeback", __SELF__);
        $F = M("Cube"); // 实例化User对象
        import('ORG.Util.Page'); // 导入分页类
        $where = array(
            "member_id" => $this->member_id,
            "disable" => 1
        );
        $count = $F->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $F->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

    function add() {
        if (I("post.dopost") == "save") {
            $title = trim(I('post.title'));
            $is_ipad_view = I('post.is_ipad_view');
            $qingxi = I('post.qingxi');
            $cursor_open = I('post.cursor_open');
            $cursor_id = I('post.cursor_id');

            if ($title == "") {
                $this->error("标题不能为空！");
                exit();
            }

            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "is_ipad_view" => $is_ipad_view,
                "cursor_open" => $cursor_open,
                "cursor_id" => $cursor_id,
                "creat_time" => time()
            );
            $now_id = M("Pano")->add($data);
            $this->success("全景项目创建成功！", U("view/index", array("pano_id" => $now_id)));
            exit();
        }


        $syscursorwhere = array(
            "type" => "system"
        );
        $syscursorrow = M("Cursor")->where($syscursorwhere)->select();
        $this->assign("syscursorrow", $syscursorrow);

        $selfcursorwhere = array(
            "type" => "self",
            "member_id" => $this->member_id
        );
        $selfcursorrow = M("Cursor")->where($selfcursorwhere)->select();
        $this->assign("selfcursorrow", $selfcursorrow);

        $this->display(); // 输出模板
    }
}

?>
