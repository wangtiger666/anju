<?php

class WebsystemvalAction extends AdminAction{

    function index(){
        cookie("back", __SELF__);
        $step = $_REQUEST['step'];
  
        if($step=="save"){
                $this->set_bianliang('mingcheng',$_REQUEST['mingcheng']);
                $this->set_bianliang('keywords',$_REQUEST['keywords']);
                $this->set_bianliang('description',$_REQUEST['description']);
                $this->set_bianliang('address',$_REQUEST['address']);
                $this->set_bianliang('beian',$_REQUEST['beian']);
                $this->set_bianliang('dianhua',$_REQUEST['dianhua']);
                $this->set_bianliang('kefu',$_REQUEST['kefu']);
                $this->set_bianliang('qq',$_REQUEST['qq']);
                $this->set_bianliang('shijian',$_REQUEST['shijian']);
                $this->set_bianliang('banquan',$_REQUEST['banquan']);
                $this->set_bianliang('youxiang',$_REQUEST['youxiang']);
                $this->success("修改成功！", U("index"));exit();
        }

        $mingcheng = $this->get_bianliang('mingcheng');
        $keywords = $this->get_bianliang('keywords');
        $description = $this->get_bianliang('description');
        $address = $this->get_bianliang('address');
        $beian = $this->get_bianliang('beian');
        $dianhua = $this->get_bianliang('dianhua');
        $kefu = $this->get_bianliang('kefu');
        $qq = $this->get_bianliang('qq');
        $shijian = $this->get_bianliang('shijian');
        $banquan = $this->get_bianliang('banquan');
        $youxiang = $this->get_bianliang('youxiang');

        $this->assign('mingcheng', $mingcheng); 
        $this->assign('keywords', $keywords); 
        $this->assign('description', $description); 
        $this->assign('logo', $logo);
        $this->assign('address', $address); 
        $this->assign('beian', $beian); 
        $this->assign('dianhua', $dianhua); 
        $this->assign('kefu', $kefu); 
        $this->assign('qq', $qq);
        $this->assign('shijian', $shijian); 
        $this->assign('banquan', $banquan); 
        $this->assign('youxiang', $youxiang);  
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

    function get_bianliang($bianliang){
            $where = array(
                'bianliang' =>$bianliang
            );
            $res = M("yun_websystemval")->where($where)->getfield('value');
            return  $res;
    }

    function set_bianliang($bianliang,$value){
            $where = array(
                'bianliang' => $bianliang
            );
            $dataarr =array(
                'value' => $value
                );
            $res = M("yun_websystemval")->where($where)->data($dataarr)->save();
    }

    function setlogo_bak(){
    	$step = $_REQUEST['step'];
        if($step=="save"){
        	$dataarr =array(
                'panoheaderlogo' => $panoheaderlogo,
                'panofooterlogo' => $panofooterlogo,
                'aboutheaderlogo' => $aboutheaderlogo,
                'aboutfooterlogo' => $aboutfooterlogo,
                'phone' => $phone,
                'weixin' => $weixin,
                'memberlogo' => $memberlogo,
                'adminlogo' => $adminlogo
                );
            $res = M("yunweb_logo")->where($where)->data($dataarr)->save();
            var_dump(1);die;
        }
    	$this->display(); // 输出模板
    }


    function setlogo(){
        cookie("back", __SELF__);
        $step = $_REQUEST['step'];
  
        if($step=="save"){

		        if($_REQUEST['panoheaderlogo']!=$_REQUEST['old_image1']){
					if(!empty($_REQUEST['panoheaderlogo'])){
						$fileurl1 = "/Public/yun/images";
						$name1="logo.png";
						$panoheaderlogo = $this->ExecUpload($_REQUEST['panoheaderlogo'], $_REQUEST['old_image1'], $fileurl1,$name1);
						M("yunweb_logo")->where(array("bianliang"=>"panoheaderlogo"))->save(array("imgsrc"=>$panoheaderlogo));
					}
				}

				if($_REQUEST['panofooterlogo']!=$_REQUEST['old_image2']){
					if(!empty($_REQUEST['panofooterlogo'])){
						$fileurl2 = "/Public/yun/images";
						$name2="footer.png";
						$panofooterlogo = $this->ExecUpload($_REQUEST['panofooterlogo'], $_REQUEST['old_image2'], $fileurl2,$name2);
						M("yunweb_logo")->where(array("bianliang"=>"panofooterlogo"))->save(array("imgsrc"=>$panofooterlogo));
					}
				}

				if($_REQUEST['aboutheaderlogo']!=$_REQUEST['old_image3']){
					if(!empty($_REQUEST['aboutheaderlogo'])){
						$fileurl3 = "/Public/webstyle/images";
						$name3="logo.jpg";
						$aboutheaderlogo = $this->ExecUpload($_REQUEST['aboutheaderlogo'], $_REQUEST['old_image3'], $fileurl3,$name3);
						M("yunweb_logo")->where(array("bianliang"=>"aboutheaderlogo"))->save(array("imgsrc"=>$aboutheaderlogo));
					}
				}

				if($_REQUEST['aboutfooterlogo']!=$_REQUEST['old_image4']){
					if(!empty($_REQUEST['aboutfooterlogo'])){
						$fileurl4 = "/Public/webstyle/images"; 
						$name4="footer_logo.jpg";
						$aboutfooterlogo = $this->ExecUpload($_REQUEST['aboutfooterlogo'], $_REQUEST['old_image4'], $fileurl4,$name4);
						M("yunweb_logo")->where(array("bianliang"=>"aboutfooterlogo"))->save(array("imgsrc"=>$aboutfooterlogo));
					}
				}

		
                if($_REQUEST['phone']!=$_REQUEST['old_image5']){
					if(!empty($_REQUEST['phone'])){
						$fileurl5 = "/Public/webstyle/images";
						$name5="web_tel.jpg";
						$phone = $this->ExecUpload($_REQUEST['phone'], $_REQUEST['old_image5'], $fileurl5,$name5);
						M("yunweb_logo")->where(array("bianliang"=>"phone"))->save(array("imgsrc"=>$phone));
					}
				}

				if($_REQUEST['weixin']!=$_REQUEST['old_image6']){
					if(!empty($_REQUEST['weixin'])){
						$fileurl6 = "/Public/webstyle/images";
						$name6="web_weixin.jpg";
						$weixin = $this->ExecUpload($_REQUEST['weixin'], $_REQUEST['old_image6'], $fileurl6,$name6);
						M("yunweb_logo")->where(array("bianliang"=>"weixin"))->save(array("imgsrc"=>$weixin));
					}
				}

				if($_REQUEST['memberlogo']!=$_REQUEST['old_image7']){
					if(!empty($_REQUEST['memberlogo'])){
						$fileurl7 = "/Public/member/images/index";
						$name7="logo.png";
						$memberlogo = $this->ExecUpload($_REQUEST['memberlogo'], $_REQUEST['old_image7'], $fileurl7,$name7);
						M("yunweb_logo")->where(array("bianliang"=>"memberlogo"))->save(array("imgsrc"=>$memberlogo));
					}
				}

				if($_REQUEST['adminlogo']!=$_REQUEST['old_image8']){
					if(!empty($_REQUEST['adminlogo'])){
						$fileurl8 = "/Public/admin/images/index";
						$name8="logo.png";
						$adminlogo = $this->ExecUpload($_REQUEST['adminlogo'], $_REQUEST['old_image8'], $fileurl8,$name8);
						M("yunweb_logo")->where(array("bianliang"=>"adminlogo"))->save(array("imgsrc"=>$adminlogo));
					}
				}

                $this->success("修改成功！", U("setlogo"));exit();
        }

        $panoheaderlogo = $this->get_logo('panoheaderlogo');
        $panofooterlogo = $this->get_logo('panofooterlogo');
        $aboutheaderlogo = $this->get_logo('aboutheaderlogo');
        $aboutfooterlogo = $this->get_logo('aboutfooterlogo');
        $phone = $this->get_logo('phone');
        $weixin = $this->get_logo('weixin');
        $memberlogo = $this->get_logo('memberlogo');
        $adminlogo = $this->get_logo('adminlogo');
        $this->assign('panoheaderlogo', $panoheaderlogo); 
        $this->assign('panofooterlogo', $panofooterlogo); 
        $this->assign('aboutheaderlogo', $aboutheaderlogo); 
        $this->assign('aboutfooterlogo', $aboutfooterlogo);
        $this->assign('phone', $phone); 
        $this->assign('weixin', $weixin);
        $this->assign('memberlogo', $memberlogo); 
        $this->assign('adminlogo', $adminlogo); 

        $this->display(); // 输出模板
    }

    function get_logo($bianliang){
            $where = array(
                'bianliang' =>$bianliang
            );
            $res = M("yunweb_logo")->where($where)->getfield('imgsrc');
            return  $res;
    }


    function ExecUpload($photo, $oldphoto, $dir="",$name) {
    if ($photo != "") {
        if ($photo == $oldphoto) {
            return $oldphoto;
        } else {
            @unlink(APP_ROOT . $oldphoto);
            $oldname = APP_ROOT . $photo;
            if (!is_dir(APP_ROOT . $dir)) {
                createFolder(APP_ROOT . $dir);
            }
            $newname = APP_ROOT . $dir . "/" . basename($photo);
            if (substr_count($oldname, "station") > 0) {
                rename($oldname, $newname);
            } else {
                copy($oldname, $newname);
            }
            $rename = $dir . "/" . $name;
            $rname = APP_ROOT . $rename;	
            $res = rename($newname,$rname);
            
            return $rename;
        }
    }
}





}

?>
