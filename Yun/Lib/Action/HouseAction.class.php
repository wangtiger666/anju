<?php

/**
 * 账号房屋信息接口
 * Class HouseAction
 */
class HouseAction extends CommomAccountAction
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 浏览记录
     */
    public function history(){
        $result = [];
        if(empty($this->account)){
            $result['code'] = 800;
            $result['msg'] = '账号未登陆';
            $this->ajaxReturn($result);
        }

        $house_resouces_id = I('post.house_resouces_id');

        if(empty($house_resouces_id)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //判断房源是否存在
        $count = M('house_resouces')->where(['id' => $house_resouces_id])->count('id');
        if($count <= 0){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //添加浏览记录
        $nowtime = date('Y-m-d H:i:s');
        $data = [
            'house_resouces_id' => $house_resouces_id,
            'account' => $this->account,
            'type' => 1,
            'create_time' => $nowtime,
        ];
        M('house_resouces_follow')->add($data);

        $result['code'] = 200;
        $result['msg'] = '成功';
        $this->ajaxReturn($result);
    }

    /**
     * 关注记录
     */
    public function follow(){
        $result = [];
        if(empty($this->account)){
            $result['code'] = 800;
            $result['msg'] = '账号未登陆';
            $this->ajaxReturn($result);
        }

        $house_resouces_id = I('post.house_resouces_id');

        $result = [];
        if(empty($house_resouces_id)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //判断房源是否存在
        $count = M('house_resouces')->where(['id' => $house_resouces_id])->count('id');
        if($count <= 0){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //判断是否已经关注过
        $count = M('house_resouces_follow')
                    ->where(['house_resouces_id' => $house_resouces_id, 'account' => $this->account])
                    ->count('id');
        if($count > 0){
            $result['code'] = 400;
            $result['msg'] = '你已经关注过此房源了';
            $this->ajaxReturn($result);
        }

        //添加浏览记录
        $nowtime = date('Y-m-d H:i:s');
        $data = [
            'house_resouces_id' => $house_resouces_id,
            'account' => $this->account,
            'type' => 2,
            'create_time' => $nowtime,
        ];
        M('house_resouces_follow')->add($data);

        $result['code'] = 200;
        $result['msg'] = '关注成功';
        $this->ajaxReturn($result);
    }
}