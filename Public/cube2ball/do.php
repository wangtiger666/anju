<?php
$do = "cube2ball.bat pano_u.jpg pano_d.jpg pano_l.jpg pano_r.jpg pano_b.jpg pano_f.jpg";
if (exec($do, $output)) {
    echo "changeback();";
    exit();
}
?>
