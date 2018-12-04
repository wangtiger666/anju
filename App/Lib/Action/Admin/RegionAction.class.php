<?php

class RegionAction extends AdminAction
{

    //列表页
    public function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M('region');
        $name = I('post.name');
        $pid = I('post.pid');

        $where=" 1 ";

        if($name){
            $where .=" AND name like '%" . $name ."%'";
            $this->assign('name', $name);
        }
        if($pid){
            $where .=" AND pid=".$pid;
            $this->assign('pid', $pid);
        }

        $count = $mm->where($where)->count();// 查询满足要求的总记录数
        $Page = new Page($count, 15);// 实例化分页类 传入总记录数和每页显示的记录数

        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order('id desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $show = $Page->show(); // 分页显示输出

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

    //添加
    public function add(){

//        //查询数据
        $regionList = M("region")->field('id,name')->order("id desc")->select();
        $this->assign('regionList', $regionList);

        $dopost = I('post.dopost');
        if($dopost == 'add'){
            $params = I('post.');
            foreach ($params as $key => $value){
                if($key != 'citycode' && $key != 'adcode'){
                    if(empty($value)){
                        $this->assign('errors', '参数不能为空');
                        $this->display();
                    }
                }
            }

            $data = [
                'pid' => $params['pid'],
                'name' => $params['name'],
                'citycode' => $params['citycode'],
                'adcode' => $params['adcode'],
                'center' => $params['center'],
                'level' => $params['level'],
                'img' => $params['img'],
                'content' => $params['content'],
                'idcode' => $params['idcode'],
            ];

            $ret = M('region')->add($data);

            if($ret){
                redirect('/admin/Region/index');
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
        //查询数据
        $regionList = M("region")->field('id,name')->order("id desc")->select();
        $this->assign('regionList', $regionList);

        $dopost = I('post.dopost');
        $id = I('get.id');
        if($dopost == 'edit'){
            $params = I('post.');
            foreach ($params as $key => $value){
                if($key != 'citycode' && $key != 'adcode'){
                    if(empty($value)){
                        $this->assign('errors', '参数不能为空');
                        $this->display();
                    }
                }
            }

            $data = [
                'pid' => $params['pid'],
                'name' => $params['name'],
                'citycode' => $params['citycode'],
                'adcode' => $params['adcode'],
                'center' => $params['center'],
                'level' => $params['level'],
                'img' => $params['img'],
                'content' => $params['content'],
                'idcode' => $params['idcode'],
            ];

            $ret = M('region')->where(['id' => $params['id']])->save($data);
            if($ret){
                redirect('/admin/Region/index');
            }else{
                $this->assign('errors', '数据保存失败');
                $this->display();
            }

        }else{

            $obj = M('region')->where(['id' => $id])->find();
            $this->assign('detail', $obj);
            $this->display();
        }
    }

    //删除
    function delete(){
        $id = I('post.id');
        $result = [];
        if(empty($id)){
            $result['code'] = 300;
            $result['msg'] = '删除失败！';
            $this->ajaxReturn($result);
        }

        $ret =  M('region')->where(['id' => $id])->delete();
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

    //获取地址
    function getPath(){
        $id = I('post.id');
        $result = [];
        if(empty($id)){
            $result['code'] = 300;
            $result['msg'] = '地址获取失败！';
            $this->ajaxReturn($result);
        }

        $this->getAllPath($id, $path);
        if(empty($path)){
            $result['code'] = 300;
            $result['msg'] = '地址获取失败！';
            $this->ajaxReturn($result);
        }
        $result['code'] = 200;
        $result['msg'] = $path;
        $this->ajaxReturn($result);
    }

    private function getAllPath($id, &$path = ''){
        //查询当前节前的名称
        $region = M('region')->where(['id'=>$id])
                        ->field('pid, name')->find();
        if(!empty($region)){
            $path = $region['name'] . $path;
            if($region['pid'] !== 0){
                $this->getAllPath($region['pid'], $path);
            }
        }
    }

}