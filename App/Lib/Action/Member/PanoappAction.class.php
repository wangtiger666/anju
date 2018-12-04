<?php

class PanoappAction extends Action {

    function index()
	{
		$panolist = M("pano")->select();		
		//项目没有缩略图 取场景的第一个
		foreach($panolist as $key=>$val)
		{
			$pano_id = $val['id'];
			//项目缩略图
			$pano_view = M("pano_view")->where(array("pano_id"=>$pano_id))->order("id asc")->find();
			$panolist[$key]['thumb'] = $pano_view['thumb'];
			//项目路径
			$panopath = M("pano_putout")->where(array("pano_id"=>$pano_id))->find();
			$panolist[$key]['panopath'] = $panopath['fileurl'];
		}
		$this->assign("panolist", $panolist);		
		//print_r($panolist);
        $this->display();
    } 

}
?>