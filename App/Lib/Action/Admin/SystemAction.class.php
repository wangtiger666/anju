<?php

class SystemAction extends AdminAction {

    var $systype = array(
        1 => "站点设置",
        2 => "附件设置"
    );

    function show(){
        $this->display(); 
    }

    function home(){
        $todaytime = strtotime(date("Y-m-d"));
        $New_User_num = M('Member')->where('create_time > $todaytime')->count();
        $New_Pano_num = M('Pano')->where('creat_time > $todaytime')->count();
        $User_num = M('Member')->where('1')->count();
        $Pano_num = M('Pano')->where('1')->count();

        $Admin_log = M('Admin_log')->where('1')->order('id asc')->select();

        $New_User_num = isset($New_User_num) ? $New_User_num : '0';
        $New_Pano_num = isset($New_Pano_num) ? $New_Pano_num : '0';
        $User_num = isset($User_num) ? $User_num : '0';
        $Pano_num = isset($Pano_num) ? $Pano_num : '0';

        $this->assign("Admin_log", $Admin_log);
        $this->assign("New_User_num", $New_User_num);
        $this->assign("New_Pano_num", $New_Pano_num);
        $this->assign("User_num", $User_num);
        $this->assign("Pano_num", $Pano_num);
        $this->display(); 
    }


    function index() {
        if ($_POST['dopost'] == 'add') {
            $vartype = $_POST['vartype'];
            $nvarvalue = $_POST['nvarvalue'];
            $nvarname = $_POST['nvarname'];
            $varmsg = $_POST['varmsg'];
            $vargroup = $_POST['vargroup'];

            if ($vartype == 'bool' && ($nvarvalue != 'Y' && $nvarvalue != 'N')) {
                $this->error("布尔变量值必须为'Y'或'N'!", "-1");
                exit();
            }
            if (trim($nvarname) == '' || preg_match("#[^a-z_]#i", $nvarname)) {
                $this->error("变量名不能为空并且必须为[a-z_]组成!", "-1");
                exit();
            }
            $chwhere = array(
                "varname" => $nvarname
            );
            $chrow = M("Sysconfig")->where($chwhere)->find();
            if (is_array($chrow)) {
                $this->error("该变量名称已经存在!", "-1");
                exit();
            }
            $addarr = array(
                'varname' => $nvarname,
                'info' => $varmsg,
                'value' => $nvarvalue,
                'type' => $vartype,
                'groupid' => $vargroup
            );
            M("Sysconfig")->add($addarr);
            $url = U("admin/system/index/?gp=$vargroup");
            $this->success("成功保存变量并更新配置文件！", $url);
            exit();
        }

        if ($_POST['dopost'] == 'save') {
            foreach ($_POST as $k => $v) {
                if (preg_match("#^edit___#", $k)) {
                    $v = $_POST[$k];
                } else {
                    continue;
                }
                $k = preg_replace("#^edit___#", "", $k);
                $where = array(
                    "varname" => $k
                );
                $data = array(
                    "value"=>$v
                );
                M("Sysconfig")->where($where)->save($data);                
            }
            $url = U("admin/system/index");
            $this->success("保存完成！", $url);
            exit();
        }

        $i = 0;
        $config_html = "";
        $config_select = "";
        $config_tb = "";
        foreach ($this->systype as $theid => $thetp) {
            $i++;
            $config_select .= "<option value='$theid'>$thetp</option>";
            if ($i > 1)
                $config_html .= " | <a href='javascript:ShowConfig($i)'>$thetp</a> ";
            else {
                $config_html .= " <a href='javascript:ShowConfig($i)'>$thetp</a> ";
            }
            $readwhere = array(
                "groupid" => $theid
            );
            $readrow = M("Sysconfig")->where($readwhere)->select();
            $config_tb .= '<table width="100%" class="result_back_box table" style="display:none;" border="0" cellspacing="1" cellpadding="1" bgcolor="#d6d6d6">';
            $config_tb .= '<tr class="tr_hui" align="center" height="30"><td width="300">参数说明</td><td>参数值</td><td width="220">变量名</td></tr>';
            $c = 1;
            foreach ($readrow as $readv) {
                if ($c % 2 == 0) {
                    $bgcolor = "f6f6f6";
                } else {
                    $bgcolor = "#ffffff";
                }
                $c++;
                $config_tb .= '<tr align="center" bgcolor="' . $bgcolor . '" height="30">';
                $config_tb .= '<td width="300">' . $readv['info'] . '：</td>';
                $config_tb .= '<td align="left" style="padding:3px;">';
                if ($readv['type'] == 'bool') {
                    $c1 = '';
                    $c2 = '';
                    $readv['value'] == 'Y' ? $c1 = " checked" : $c2 = " checked";
                    $config_tb .= '<input type="radio" class="np" name="edit___' . $readv['varname'] . '" value="Y"' . $c1 . '>是 ';
                    $config_tb .= '<input type="radio" class="np" name="edit___' . $readv['varname'] . '" value="N"' . $c2 . '>否 ';
                } else if ($readv['type'] == 'bstring') {
                    $config_tb .= '<textarea name="edit___' . $readv['varname'] . '" row="4" id="edit___' . $readv['varname'] . '" class="textarea_info" style="width:98%;height:50px">' . htmlspecialchars($readv['value']) . '</textarea>';
                } else if ($readv['type'] == 'number') {
                    $config_tb .= '<input type="text" name="edit___' . $readv['varname'] . '" id="edit___' . $readv['varname'] . '" value="' . $readv['value'] . '" style="width:30%">';
                } else {
                    $config_tb .= '<input type="text" name="edit___' . $readv['varname'] . '" id="edit___' . $readv['varname'] . '" value="' . htmlspecialchars($readv['value']) . '" style="width:80%">';
                }
                $config_tb .= '</td>';
                $config_tb .= '<td>' . $readv['varname'] . '</td>';
                $config_tb .= '</tr>';
            }
            $config_tb .= '</table>';
        }

        $this->assign("config_select", $config_select);
        $this->assign("config_html", $config_html);
        $this->assign("config_tb", $config_tb);
        $this->display();
    }
	
	public function weixin()
	{
		$id=$_REQUEST['id'];
        $where = "id=$id";
		if (I("post.dopost") == "save")
		{
			$appid = I('post.appid');
			$appsecret = I('post.appsecret');
			$data = array(
				"appid"=>$appid,
				"appsecret"=>$appsecret
			);
			M("Wxconfig")->where($where)->save($data);
			$this->success('修改成功',U("admin/system/weixin"));
            exit();
		}

		$row = M("Wxconfig")->where("id=1")->find();
        $row2 = M("Wxconfig")->where("id=2")->find();
        $this->assign('row', $row);
        $this->assign('row2', $row2);
		$this->display();
	}

    public function duanxin()
    {
        $id=$_REQUEST['id'];
        $where = "id=$id";
        if (I("post.dopost") == "save")
        {
            $key = I('post.key');
            $mobanid = I('post.mobanid');
            $data = array(
                "key"=>$key,
                "mobanid"=>$mobanid
            );
            M("Dxconfig")->where($where)->save($data);
            $this->success('修改成功',U("admin/system/duanxin"));
            exit();
        }
        $row = M("Dxconfig")->where("id=1")->find();
        $this->assign('row', $row);
        $this->display();
    }

    public function youjian()
    {
        $id=$_REQUEST['id'];
        $where = "id=$id";
        if (I("post.dopost") == "save")
        {   
            $cfg_smtp_server = I('post.cfg_smtp_server');
            $cfg_smtp_port = I('post.cfg_smtp_port');
            $cfg_smtp_usermail = I('post.cfg_smtp_usermail');
            $cfg_smtp_user = I('post.cfg_smtp_user');
            $cfg_smtp_pwd = I('post.cfg_smtp_pwd');
            $data = array(
                "cfg_smtp_server"=>$cfg_smtp_server,
                "cfg_smtp_port"=>$cfg_smtp_port,
                "cfg_smtp_usermail"=>$cfg_smtp_usermail,
                "cfg_smtp_user"=>$cfg_smtp_user,
                "cfg_smtp_pwd"=>$cfg_smtp_pwd,
            );
            M("Yjconfig")->where($where)->save($data);
            $this->success('修改成功',U("admin/system/youjian"));
            exit();
        }
        $row = M("Yjconfig")->where("id=1")->find();
        $this->assign('row', $row);
        $this->display();
    }

    public function moban()
    {
       $ajax=$_POST['ajax'];
       $id=$_POST['id'];
       $where = "varname='web_moban'";
       if($ajax&&$id){
               $data = array(
                "value"=>$id,
            );
        $res=M("Sysconfig")->where($where)->save($data);
        echo json_encode($res);die;
       }
        $row = M("Sysconfig")->where($where)->getfield('value');
        $this->assign('row', $row);
        $this->display();
    }

    public function fuwu()
    {
        $this->display();
    }

    public function tupian()
    {
        $id=$_REQUEST['id'];
        $where = "id=$id";
        if (I("post.dopost") == "save")
        {   
            $cid = I('post.cid');
            $secretKEY = I('post.secretKEY');
            $bucket = I('post.bucket');
            $cdn = I('post.cdn');
            $area = I('post.area');
            $data = array(
                "cid"=>$cid,
                "secretKEY"=>$secretKEY,
                "bucket"=>$bucket,
                "cdn"=>$cdn,
                "area"=>$area,
            );
            M("Tpconfig")->where($where)->save($data);
            $this->success('修改成功',U("admin/system/tupian"));
            exit();
        }
        $row = M("Tpconfig")->where("id=1")->find();
        $this->assign('row', $row);
        $this->display();
    }



}

?>
