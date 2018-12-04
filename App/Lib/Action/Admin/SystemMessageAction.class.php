<?php

//账号管理
class SystemMessageAction extends AdminAction {

    //账号列表
    function index() {
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $account = I('post.account');
        $type = I('post.type');
        $startDate = I('post.startDate');
        $endDate = I('post.endDate');

        $where = '1';
        if($account){
            $where .= ' and receive_account = "' . $account . '"';
        }
        if($type){
            $where .= ' and type = "' . $type . '"';
        }
        if($startDate){
            $where .= ' and create_time >= "' . $startDate . ' 00:00:00"';
        }
        if($endDate){
            $where .= ' and create_time <= "' . $endDate . ' 23:59:59"';
        }

        $mm = M('system_message');
        $count = $mm->where($where)->count();// 查询满足要求的总记录数
        $Page = new Page($count, $this->pageSize);// 实例化分页类 传入总记录数和每页显示的记录数

        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order('id asc')
                    ->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $show = $Page->show(); // 分页显示输出

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

        $params = I('post.');

        foreach ($params as $key => $value){
            $this->assign($key, $value);
        }

        $this->display();
    }

    //添加
    function add(){
        $dopost = I('post.dopost');
        if($dopost == 'add'){
            $params = I('post.');
            foreach ($params as $key => $value){
                if(empty($value)){
                    $this->assign('errors', '参数不能为空');
                    $this->display();
                }
            }

            $data = [
                'receive_account' => $params['receive_account'],
                'title' => $params['title'],
                'content' => $params['content'],
                'type' => $params['type'],
                'create_time' => $this->getNowTime(),
                'modify_time' => $this->getNowTime(),
            ];

            $ret = M('system_message')->add($data);

            if($ret){
                redirect('/admin/SystemMessage/index');
            }else{
                $this->assign('errors', '数据保存失败');
                $this->display();
            }
        }else{
            $this->display();
        }
    }


    //修改
    function edit(){
        $dopost = I('post.dopost');
        $id = I('get.id');
        if($dopost == 'edit'){
            $params = I('post.');
            foreach ($params as $key => $value){
                if(empty($value)){
                    $this->assign('errors', '参数不能为空');
                    $this->display();
                }
            }

            $data = [
                'receive_account' => $params['receive_account'],
                'title' => $params['title'],
                'content' => $params['content'],
                'type' => $params['type'],
                'modify_time' => $this->getNowTime(),
            ];

            $ret = M('system_message')->where(['id' => $params['id']])->save($data);

            if($ret){
                redirect('/admin/SystemMessage/index');
            }else{
                $this->assign('errors', '数据保存失败');
                $this->display();
            }

        }else{

            $obj = M('system_message')->where(['id' => $id])->find();
            $this->assign('obj', $obj);
            $this->display();
        }
    }

    function delete(){
        $id = I('post.id');
        $result = [];
        if(empty($id)){
            $result['code'] = 300;
            $result['msg'] = '删除失败！';
            $this->ajaxReturn($result);
        }

        $ret =  M('system_message')->where(['id' => $id])->delete();
        if($ret){
            $result['code'] = 200;
            $result['msg'] = '删除成功！';
            $this->ajaxReturn($result);
        }else{
            $result['code'] = 400;
            $result['msg'] = '删除失败！';
            $this->ajaxReturn($result);
        }
    }
}

?>
