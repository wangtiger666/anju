<?php

class UploaderWidget extends Widget {

    public function render($data) {
        $appbase = "/".APP_NAME;
        $html =  "<script type=\"text/javascript\">var upload_root = \"{$base}\";</script>\r\n";
        $html .=  "<script type=\"text/javascript\" src=\"$appbase/Tools/Uploader/uploader.js\"></script>\r\n";        
        $html .= "<link href=\"$appbase/Tools/Uploader/uploader.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n";
        return $html;
    }
}

?>
