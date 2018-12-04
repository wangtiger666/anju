<?php

class CommonalityAction extends AdminAction
{

    //列表页
    public function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M('commonality as comm');
        $name = I('post.name');
        $pid = I('post.pid');

        $where=" 1 ";

        if($name){
            $where .=" AND comm.name like '%" . $name ."%'";
            $this->assign('name', $name);
        }
        if($pid){
            $where .=" AND comm.pid=".$pid;
            $this->assign('pid', $pid);
        }

        $count = $mm->where($where)->count();// 查询满足要求的总记录数
        $Page = new Page($count, 15);// 实例化分页类 传入总记录数和每页显示的记录数

        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order('comm.id asc')
                    ->field('comm.id,comm.name,comm.status,comm.sort,comm.pid,comm.remark,pcomm.name as pname')
                    ->join('left join pano_commonality AS pcomm ON comm.pid = pcomm.id')
                    ->order('id desc')
                    ->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $show = $Page->show(); // 分页显示输出

        foreach ($list as &$value){
           $value['statusShow'] = ($value['status'] == 1)? '<font style="color: blue">启用</font>':'<font style="color: red">停用</font>';
        }

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

    //新增
    public function add(){
        cookie("back", __SELF__);

        if ($_POST['dopost'] == "save") {

            $data = I("post.");

            $nowtime = date('Y-m-d H:i:s');
            $data['create_time'] = $nowtime;
            $data['modify_time'] = $nowtime;

            $res = M("commonality")->add($data);

            redirect("index");
            exit();
        }

        //查询数据
        $commonalityList = $this->getTreeList($commonalityList = []);
        $this->assign('commonalityList', $commonalityList);

        $this->display(); // 输出模板
    }

    //修改
    public function edit(){
        cookie("back", __SELF__);
        $id = intval($_REQUEST['id']);

        if ($_POST['dopost'] == "edit") {
            $data = I("post.");

            $nowtime = date('Y-m-d H:i:s');
            $data['modify_time'] = $nowtime;

            unset($data['id']);

            $posi_res = M("commonality")->where(array("id"=>$id))->data($data)->save();

            $this->success("修改成功！", U("index"));
            exit;
        }

        //查询数据
        $commonalityList = $this->getTreeList($commonalityList = []);
        $this->assign('commonalityList', $commonalityList);

        $info = M("commonality")->where(array("id"=>$id))->find();

        $this->assign("info", $info);
        $this->display();

    }

    public function getTreeList(& $dataList = [], $pid = 1, $n = 0){
        $list = M("commonality")->field('id,name,pid')
                    ->where("pid=" . $pid)->order("id asc")->select();

        foreach ($list as $key => $value){
            if($value['id'] == 1){
                $dataList[$value['id']] = $value['name'];
                continue;
            }
            $space = "——";
            for ($i=0; $i < $n; $i++) {
                $space .= "——";
            }
            $dataList[$value['id']] = $space . '||' . $value['name'];
            $this->getTreeList($dataList, $value['id'], ($n + 1));
        }
        return $dataList;
    }

}