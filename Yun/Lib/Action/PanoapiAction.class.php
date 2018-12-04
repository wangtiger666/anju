<?php
header("Access-Control-Allow-Origin：*");

class PanoapiAction extends Action {
		
    public function view()
	{
		$id = intval($_GET['id']);
		if(!$id) return ;
		$articlerow = M("yun720_article")->where(array("id"=>$id))->find();

			if(!empty($articlerow)){
				$hits = $articlerow['hits']+1;
				M("yun720_article")->where(array("id"=>$id))->save(array("id"=>$id,"userid"=>$articlerow['userid'],"hits"=>$hits));	
			}else{
				$hits = 1;
				M("yun720_article")->where(array("id"=>$id))->add(array("id"=>$id,"userid"=>$articlerow['userid'],"hits"=>$hits));	
			}
		

		echo 'success_jsonpCallback({"data":'.$hits.',"info":"","status":1})';  
		exit;
    }

}
?>