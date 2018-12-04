<?php

class YunArtlanmuAction extends AdminAction{

    function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M("yun720_lanmu");
		$where = "1";

        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 1000); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出

		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order("listorder")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $tree = $this->getTree($list, 0,0);

        // var_dump($tree);die;
        $this->assign('tree', $tree); // 赋值数据集
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

    function del() {
        if ($_GET['cid'] != "") {
            M("yun720_lanmu")->where("id=" . $_GET['cid'])->delete();
        }
        $this->success("删除成功！", U("index"));
    }

	function add()
	{
		//接受id 选择出栏目
        $cid = $_REQUEST['cid'];
        //选择出上级栏目

        $cattree = $this->selecttree($cid);

        if ($_POST['dopost'] == "save")
		{
			$pid=$_POST['cid'];
            $name = trim(I("post.name"));
            $listorder = I("post.listorder");

            $addmap = array(
                "pid" => $pid,
                "name" => $name,
                "listorder" => $listorder
            );
            $res = M("yun720_lanmu")->add($addmap);
			redirect("index");
            exit();
		}

        $this->assign('cattree', $cattree); // 赋值数据集
		$this->display();	
	}
	
	function edit()
	{
		$id = intval($_REQUEST['cid']);
        if($id){
            //根据栏目ID选择出栏目父ID
            $where=array(
                'id'=>$id
                );
            $pid = M("yun720_lanmu")->where($where)->getfield('pid'); 
            $cattree = $this->selecttree($pid);
        }

		if ($_POST['dopost'] == "save")
		{	
            $name = I("post.name");
            $pid = I("post.cid");
            $listorder = I("post.listorder");
            $posidarr=array( 
                    "pid" =>$pid,
                    "name" => $name,
                    'listorder' =>$listorder
                    );
            $posi_res = M("yun720_lanmu")->where(array("id"=>$id))->data($posidarr)->save();
            $this->success("修改成功！", U("index"));
			exit;
		}
	
   		$info = M("yun720_lanmu")->where(array("id"=>$id))->find();	
        $this->assign("cattree", $cattree);		
		$this->assign("group", $group);
		$this->assign("info", $info);
		$this->assign("id", $id);
		$this->display();
	}
	


    //递归栏目列表函数  data 数据  pid 父id  n 递归深度
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
		    //32ID的为系统自带 不要删除

		    if($v['id']=='32'){
		    	$del="";
		    }else{
		    	$del='<input class="btn btn-white btn-sm" onclick="window.location.href ='."'/admin/yun_artlanmu/del/cid/$v[id]'".'"  value="删除"   type="button">';
		    }

            $html .= '<tr class="tr_white">
            				<td align="center">'. $v['id'].'</td>
                            <td style="margin-left:50px;">
                                  '.$v['nbs'].$frist.$v['char'].$v['name'].'</td>
                            <td align="center">
                            '.$v['listorder'].'
                            </td>
                            <td align="center">
                            <input class="btn btn-white btn-sm" onclick="window.location.href ='."'/admin/yun_article/add/cid/$v[id]'".'" value="添加内容" type="button">
                            <input class="btn btn-white btn-sm" onclick="window.location.href ='."'/admin/yun_artlanmu/add/cid/$v[id]'".'" value="添加子栏目" type="button">
                            <input class="btn btn-white btn-sm" onclick="window.location.href ='."'/admin/yun_artlanmu/edit/cid/$v[id]'".'" 
                                value="修改" type="button">'.$del.'
                            </td>';
            $html .= self::getTree($data, $v['id'],($n+1));
            $html = $html.'</tr>';
           }
        }
        return $html ? '<tbody">'.$html.'</tbody>' : $html ;
    }

    function selecttree($id){  
        $data = M("yun720_lanmu")->where($where)->order("listorder")->select(); 
        $option = $this->get_select_Tree($data,0,0,$id);
        $res = "<select name='cid'><option value='0'>请选择...</option> ".$option."</select>";
        return $res; 
    }

    //递归栏目列表选择函数   data 数据  pid 父id  n 递归深度 cid 选中的栏目ID
    function get_select_Tree($data, $pId,$n,$cid)
    {
        $html = '';
        $fenjie=array();
        foreach($data as $k => $v)
        {
           if($v['pid'] == $pId)
           { 
            $frist = ($v['pid']!=0) ? "|": "";
            if($n){
                for ($i=0; $i < $n; $i++) { 
                    $v['char']=$v['char']."-";
                    $v['nbs']=$v['nbs']."&nbsp&nbsp&nbsp&nbsp";
                }
            }

            $selected = $v['id']==$cid ? "selected": " " ;
            $html .= '<option '.$selected.' value ="'.$v['id'].'">'.$v['nbs'].$frist.$v['char'].$v['name'];              
            $html .= self::get_select_Tree($data, $v['id'],($n+1),$cid);
            $html = $html.'</option>';
           }
        }
        return $html;
    }


}
?>
