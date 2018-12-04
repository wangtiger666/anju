<?php

class UserAction extends MemberAction {

    public function index() {
        $where = array(
            "id" => $this->member_id
        );
		if (I("post.dopost") == "save")
		{
			$nickname = trim(I('post.nickname'));
			$phone = I('post.phone');
			$qq = I('post.qq');
			$email = I('post.email');
			$intro = I('post.intro');
			$province = I('post.province');
			$city = I('post.city');
			$area = I('post.area');
			$index_pano = I('post.index_pano');
			
			$company = I('post.company');
			$company_url = I('post.company_url');
			if(!empty($phone)){
				if(strlen($phone) <> 11 || !preg_match("/^1[34578]\\d{9}$/", $phone)){
					$this->error('手机格式错误');exit;
				}
			}
			if(!empty($email)){
				if(strlen($email) <= 6 || !preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)){
					$this->error('请输入有效的邮箱地址');exit;
				}
			}
			$fileurl = "/uploads/avatar/" . substr(md5($this->member_id), 5, 15);
			if(!empty($_POST['image'])){
				$headimg = ExecUpload($_POST['image'], $_POST['old_image'], $fileurl);
				M("Member")->where($where)->save(array("headimg"=>$headimg));
			}
			
			$fileurl = "/uploads/avatar/" . substr(md5($this->member_id), 5, 15);
			if(!empty($_POST['imageewm'])){
				$ewm = ExecUpload($_POST['imageewm'], $_POST['old_imageewm'], $fileurl);
				M("Member")->where($where)->save(array("ewm"=>$ewm));
			}

			$fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/uiimage";
			if(!empty($_POST['company_logo'])){
				$company_logo = ExecUpload($_POST['company_logo'], $_POST['old_company_logo'], $fileurl);
				M("Member")->where($where)->save(array("company_logo"=>$company_logo));
			}

			$data = array(
				"nickname"=>$nickname,
				"phone"=>$phone,
				"qq"=>$qq,
				"email"=>$email,
				"intro"=>strip_tags($intro),
				"province"=>$province,
				"city"=>$city,
				"area"=>$area,
				"index_pano"=>$index_pano,
				"company_url"=>$company_url

				);
			M("Member")->where($where)->save($data);
			$this->success('修改成功');
            exit();
		}
		
		
		

        $row = M("Member")->where($where)->find();
        $this->assign('row', $row);

        $this->display();
    }

    function repassword() {
        $mi = I("post.mm");
        if ($mi != "") {
            $newp = substr(md5($mi), 5, 20);
            $data = array(
                "password" => $newp
            );
            M("Member")->where(array("id" => $this->member_id))->save($data);
            echo "showMsg(\"密码修改完成！\",1);";
            exit();
        }
    }

}

?>
