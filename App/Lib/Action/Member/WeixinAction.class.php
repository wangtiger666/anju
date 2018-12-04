<?php

class WeixinAction extends MemberAction {

    public function index() {
        $where = array(
            "id" => 1
        );
		if (I("post.dopost") == "save")
		{
			$appid = I('post.appid');
			$appsecret = I('post.appsecret');
			$data = array(
				"appid"=>$appid,
				"appsecret"=>$appsecret
			);
			M("Wxconfig")->where($where)->save($data);
			$this->success('修改成功');
            exit();
		}
        $row = M("Wxconfig")->where($where)->find();
		if(empty($row)){
			M("Wxconfig")->data(array("id"=>1))->add();
		}
        $this->assign('row', $row);
        $this->display();
    }

    public function syslist() {
		$F = M("Comment");
        import('ORG.Util.Page');
		$pano_id = I('get.pano_id');
		$where['member_id'] = $this->member_id;
		$where['pano_id'] = $pano_id;
		$panoname = M("Pano")->where(array("id"=>$pano_id))->getField("title");
		$this->assign('panoname', $panoname);
		/*$where['status'] = 1;
		$panoname = trim(I('post.panoname'));
		if(!empty($panoname))
		{
			$where['title'] = array('like',"%".$panoname."%");
		}*/
        $count = $F->where($where)->count();
        $Page = new Page($count, 15);
        $show = $Page->show();        
        $list = $F->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $key => $va) {
            $wxinfo = M("Wxuser")->where(array("id"=>$va['wxuserid']))->find();
            $list[$key]['nickname'] = $wxinfo['nickname'];
            $list[$key]['headimgurl'] = $wxinfo['headimgurl'];
            $list[$key]['c_time'] = date("Y-m-d H:i:s",$va['time']);
        }
		$this->assign('list', $list);
        $this->assign('page', $show);
		$this->display();
	}
	public function setStatus()
	{
		$id = intval($_GET['id']);
		$act = trim($_GET['act']);
		if(!empty($id))
		{
			if($act=="close"){
				M("Comment")->where(array("id"=>$id))->data(array("status"=>0))->save();				
			}
			if($act=="open"){
				M("Comment")->where(array("id"=>$id))->data(array("status"=>1))->save();	
			}
			$this->success("操作成功！");
		}
	}
	public function del()
	{
		$id = intval($_GET['id']);
		if(!empty($id))
		{
			M("Comment")->where(array("id"=>$id))->delete();
			$this->success("删除成功！");
		}
	}
}
?>