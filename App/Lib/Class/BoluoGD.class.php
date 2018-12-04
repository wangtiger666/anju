<?php

class BoluoGD {

    var $imgself = array();
    var $result = array();

    function __construct($imgurl="", $dex="me") {
        $this->loadImg($imgurl, $dex);
    }

    function loadImg($imgurl="", $dex="me") {
        if ($imgurl != "") {
            $this->imgself[$dex] = $imgurl;
        }
    }

    function readImg($key="", $dex="me") {
        $img = $this->imgself[$dex];
        $data = getimagesize($img);
        $mtp = array(
            1 => "gif",
            2 => "jpg",
            3 => "png"
        );
        switch ($key) {
            case "size":
                return $data["bits"];
                exit();
                break;
            case "width":
                return $data[0];
                exit();
                break;
            case "height":
                return $data[1];
                exit();
                break;
            case "type":
                return $mtp[$data[2]];
                exit();
                break;
            default:
                $info = array(
                    "size" => $data["bits"],
                    "width" => $data[0],
                    "height" => $data[1],
                    "type" => $mtp[$data[2]]
                );
                return $info;
                break;
        }
    }

    function creatImg($width, $height, $color="", $alpha=1, $dex="me") {
        $this->result[$dex] = imagecreatetruecolor($width, $height);
        if ($color != "") {
            $r = intval("0x" . substr($color, 1, 2), 16);
            $g = intval("0x" . substr($color, 3, 2), 16);
            $b = intval("0x" . substr($color, 5, 2), 16);
            $alp = (1 - $alpha) * 127;
            $colordata = imagecolorallocatealpha($this->result[$dex], $r, $g, $b, $alp);
            imagefill($this->result[$dex], 0, 0, $colordata);
        } else {
            imagealphablending($this->result[$dex], true);
        }
    }

    function creatNoneImg($width, $height, $dex="me") {
        $this->result[$dex] = imagecreatetruecolor($width, $height);
        $alp = (1 - $alpha) * 127;
        $colordata = imagecolorallocatealpha($this->result[$dex], 255, 255, 255, 127);
        imagefill($this->result[$dex], 0, 0, $colordata);
        imagealphablending($this->result[$dex], true);
        imagesavealpha($this->result[$dex], true);
    }

    function copyImg($filename, $self=array(0, 0, 0, 0), $target=array(0, 0, 0, 0), $dex="me") {
        $im = $this->creatFrom($filename);
        $fileData = getimagesize($filename);
        if ($self[2] == 0) {
            $self[2] = $fileData[0];
        }
        if ($self[3] == 0) {
            $self[3] = $fileData[1];
        }
        if ($target[2] == 0) {
            $target[2] = $fileData[0];
        }
        if ($target[3] == 0) {
            $target[3] = $fileData[1];
        }

        imagealphablending($im, true);
        imagesavealpha($im, true);
        imagecopyresized($this->result[$dex], $im, $target[0], $target[1], $self[0], $self[1], $target[2], $target[3], $self[2], $self[3]);
    }

    function polarImg($width=500, $dex="me") {
        $this->creatNoneImg($width, $width, $dex);
        $oldim = $this->creatFrom($this->imgself[$dex]);
        $oldwidth = $this->readImg("width", $dex);
        $oldheight = $this->readImg("height", $dex);

        $r = $width * 0.5;

        for ($y = 0; $y < $width; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $px = $x / $width;
                $d = ($x - $r + 0.5) * ($x - $r + 0.5) + ($y - $r + 0.5) * ($y - $r + 0.5);
                $d = sqrt($d);
                $dx = abs($d - $r);
                if ($d < $r) {
                    $BY = floor($oldheight * (1 - $d / $r));
                    $vx = $x + 0.5 - $r;
                    $vy = ($y + 0.5 - $r);
                    $vl = sqrt($vx * $vx + $vy * $vy);
                    $v = (acos($vx / $vl));
                    if ($vy > 0) {
                        $v = $v + Pi();
                    }
                    $v = $v * 180 / Pi();
                    $v = $v - 90;
                    if ($v < 0) {
                        $v = $v + 360;
                    }
                    $yp = $v / 360;
                    $BX = floor($oldwidth * $yp);
                    if ($vy > 0) {
                        $BX = $oldwidth - $BX;
                    }
                    $color = imagecolorat($oldim, $BX, $BY);
                    $red = ( $color >> 16 ) & 0xFF;
                    $green = ( $color >> 8 ) & 0xFF;
                    $blue = $color & 0xFF;
                    imagesetpixel($this->result[$dex], $x, $y, $color);
                }
            }
        }
    }

    function creatFrom($filename) {
        $fileData = getimagesize($filename);
        if ($fileData[2] == 1) {
            return imagecreatefromgif($filename);
        } else if ($fileData[2] == 2) {
            return imagecreatefromjpeg($filename);
        } else {
            return imagecreatefrompng($filename);
        }
    }

    function display($type="png", $dex="me") {
        switch ($type) {
            case "png":
                header('Content-Type: image/png');
                imagepng($this->result[$dex]);
                break;
            case "gif":
                header('Content-Type: image/gif');
                imagegif($this->result[$dex]);
                break;
            case "jpg":
                header('Content-Type: image/jpeg');
                imagejpeg($this->result[$dex]);
                break;
            default:
                break;
        }
    }

    function save($url, $type="png", $dex="me") {
        switch ($type) {
            case "png":
                imagepng($this->result[$dex], $url);
                break;
            case "gif":
                imagegif($this->result[$dex], $url);
                break;
            case "jpg":
                imagejpeg($this->result[$dex], $url);
                break;
            default:
                break;
        }
    }

}

?>
