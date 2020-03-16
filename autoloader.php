<?php
class autoloader {
    static public function loader($className) {
        $filename = str_replace("\\", '/', $className) . ".php";

        //$filename = "../" . $filename;

        $filename = "../../" .$filename; //change the directory as per requirement. ../ or ../../
        
        //echo $filename ."<br/>";
        if (file_exists($filename)) {
            include($filename);
            if (class_exists($className)) {
                // echo "<br/>TRUE<br/>";
                return TRUE;

            }
        }
        // echo "<br/>FALSE<br/>";
        return FALSE;

    }
}
spl_autoload_register('autoloader::loader');
?>
