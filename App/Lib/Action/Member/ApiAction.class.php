<?php

class ApiAction extends MemberAction
{
    //列表页
    public function regionList(){
        $level = I('post.level');
        $pid = I('post.pid', 0);
        if(empty($level)){
            return [];
        }

        $key = 'region_' . $level . $pid;
        $list = M('region')->field('id,name')->cache($key, 3600)
                                        ->where(['level' => $level, 'pid' => $pid])->select();

        $this->ajaxReturn($list);
    }

}