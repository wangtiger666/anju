<?php

class YunNavAction extends AdminAction{

    function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M("yun720_nav");
		$where = "type=1";

        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 50); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出

		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order("orderlist")->limit($Page->firstRow . ',' . $Page->listRows)->select();
       
        $tree = $this->getTree($list,0,0);
        $this->assign('tree', $tree); // 赋值数据集
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

    function headerindex(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M("yun720_nav");
		$where = "type=2";

        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 50); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出

		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order("orderlist")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		
       
        $tree = $this->getTree($list,0,0);
        $this->assign('tree', $tree); // 赋值数据集
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }




    function show(){
        $this->display(); // 输出模板
    }


    function del(){
    	$id=$_REQUEST['id'];
        if ($id != "") {
        	$pidlist =  M("yun720_nav")->where("pid=".$id)->select();

        	if($pidlist){
        		$this->success("请删除分类下子栏目继续操作", U("index"));exit();
        	}else{
        		M("yun720_nav")->where("id=".$id)->delete();
        		$this->success("删除成功！", U("index"));exit();
        	}
        }
        $this->success("操作失败！", U("index"));exit();
    }

    function headeradd()
	{
		//选择出pid为0的
		$where=array(
			'pid'=>'0'
			);
		$pidlist =  M("yun720_nav")->where($where)->order("id asc")->select();

		$plist = "<select name='pid'><option value='0'>作为顶级栏目</option>";                           
		foreach ($pidlist as $key => $value) {
  				$plist .="<option value='".$value['id']."'>".$value['name']."</option>";	
		}
		$plist .="</select>";

		if ($_POST['dopost'] == "save")
		{
			$name = I("post.name");
            $outlink = I("post.outlink");
            $orderlist = I("post.orderlist");
            $isshow = I("post.isshow");
            $isblank =I("post.isblank");
            $pid =I("post.pid");
			$type =("post.type");

            $addmap = array(
                "name" => $name,
                "outlink" => $outlink,
                "orderlist" => $orderlist,
                "isshow" => $isshow,
                "isblank" => $isblank,
                "pid" =>$pid
            );
            $res = M("yun720_nav")->add($addmap);
			redirect("index");
            exit();
		}

		$this->assign('plist', $plist); 
		$this->display();	
	}

	function add()
	{
		//选择出pid为0的
		$where=array(
			'pid'=>'0'
			);
		$pidlist =  M("yun720_nav")->where($where)->order("id asc")->select();

		$plist = "<select name='pid'><option value='0'>作为顶级栏目</option>";                           
		foreach ($pidlist as $key => $value) {
  				$plist .="<option value='".$value['id']."'>".$value['name']."</option>";	
		}
		$plist .="</select>";

		if ($_POST['dopost'] == "save")
		{
			$name = I("post.name");
            $outlink = I("post.outlink");
            $orderlist = I("post.orderlist");
            $isshow = I("post.isshow");
            $isblank =I("post.isblank");
            $pid =I("post.pid");
			$type =I("post.type");

            $addmap = array(
                "name" => $name,
                "outlink" => $outlink,
                "orderlist" => $orderlist,
                "isshow" => $isshow,
                "isblank" => $isblank,
                "pid" =>$pid,
				"type" =>$type
            );
            $res = M("yun720_nav")->add($addmap);
			
			redirect("index");
            exit();
		}

		$this->assign('plist', $plist); 
		$this->display();	
	}
	
	function edit()
	{
		$id = intval($_REQUEST['id']);


		if ($_POST['dopost'] == "save")
		{
			$name = I("post.name");
            $outlink = I("post.outlink");
            $orderlist = I("post.orderlist");
            $isshow = I("post.isshow");
            $isblank =I("post.isblank");
            $pid =I("post.pid");
			$type =I("post.type");


            $addmap = array(
                "name" => $name,
                "outlink" => $outlink,
                "orderlist" => $orderlist,
                "isshow" => $isshow,
                "isblank" => $isblank,
                "pid" =>$pid,
				"type" =>$type
            );
            $res = M("yun720_nav")->where(array("id"=>$id))->data($addmap)->save();
			
			$this->success("修改成功！", U("index"));
            exit();
		}
		$info = M("yun720_nav")->where(array("id"=>$id))->find();

		$pid = $info['pid'];

		$where=array(
			"pid"=>0
			);
		$pidlist =  M("yun720_nav")->where($where)->order("id asc")->select();
		$plist = "<select name='pid'><option value='0'>作为顶级栏目</option>";                           
		foreach ($pidlist as $key => $value) {
				$sel=($value['id']==$pid)?"selected":"";
  				$plist .="<option   ".$sel."  value='".$value['id']."'>".$value['name']."</option>";	
		}
		$plist .="</select>";
	
   		$this->assign("plist", $plist);
		$this->assign("info", $info);
		$this->assign("id", $id);
		$this->display();
	}
	
	function setshow()
	{
		$id = intval($_REQUEST['id']);
        $is_recommend_s = M("yun720_nav")->where("id = $id")->getfield("isshow");
        $is_recommend = ($is_recommend_s=='0') ? "1" : '0';
        $res = M("yun720_nav")->where(array("id"=>$id))->data(array("isshow"=>$is_recommend))->save();
        echo json_encode($is_recommend);die;
	}
	
	function setblank()
	{
		$id = intval($_REQUEST['id']);
        $is_recommend_s = M("yun720_nav")->where("id = $id")->getfield("isblank");
        $is_recommend = ($is_recommend_s=='0') ? "1" : '0';
        $res = M("yun720_nav")->where(array("id"=>$id))->data(array("isblank"=>$is_recommend))->save();
        echo json_encode($is_recommend);die;
	}

	function getTree($data, $pId,$n)
    {
        $html = '';
        $fenjie=array();
        foreach($data as $k => $v)
        {
           if($v['pid'] == $pId)
           { 
	           if($v['pid']==0){
	           		$frist="";
	           }else{
	           		$frist="|";
	           }
		    if($n){
		    	for ($i=0; $i < $n; $i++) { 
		    		$v['char']=$v['char']."-";
		    		$v['nbs']=$v['nbs']."&nbsp&nbsp&nbsp&nbsp";
		    	}
		    }
	
		    $del='<input class="btn btn-white btn-sm" onclick="window.location.href ='."'/admin/yun_artlanmu/del/cid/$v[id]'".'"  value="删除"   type="button">';
		 	
		    $blankgif_img = ($v['isblank']=='0') ? "no.gif" : 'yes.gif';
		    $isshow_img = ($v['isshow']=='0') ? "no.gif" : 'yes.gif';

		    
            $html .= '<tr class="tr_white">
            				<td align="center">'. $v['id'].'</td>
                            <td style="margin-left:50px;">
                                  '.$v['nbs'].$frist.$v['char'].$v['name'].'</td>
                            <td align="center">
                            '.$v['outlink'].'
                            </td>'.' <td align="center">
                               <img  id="img_'.$v['id'].'"   src="__PUBLIC__/admin/images/'.$isshow_img.'"   onclick="set_show('.$v['id'].');" > 
                            </td>'.' <td align="center">
                               <img  id="img2_'.$v['id'].'"  src="__PUBLIC__/admin/images/'.$blankgif_img.'" onclick="set_blank('.$v['id'].');" > 
                            </td>'.'
                                <td align="center">
							<input class="btn btn-white btn-sm" onclick="window.location.href ='."'/admin/YunNav/edit/id/$v[id]'".'" value="修改" type="button">
							<input class="btn btn-white btn-sm" onclick="window.location.href ='."'/admin/YunNav/del/id/$v[id]'".'" value="删除" type="button">
                            </td>';
            $html .= self::getTree($data, $v['id'],($n+1));
            $html = $html.'</tr>';
           }
        }
        return $html ? '<tbody">'.$html.'</tbody>' : $html ;
    }
}

?>
