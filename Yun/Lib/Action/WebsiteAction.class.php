<?php
// 本类由系统自动生成，仅供测试用途
class WebsiteAction extends PublicAction {
    public function index(){
    		$hangye = getWebHangye();//行业
            $area =   getWebArea();//地区
            $banner = getWebPosAd(1);//首页banner

            $hangye = getWebHangye();//行业
            $area =   getWebArea();//地区
          
            if($hangye){
                foreach ($hangye as $key => $value) {
                  $hangyeid[$key]=$value['id'];
                }
                $hangyeidstr = implode(",", $hangyeid);
            }
            if($area){
              foreach ($area as $key => $value) {
                  $areaid[$key]=$value['id'];
                }
              $areaidstr = implode(",", $areaid);
            }

            $hangyepano1 = getRecPano_home($hangyeidstr,24,'hangye');
            $areapano1   = getRecPano_home($areaidstr,9,'area');

            //首页推荐文章
            $leftarticle = getRecommendArt(0,6);
            $rightarticle =  getRecommendArt(6,6);
            $zhuantiarticle = getRecommendArt(12,10);

            $this->assign("hangyepano1",$hangyepano1);
            $this->assign("areapano1",$areapano1);


            $this->assign("hangye",$hangye);
            $this->assign("area",$area);
            $this->assign("banner",$banner);
            $this->assign("zhuantiarticle",$zhuantiarticle);
            $this->assign("rightarticle",$rightarticle);
            $this->assign("leftarticle",$leftarticle);

    		$this->display();
    }

    public function qjzs(){
    	      $banner = getWebPosAd(2);//全景展示栏目banner
            $hangye = getWebHangye();//行业
            $area =   getWebArea();//地区
            $ad = getWebPosAd(3);

            //选取前三个地区 前三个 行业
            if($hangye){
                $hangye1_id = $hangye['0']['id'];
                $hangye2_id = $hangye['1']['id'];
                $hangye3_id = $hangye['2']['id'];
            }
            if($area){
                $area1_id =$area['0']['id'];
                $area2_id =$area['1']['id'];
                $area3_id =$area['2']['id'];
            }
            $areapano1   = getRecPano($area1_id,9,'area');//全景展示热门地区1 9条
            $hangyepano1 = getRecPano($hangye1_id,9,'hangye');//全景展示热门行业1 9条
            $hangyepano2 = getRecPano($hangye2_id,9,'hangye');//全景展示热门行业2 9条
            $hangyepano3 = getRecPano($hangye3_id,9,'hangye');//全景展示热门行业3 9条
            $areapano1   = getRecPano($area1_id,9,'area');//全景展示热门地区1 9条
            $areapano2   = getRecPano($area2_id,9,'area');//全景展示热门地区2 9条
            $areapano3   = getRecPano($area3_id,9,'area');//全景展示热门地区3 9条

            $this->assign("ad",$ad);
            $this->assign("banner",$banner);
            $this->assign("hangye",$hangye);
            $this->assign("area",$area);
            $this->assign("hangyepano1",$hangyepano1);
            $this->assign("hangyepano2",$hangyepano2);
            $this->assign("hangyepano3",$hangyepano3);

            $this->assign("areapano1",$areapano1);
            $this->assign("areapano2",$areapano2);
            $this->assign("areapano3",$areapano3);

    		    $this->display();
    }


    public function qjzscat(){
            $hangye = getWebHangye();//行业
            $area =   getWebArea();//地区
            //选择全景进行展示
            import('ORG.Util.NewPage'); // 导入分页类

            $areaid = $_REQUEST['areaid'];
            $hangyeid = $_REQUEST['hangyeid'];
            //根据行业ID选择出行业名称
            $hangyename = M("yunweb_hangye")->where("id = $hangyeid")->getfield("name");
            if(empty($hangyename)){
              $hangyename = "全部";
            }
           
            $cat_name=$hangyename;
           

            $where=array();
            if($areaid){ 
              $where['areaid']=$areaid;
            }
            if($hangyeid){
              $where['hangyeid']=$hangyeid;
            }
          
            $count = M("pano")->where($where)->count();
            $Page = new NewPage($count,30);
            $pos_pano = M("pano")->where($where)->order("id desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $show = $Page->show();

            if(!empty($pos_pano))
            {
              foreach($pos_pano as $key=>$val)
              {
                $pos_pano[$key]['author'] = M("Member")->where(array("id"=>$val['member_id']))->getField("nickname");
                $pos_pano[$key]['thumb'] = getPanoThumb($val['id']);
				
                $panopath = "/t/".$val['guid'];
                if($panopath) $pos_pano[$key]['panopath'] = $panopath;
              }
            }
            $list = $pos_pano;
            
            $this->assign("areaid",$areaid);
            $this->assign("hangyeid",$hangyeid);
            $this->assign("hangyename",$cat_name);
            $this->assign("page",$show);
            $this->assign("area",$area);
            $this->assign("hangye",$hangye);
            $this->assign("list",$list);
            $this->assign("is_kaiqi",$is_kaiqi);
            $this->display();
    }

    public function search(){
            $keywords=$_GET['keywords'];
            import('ORG.Util.NewPage'); // 导入分页类
            if($keywords){
                $count = M("pano")->where("title like '%$keywords%' ")->count();
                $Page = new NewPage($count, 30);
                $pos_pano = M("pano")->where( " title like '%$keywords%' " )->limit($Page->firstRow . ',' . $Page->listRows)->select();
                $show = $Page->show();

                foreach($pos_pano as $key=>$val)
                {
                  $pos_pano[$key]['member_id'] = M("Pano")->where(array("id"=>$val['id']))->getField("member_id");
                  $pos_pano[$key]['title'] = M("Pano")->where(array("id"=>$val['id']))->getField("title");
                  $pos_pano[$key]['author'] = M("Member")->where(array("id"=>$pos_pano[$key]['member_id']))->getField("nickname");
                 
                  $pano_view[$key] = M("pano_view")->where(array("pano_id"=>$val['id']))->find();
                  if(!empty($pano_view[$key]['thumb'])){
                    $thumb[$key] = $pano_view[$key]['thumb'];
                  }else{
                    $thumb[$key] = "/Public/member/images/pano/pano.jpg";
                  }
                  $pos_pano[$key]['thumb'] = $thumb[$key];

                  $panopath = M("pano_putout")->where(array("pano_id"=>$val['id']))->getField("fileurl");
                  if($panopath) $pos_pano[$key]['outlink'] = $panopath."/pano";
              }
            }

            $panolist = $pos_pano;
            $this->assign("count",$count);
            $this->assign("page",$show);
            $this->assign("keywords",$keywords);
            $this->assign("panolist",$panolist);
            $this->display('search');
    }

     public function qjzt(){
          $banner = getWebPosAd(4);//首页banner
     	    $jianjie = getPosArticle(17);//全景专题简介
        	$redianzixun = getPosArticle(18);//热点资讯
     	    $pano1 = getRecommendPano(0,18);
     	    $pano2 = getRecommendPano(0,6);
     	    $dongtai = getCatArticle(38,5);
         	$quanjingnew  = getCatArticle(34,8);

     	  	$about = getPosArticle(21);//关于我们
          $shipin = getWebPosAd(10);

          //联系我们 系统变量
          $this->assign("shipin",$shipin);
          $this->assign("banner",$banner);
          $this->assign("jianjie",$jianjie);
          $this->assign("redianzixun",$redianzixun);
          $this->assign("pano1",$pano1);
          $this->assign("pano2",$pano2);
         	$this->assign("quanjingnew",$quanjingnew);
         	$this->assign("dongtai",$dongtai);
         	$this->assign("about",$about);
    		  $this->display();
    }

    public function qjfx(){
        $hangye = getCatArticle(36,6);
        $pano1 = getRecommendPano(0,9);
        $pano2 = getRecommendPano(10,8);

        $this->assign("hangye",$hangye);
        $this->assign("pano1",$pano1);
        $this->assign("pano2",$pano2);
    		$this->display();
    }

    public function wzzx(){

           $zixun = getCatArticle(34,4);
           $zixun2 = getCatArticle(35,4);
           $zixun3 = getCatArticle(36,4);
           $xinwen = getCatArticle(34,7);
           $zhishi = getCatArticle(35,7);
           $hangye = getCatArticle(36,12);
           $zhuanlan = getCatArticle(37,5);
           $wzdt   = getCatArticle(38,6);

          $left_article = $this->get_rand_article(4);
          $banner = getWebPosAd(11);
          $ad1 = getWebPosAd(12);
          $ad2 = getWebPosAd(13);
          $ad3 = getWebPosAd(14);
          $ad4 = getWebPosAd(15);
          $ad5 = getWebPosAd(16);

          $pano1 = getRecommendPano(0,2);
     	    $pano2 = getRecommendPano(3,8);

          //文章总数统计
          $count = M("yunweb_article")->where("1")->count();
          //获取文章的栏目
          $lanmu = M("yunweb_lanmu")->where("pid=32")->order("listorder")->select();
    			  
          $this->assign("pano1",$pano1);
          $this->assign("pano2",$pano2);
          $this->assign("ad1",$ad1); 
          $this->assign("ad2",$ad2); 
          $this->assign("ad3",$ad3);
          $this->assign("ad4",$ad4);
          $this->assign("ad5",$ad5);
          $this->assign("banner",$banner);    
      		$this->assign("wzdt",$wzdt);
      		$this->assign("zhuanlan",$zhuanlan);
      		$this->assign("hangye",$hangye);
         	$this->assign("xinwen",$xinwen);
         	$this->assign("zixun2",$zixun2);
         	$this->assign("zixun3",$zixun3);
         	$this->assign("zixun",$zixun);
         	$this->assign("zhishi",$zhishi);

          $this->assign("count",$count);
          $this->assign("lanmu",$lanmu);
        	$this->display();
    }



    public function wzzxlist(){ 
            $id = $_REQUEST['lanmuid'];
            $banner = getWebPosAd(11);
            $lanmu = M("yunweb_lanmu")->where("pid=32")->order("listorder")->select();

            $infowhere = array("id" =>$id);
            $name = M("yunweb_lanmu")->where($infowhere)->getField("name");

            //根据栏目ID选择栏目内容
            $lanmuwhere = array("lanmuid" =>$id);
            $list = M("yunweb_article")->where($lanmuwhere)->order("id desc")->select();
            $count = M("yunweb_article")->where($lanmuwhere)->count();
            //热点阅读
            $redian_article = $this->get_rand_article(6);
            $ad = getWebPosAd(17);
            $zxpano  = getRecommendPano(0,9);

            $this->assign("zxpano",$zxpano); 
            $this->assign("redian_article",$redian_article); 
            $this->assign("count",$count); 
            $this->assign("list",$list);
            $this->assign("id",$id);
            $this->assign("name",$name);
            $this->assign("banner",$banner);
            $this->assign("lanmu",$lanmu); 
            $this->assign("ad",$ad); 
            $this->display();
      }

    public function content(){
            $banner = getWebPosAd(11);
           
          	$id = $_REQUEST['id'];
          	$where=array(
          			'id'=>$id,
          		);
          	$info = M('yunweb_article')->where($where)->find();

          	//推荐阅读  获取随机文章
      		  $tuijian_article = $this->get_rand_article(5);
      	  	//相关阅读
      	   	$xiangguan_article = $this->get_rand_article(5);
        		//底部左边
        		$left_article = $this->get_rand_article(4);
        		//热点阅读
        		$redian_article = $this->get_rand_article(6);

            $ad = getWebPosAd(17);
            $zxpano = getRecommendPano(0,9);
            $lanmu = M("yunweb_lanmu")->where("pid=32")->order("listorder")->select();
            
            if($info){
              $lanmuid=$info['lanmuid'];
            }else{
               $lanmuid="";
            }
            //根据栏目ID选择栏目名称
            if($lanmuid){
            	$lanmu_name = M("yunweb_lanmu")->where("id=$lanmuid")->getfield('name');
            	$count = M("yunweb_article")->where("lanmuid=$lanmuid")->count();
            }
            $this->assign("lanmu_name",$lanmu_name);
            $this->assign("lanmuid",$lanmuid);
            $this->assign("zxpano",$zxpano); 
        	$this->assign("tuijian_article",$tuijian_article);
        	$this->assign("xiangguan_article",$xiangguan_article);
        	$this->assign("left_article",$left_article);
        	$this->assign("redian_article",$redian_article);
            $this->assign("banner",$banner);  
            $this->assign("count",$count); 
            $this->assign("ad",$ad); 
            $this->assign("lanmu",$lanmu); 

          	$this->assign("info",$info);
          	$this->display();
    }

      public function danye(){
            $banner = getWebPosAd(11);
            $id = $_REQUEST['id'];
            
            $id = $_REQUEST['id'];
            $where=array(
                'id'=>$id,
              );
            $info = M('yunweb_artdanye')->where($where)->find();

            //推荐阅读  获取随机文章
            $tuijian_article = $this->get_rand_article(5);
            //相关阅读
            $xiangguan_article = $this->get_rand_article(5);
            //底部左边
            $left_article = $this->get_rand_article(4);
            //热点阅读
            $redian_article = $this->get_rand_article(6);

            $ad = getWebPosAd(17);
            $zxpano = getRecommendPano(0,9);
            $lanmu = M("yunweb_lanmu")->where("pid=32")->order("listorder")->select();
            
            if($info){
              $lanmuid=$info['lanmuid'];
            }else{
               $lanmuid="";
            }
            //根据栏目ID选择栏目名称
            if($lanmuid){
              $lanmu_name = M("yunweb_lanmu")->where("id=$lanmuid")->getfield('name');
              $count = M("yunweb_article")->where("lanmuid=$lanmuid")->count();
            }
            $this->assign("lanmu_name",$lanmu_name);
            $this->assign("lanmuid",$lanmuid);
            $this->assign("zxpano",$zxpano); 
             $this->assign("tuijian_article",$tuijian_article);
            $this->assign("xiangguan_article",$xiangguan_article);
            $this->assign("left_article",$left_article);
             $this->assign("redian_article",$redian_article);
            $this->assign("banner",$banner);  
            $this->assign("count",$count); 
            $this->assign("ad",$ad); 
            $this->assign("lanmu",$lanmu); 

            $this->assign("info",$info);
            $this->display();
    }

	public function get_rand_article($num){
      			$min=0;
      			$max=10;
      			$result =array();
      			for ($i=0; $i < $num; $i++) { 
      					$rand_num = rand($min,$max);
      					$result[$i]=$rand_num;	
      			}
      			$posid = implode(',', $result);
      			$article = M("yunweb_article")->where(" status='1' and  lanmuid !=0  ")->order("listorder ASC")->limit(0,$num)->select();
      			
      			if(!empty($article)){
      				foreach($article as $key=>$val)
      				{
      					$article[$key]['ariurl']= "index.php?s=/Website/content/id/".$val['id'];
                if(empty($val['thumb'])){
                  $article[$key]['thumb']="/Public/webstyle/images/srcdefault.jpg";
                }
      				}
      			}
      			return $article;
	}   

}