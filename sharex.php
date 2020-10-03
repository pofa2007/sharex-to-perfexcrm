<?php
/**
 * @version 20201003
 * @link https://github.com/Inteliboi/ShareX-Custom-Upload
 * @link https://suporte.com.pt
 */

/**
 * content for sharex.sxcu 
 * 
 {
  "Name": "to perfexcrm",
  "DestinationType": "ImageUploader, TextUploader, FileUploader",
  "RequestType": "POST",
  "RequestURL": "https://perfexcrm_url_here/sharex.php",
  "FileFormName": "sharex",
  "Arguments": {
    "secret": "putyourtoken_abcd"
  },
  "ResponseType": "Text",
  "URL": "https://perfexcrm_url_here/media/public/sharex/$json:url$"
 */

set_error_handler("myErrorHandler");

$tokens = array("putyourtoken_12345", "putyourtoken_abcd"); //Tokens go here
$sharexdir = "media/public/sharex"; //without last /
$lengthofstring = 5; //Length of file name

/**
 * DO NOT CHANGE FROM HERE
 */
$json = new stdClass();

//Random file name generation
function RandomString($length) {
    $keys = array_merge(range(0,9), range('a', 'z'));
    $key="";
    for($i=0; $i < $length; $i++) {
        $key .= $keys[mt_rand(0, count($keys) - 1)];
    }
    return date("Ymd.Hms") . "." . $key;
}
 
//Check for token
if(isset($_POST['secret'])){

    //Checks if token is valid
    if(in_array($_POST['secret'], $tokens)){
        //Prepares for upload
        $filename = RandomString($lengthofstring);
        $target_file = $_FILES["sharex"]["name"];
        $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
        
        //Accepts and moves to directory
        if ( file_exists($sharexdir) === false) mkdir($sharexdir);
        if (move_uploaded_file($_FILES["sharex"]["tmp_name"], $sharexdir."/" . $filename.'.'.$fileType)){
            //Sends info to client
            $json->status = "OK";
            $json->errormsg = "";
            $json->url = $filename . '.' . $fileType;
        } else {
            //Warning
           //echo 'File upload failed - CHMOD/Folder doesn\'t exist?';
           $ret["_POST"]=$_POST;
           $ret["filename"]=$filename;
           $ret["fileType"]=$fileType;
           $json->status = "ERROR";
           $json->errormsg = $ret;
           $json->url = "";
        }  
    }
    else {
        //Invalid key
        $json->status = "ERROR";
        $json->errormsg = "invalid token";
        $json->url = "";
    }
} else {
    //Warning if no uploaded data
    $json->status = "ERROR";
    $json->errormsg = " _POST is empty";
    $json->url = "";
}
//Sends json
echo(json_encode($json));


// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        //return false;

        $json->status = "ERROR";
        $json->errormsg = "$errno,$errfile,$errline,$errstr";
        $json->url = "";
        echo(json_encode($json));
        exit(1);
    }

    // $errstr may need to be escaped:
    $errstr = htmlspecialchars($errstr);

    switch ($errno) {
    case E_USER_ERROR:
        /*echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...<br />\n";*/
        $json->status = "ERROR";
        $json->errormsg = "E_USER_ERROR,$errno,$errfile,$errline,$errstr";
        $json->url = "";
        echo(json_encode($json));
        exit(1);

    case E_USER_WARNING:
        //echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        $json->status = "ERROR";
        $json->errormsg = "E_USER_WARNING,$errno,$errfile,$errline,$errstr";
        $json->url = "";
        echo(json_encode($json));
        exit(1);
        //break;

    case E_USER_NOTICE:
        //echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        $json->status = "ERROR";
        $json->errormsg = "E_USER_NOTICE,$errno,$errfile,$errline,$errstr";
        $json->url = "";
        echo(json_encode($json));
        exit(1);
        //break;

    default:
        //echo "Unknown error type: [$errno] $errstr<br />\n";
        $json->status = "ERROR";
        $json->errormsg = " $errno,$errfile,$errline,$errstr";
        $json->url = "";
        echo(json_encode($json));
        exit(1);
        //break;
    }

    /* Don't execute PHP internal error handler */
    //return true;
}
?>