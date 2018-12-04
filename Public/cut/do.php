<?php
@set_time_limit(0);
@ignore_user_abort(TRUE);
$do = "cut.bat pano_u.jpg pano_d.jpg pano_l.jpg pano_r.jpg pano_b.jpg pano_f.jpg";
exec($do, $output);
$filedir = $_GET["filedir"];
copy("./scene/preview.jpg", "../". $filedir . "/preview.jpg");
?>