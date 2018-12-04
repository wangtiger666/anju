<?php

//账号管理
class HouseBookAction extends AdminAction {

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
            $where .= ' and phrf.account = "' . $account . '"';
        }
        if($startDate){
            $where .= ' and phrf.create_time >= "' . $startDate . ' 00:00:00"';
        }
        if($endDate){
            $where .= ' and phrf.create_time <= "' . $endDate . ' 23:59:59"';
        }

        $mm = M('house_resources_book phrf');
        $count = $mm->where($where)->count();// 查询满足要求的总记录数
        $Page = new Page($count, $this->pageSize);// 实例化分页类 传入总记录数和每页显示的记录数

        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->join('left join pano_house_resources phr on phrf.house_resouces_id = phr.id')
                    ->field('phrf.id, phrf.account, phrf.type, phrf.create_time, phr.pano_guid, phrf.house_resouces_id')
                    ->order('phrf.id asc')
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

}

?>
