<?php

/* Décommenter et mettre son adresse IP pour mettre le site en maintenance */
/*
$ip = "88.138.248.198";
//$ip = "127.0.0.1";
if($_SERVER["REMOTE_ADDR"] != $ip) {
    include("ui/pages/down.php");  
    exit;
}
*/

session_start();
require_once("config/configMysql.php");
require_once('captcha/recaptchalib.php');
define("PUBLIC_KEY", "6LdySeASAAAAAI6pF5LMxHwMS8l_zxuQdYWm9DDa");
define("PRIVATE_KEY", "6LdySeASAAAAAJujDmYvh3AoNXE9EXax-ohRBQpt");

spl_autoload_register(function($class){
    // on crée un tableau avec chaque mot
    $chunks = preg_split('/(?=[A-Z])/', $class, -1, PREG_SPLIT_NO_EMPTY);
    $dir = strtolower($chunks[0]);    
    if(is_file("class/".$dir."/".$class.".class.php")) {        
        require_once("class/".$dir."/".$class.".class.php");
    }
    else echo "class/".$dir."/".$class.".class.php";
    
});

set_exception_handler("exception");
set_error_handler("error");

function exception($exception) {    
    $data["content"] = PublicView::printException($exception);
    $data["title"] = $exception->getMessage();
    $data["controller"] = $data["action"] = "";  
    $data["lastEmissions"] = false;
    include("ui/pages/page.php");  
    exit;
}

function error($no, $str, $file, $line, $context) {    
    $data["content"] = PublicView::printError($no, $str, $file, $line, $context);
    $data["title"] = $str;
    $data["controller"] = $data["action"] = "";
    $data["lastEmissions"] = false;
    include("ui/pages/page.php");    
    exit;
}
// on gère le contrôleur
if (isset($_GET["c"])) {	
    $controller = ucfirst($_GET["c"]);
    if(!class_exists($controller."Controller")) {
    	header("Location: index.php?c=public&a=notFound");
    }
} else {
    $controller = "News";
}

// on gère l'action
if (isset($_GET["a"])) {
    $action = $_GET["a"];
} else {
    $action = false;
}
$controller .= "Controller";
new $controller($action);
?>
