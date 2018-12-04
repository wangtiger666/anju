<?php
class ChannelAction extends PublicAction {
    public function index(){

    	import('ORG.Util.Page'); // 导入分页类
    	$id = I('get.id');
		$where = "1";
        if($id){
            $where .=" AND hangyeid=".$id;
        }else{
        	$where .=" AND is_recommend= 1 ";
        	$recommend="1";
        }
		
        
        $count = M("pano")->where($where)->count();
        $Page = new Page($count, 35); 
        $panolist = M("pano")->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $show = $Page->show();

		if(!empty($panolist))
		{
			foreach($panolist as $key=>$val)
			{
				$minfo = M("Member")->where(array("id"=>$val['member_id']))->find();
				if(!empty($minfo['headimg'])) $headimg = $minfo['headimg'];
				else $headimg = "/Public/member/images/common/no_img.jpg";
				$panolist[$key]['author'] = $minfo['nickname'];
				$panolist[$key]['headimg'] = $headimg;
				$panolist[$key]['panothumb'] = getPanoThumb($val['id']);
				$hits[$key] = M("hitscount")->where(array("pano_id"=>$val['id']))->getField("hits");
				$zan[$key] = M("hitscount")->where(array("pano_id"=>$val['id']))->getField("zan");
				if($hits[$key]){
					if($hits[$key]>=10000){
						$hits[$key] = ($hits[$key]/10000)."万";
					}
				}else{
					$hits[$key] =0;
				}
				if($zan[$key]){
					if($zan[$key]>=10000){
						$zan[$key] = ($zan[$key]/10000)."万";
					}
				}else{
					$zan[$key] =0;
				}
				$panolist[$key]['hits'] = $hits[$key];
				$panolist[$key]['zan']  = $zan[$key];
				$panolist[$key]['panopath'] = "/t/".$val['guid'];
			}
		}

		$channel_list = M("YunwebHangye")->order("listorder asc")->limit(0,10)->select();
		$channelnav=$_GET['id'];

		$this->assign("page", $show);
		$this->assign("channel_list", $channel_list);
		$this->assign("id", $id);
		$this->assign("recommend", $recommend);
        $this->assign("channelnav", $channelnav);
		$this->assign("pano_list", $panolist);
		$this->display();

    }
}

