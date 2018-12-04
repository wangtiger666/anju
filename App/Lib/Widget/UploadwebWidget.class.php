<?php

class UploadwebWidget extends Widget {

    public function render($data) {
        $appbase = "/".APP_NAME;
        $html =  "<script type=\"text/javascript\">var upload_root = \"{$base}\";</script>\r\n";
        $html .=  "<script type=\"text/javascript\" src=\"$appbase/Tools/Uploader/uploadtool.js\"></script>\r\n";
        $html .= "<link href=\"$appbase/Tools/Uploader/style.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n";
        return $html;
    }
}

?>
