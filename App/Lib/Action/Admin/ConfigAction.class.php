<?php

class ConfigAction extends AdminAction {

	//高德地图
    public function gdgetpoint()
    {

    	$detailAddress = I('get.detailAddress');
    	$this->assign('detailAddress', $detailAddress);
        $this->display();
    }
	

}

?>
