<?php

class SplashAction extends MemberAction
{

    public function index( )
    {
        if ( i( "post.dopost" ) == "save" )
        {
            $pano_id = i( "post.pano_id" );
            $open_splash = i( "post.open_splash" );
            $splashpic = i( "post.splashpic" );
            $splashpicsj = i( "post.splashpicsj" );
            $panorow = d( "Pano" )->GetOne( $pano_id, $this->member_id );

            $this->assign( "panorow", $panorow );
            $panodir = "/uploads/user/".substr( md5( $this->member_id ), 5, 15 )."/".$pano_id;
            $splashdir = $panodir."/splash";
            if ( !is_dir( APP_ROOT.$splashdir ) )
            {
                createfolder( APP_ROOT.$splashdir );
            }
            if ( is_file( APP_ROOT.$splashpic ) && 0 < substr_count( $splashpic, "station" ) )
            {
                if ( 0 < !substr_count( $splashpic, "Public" ) )
                {
                    @unlink( APP_ROOT.$panorow['splashpic'] );
                }
                $newimg = $splashdir."/".basename( $splashpic );
                rename( APP_ROOT.$splashpic, APP_ROOT.$newimg );
                $splashpic = $newimg;
            }
            else if ( 0 < substr_count( $splashpic, "Public" ) )
            {
                $newimg = $splashdir."/".basename( $splashpic );
                copy( APP_ROOT.$splashpic, APP_ROOT.$newimg );
                $splashpic = $newimg;
            }
            if ( is_file( APP_ROOT.$splashpic ) && 0 < substr_count( $splashpicsj, "station" ) )
            {
                if ( 0 < !substr_count( $splashpicsj, "Public" ) )
                {
                    @unlink( APP_ROOT.$panorow['splashpicsj'] );
                }
                $newimg = $splashdir."/".basename( $splashpicsj );
                rename( APP_ROOT.$splashpicsj, APP_ROOT.$newimg );
                $splashpicsj = $newimg;
            }
            else if ( 0 < substr_count( $splashpicsj, "Public" ) )
            {
                $newimg = $splashdir."/".basename( $splashpicsj );
                copy( APP_ROOT.$splashpicsj, APP_ROOT.$newimg );
                $splashpicsj = $newimg;
            }
            $data = array(
                "open_splash" => $open_splash,
                "splash_id" => $splash_id,
                "open_splashbtn" => $open_splashbtn,
                "splashpic" => $splashpic,
                "splashpicsj" => $splashpicsj
            );
            $where = array(
                "member_id" => $this->member_id,
                "id" => $pano_id
            );
            m( "Pano" )->where( $where )->save( $data );
			//echo M("Pano")->getLastSql();
			//exit();
			
            $this->success( "保存成功！", u( "index", array(
                "pano_id" => $pano_id
            ) ) );
            exit( );
        }
        $pano_id = i( "get.pano_id" );
        $this->assign( "pano_id", $pano_id );
        $panorow = d( "Pano" )->GetOne( $pano_id, $this->member_id );
        $this->assign( "panorow", $panorow );
        $this->display( );
    }

}

?>
