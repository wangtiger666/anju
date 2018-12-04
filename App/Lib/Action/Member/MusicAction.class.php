<?php

class MusicAction extends MemberAction {

    public function index() {
        if (I("post.dopost") == "save") {
            $pano_id = I("post.pano_id");
            $music_id = I("post.music_id");
            $open_music = I("post.open_music");
            $open_musicbtn = I('post.open_musicbtn');
            $alpha = I('post.alpha');
            $hoveralpha = I('post.hoveralpha');
            $mpos_x = I('post.mpos_x');
            $mpos_y = I('post.mpos_y');
            $musicposto = I('post.musicposto');
            $musicpic = I('post.musicpic');
            $musichoverpic = I('post.musichoverpic');

            if ($music_id == 0) {
                $open_music = 0;
            }

            $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
            $this->assign('panorow', $panorow);

            $panodir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id;
            $musicdir = $panodir . "/music";
            if (!is_dir(APP_ROOT . $musicdir)) {
                createFolder(APP_ROOT . $musicdir);
            }

            if (is_file(APP_ROOT . $musicpic) && substr_count($musicpic, "station") > 0) {
                if (!substr_count($musicpic, "Public") > 0) {
                    @unlink(APP_ROOT . $panorow['musicpic']);
                }
                $newimg = $musicdir . "/" . basename($musicpic);
                rename(APP_ROOT . $musicpic, APP_ROOT . $newimg);
                $musicpic = $newimg;
            } elseif (substr_count($musicpic, "Public") > 0) {
                $newimg = $musicdir . "/" . basename($musicpic);
                copy(APP_ROOT . $musicpic, APP_ROOT . $newimg);
                $musicpic = $newimg;
            }

            if (is_file(APP_ROOT . $musicpic) && substr_count($musichoverpic, "station") > 0) {
                if (!substr_count($musichoverpic, "Public") > 0) {
                    @unlink(APP_ROOT . $panorow['musichoverpic']);
                }
                $newimg = $musicdir . "/" . basename($musichoverpic);
                rename(APP_ROOT . $musichoverpic, APP_ROOT . $newimg);
                $musichoverpic = $newimg;
            } elseif (substr_count($musichoverpic, "Public") > 0) {
                $newimg = $musicdir . "/" . basename($musichoverpic);
                copy(APP_ROOT . $musichoverpic, APP_ROOT . $newimg);
                $musichoverpic = $newimg;
            }

            $data = array(
                "open_music" => $open_music,
                "music_id" => $music_id,
                "open_musicbtn" => $open_musicbtn,
                "alpha" => $alpha,
                "hoveralpha" => $hoveralpha,
                "mpos_x" => $mpos_x,
                "mpos_y" => $mpos_y,
                "musicposto" => $musicposto,
                'musicpic' => $musicpic,
                'musichoverpic' => $musichoverpic
            );
            $where = array(
                "member_id" => $this->member_id,
                "id" => $pano_id
            );
            M("Pano")->where($where)->save($data);

            $this->success("保存成功！", U("index",array("pano_id"=>$pano_id)));
            exit();
        }

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $bgmusic = array();
        if ($panorow['music_id'] == 0) {
            $bgmusic['id'] = 0;
            $bgmusic['name'] = "暂无音乐";
            $bgmusic['file'] = "none";
        } else {
            $where = array(
                "id" => $panorow['music_id'],
                "member_id" => $this->member_id
            );
            $mrow = M("Music")->where($where)->find();
            $bgmusic['id'] = $panorow['music_id'];
            $bgmusic['name'] = $mrow['title'];
            $bgmusic['file'] = $mrow['file'];
        }
        $this->assign("bgmusic", $bgmusic);

        $this->display();
    }

    function musicbtnxml(){
        $this->display('./App/Tpl/Member/Music/musicbtnxml.xml', 'utf-8', 'text/xml');
    }

}

?>
