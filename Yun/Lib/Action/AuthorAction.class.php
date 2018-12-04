<?php
class AuthorAction extends PublicAction {

    public function index(){
		$filter = I('get.filter') ? intval(I('get.filter')) : 1 ;
		$this->assign("filter",$filter);
		$order = "id desc";
		$where = "status=1";
		$Model = M("Member");
		if($filter==1)
		{
			$authorlist = $Model->where($where)->order("rand()")->select();
		}
		if($filter==2)
		{	
			
			$author_list = $Model->where($where)->select();
			$res=array();
			foreach ($author_list as $key => $value) {
					$histwhere =array("member_id"=>$value['id']);
					$hits_list =  M("hitscount")->where($histwhere)->select();
					
					$hits=0;
					foreach ($hits_list as $keyt => $valuet) {
							$hits+=$valuet['hits'];
					}

					$res[$key]['member_id']=$value['id'];
					$res[$key]['hits']=$hits;
			}

			$sort = array(
		        'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
		         'field'     => 'hits',       //排序字段
			 );
			 $arrSort = array();
			 foreach($res AS $uniqid => $row){
			     foreach($row AS $key=>$value){
			         $arrSort[$key][$uniqid] = $value;
			     }
			 }
			 if($sort['direction']){
			     array_multisort($arrSort[$sort['field']], constant($sort['direction']), $res);
			 }

			 if($res){
			 	//根据排序 列表
			 	foreach ($res as $key => $val) {
			 		$i_where = array("id"=>$val['member_id']);
			 		$authinfo="";
			 		$authinfo = $Model->where($i_where)->select();
			 		$authorlist[$key] = $authinfo[0];
			 	}
			 }
			/*$table_member = C("DB_PREFIX")."member";
			$table_hitscount = C("DB_PREFIX")."hitscount";

			$authorlist = $Model->join("left join $table_hitscount h on ".$table_member.".id=h.memberid")->where($table_member." status=1")->order("h.hits desc")->select();	*/	

		}
		if($filter==3)
		{
			$authorlist = $Model->where($where)->order("pano_count desc")->select();
		}
		if($filter==4)
		{
			$authorlist = $Model->where($where)->order("id desc")->select();
		}



		if(!empty($authorlist))
		{
			foreach($authorlist as $key=>$val)
			{
				if(!empty($val['headimg'])) $headimg = $val['headimg'];
				else $headimg = "/Public/member/images/common/no_img.jpg";
				$authorlist[$key]['headimg'] = $headimg;
				$authorlist[$key]['zuopin']  = M("pano")->where("member_id='".$val['id']."'")->count();
				$renqi_list= M("hitscount")->where("member_id='".$val['id']."'")->select();

				$renqi_res = 0;
				foreach ($renqi_list as $keyt => $value) {
						$renqi_res += $value['hits'];
				}
				$authorlist[$key]['renqi'] = intval($renqi_res);
			}
		}
		/*echo "<pre>";
		var_dump($authorlist);die;*/




		$this->assign("authorlist",$authorlist);
		$this->assign("authornav",1);
		//$Model->where($where)->order($order)->select();
		//print_r($Model);
		//print_r($authorlist);
		$this->display();
    }


    public   function pannolist(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M("Pano");
        $id = I("get.id");//分类

        $where = array(
        	"member_id" => $id
        	);

        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
      	$Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
       	$list = $mm->where($where)->order("id")->limit($Page->firstRow . ',' . $Page->listRows)->select();
   
        $show = $Page->show(); // 分页显示输出

        $hiscount=0;
        foreach ($list as $k=>$li) {
        	$thiscount=0;
            $list[$k]['fileurl'] = "/t/".$li['guid'];
            $list[$k]['panothumb'] = getPanoThumb($li['id']);
       		$thiscount = M("hitscount")->where(array("pano_id"=>$li['id']))->getField("hits");

       		$hits[$key] = M("hitscount")->where(array("pano_id"=>$li['id']))->getField("hits");
			$zan[$key] = M("hitscount")->where(array("pano_id"=>$li['id']))->getField("zan");

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

			$list[$k]['zan']=$hits[$key];
			$list[$k]['hits']=$zan[$key];

       		$hiscount += $thiscount;
        }

        $photocount =  M("pano")->where($where)->count();

        //根据ID选择出会员信息
         $u_where = array(
        	"id" => $id
        	);
        $userinfo = M("Member")->where($u_where)->find();

        $this->assign('photocount', $photocount);
        $this->assign('hiscount', $hiscount);
        $this->assign('userinfo', $userinfo); 
		$this->assign('group', $group); // 赋值数据集
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }




}