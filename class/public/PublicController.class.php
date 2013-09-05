<?php

class PublicController extends AbstractController {
    public function __construct($action) {
        if(!$action) {
            $action = "direct";
        }
        parent::__construct($action);
    }   
    
    public function liensAction() {
        return array(
            "title" => "Liens",
            "content" => file_get_contents("static/liens.html")
        );
    }
    
    public function directAction() {  
        $today = new DateTime();  
        $next = new DateTime(file_get_contents("store/next.txt"));                        
        return array(
            "title" => "Écouter le direct",
            "content" => PublicView::printDirect($this->dateToJS($today), $this->dateToJS($next), $next)
        );        
    }
    public function calculateNextAction() {
        $next = new DateTime();
        $next->setTimestamp(strtotime("next wednesday"));
        $next->setTime("19", "30","00");
        
        $file = fopen("store/next.txt", "w");
        fwrite($file, $next->format("Y-m-d H:i"));
        fclose($file);
        echo $next->getTimestamp() * 1000;
        exit();
    }
    public function contactAction() {
        return array(
            "title" => "contact",
            "content" => file_get_contents("static/contact.html")
        );
    }
    public function loginAction() {          
        return array(
            "title" => "Connexion",
            "content" => file_get_contents("static/login.html")
        );        
    }
    public function logoutAction() {
        session_destroy();
        header("Location: index.php");
    }
    public function doLoginAction() {
        $url = $this->userManager->login($_POST["login"], $_POST["pwd"]);
        header("Location: ".$url);
    }
    
    public function addCommentAction() {
        // catpcha       
        $resp = recaptcha_check_answer (
            PRIVATE_KEY,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]
        );

        if (!$resp->is_valid) {
            // What happens when the CAPTCHA was entered incorrectly
            $_SESSION["error"] = "Le code de sécurité est incorrect."; 
            header("Location: " . $_SERVER["HTTP_REFERER"] . "#comments-" . $_GET["id"]);
            exit();
        }
		
		// on vérifie que cet enfoiré de robot ne nous embête pas        
		if(preg_match("#paris|escort|girl|girls#", $_POST["pseudo"])) {            
			$_SESSION["error"] = "Sale robot, tu m'auras pas héhé...";
			header("Location: " . $_SERVER["HTTP_REFERER"] . "#comments-" . $_GET["id"]);
            exit();
		}                

        if(isset($_POST["remember"])) {
            $_SESSION["pseudo"] = $_POST["pseudo"];
        }
        else {
            unset($_SESSION["pseudo"]);
        }
        $comment = new Comment($_POST);         
        $id = $this->commentManager->addComment($comment);
        $comment->setId($id);
        
        if(!isset($_GET["rel"]) || ($_GET["rel"] != "news" && $_GET["rel"] != "emission")) throw new Exception("C'est un commentaire de quoi ?");
        if(!isset($_GET["id"])) throw new Exception("Aucun id transmis");
        if($_GET["rel"] == "news") {
            $news = $this->newsManager->getOne($_GET["id"]);
            $this->commentManager->addNewsComment($comment, $news);
            $this->mailCommentNews($news, $comment);
            $location = "index.php?c=news&a=print#comments-" . $news->getId();
        }
        elseif($_GET["rel"] == "emission") {
            $emission = $this->emissionManager->getOne($_GET["id"]);
            $this->commentManager->addEmissionComment($comment, $emission);
            $this->mailCommentEmission($emission, $comment);
            $location = "index.php?c=emission&a=printEmissions&type=".$emission->getType() . "#comments-" . $emission->getId();
        }
                
        header("Location: ".$location);
    }
    
    // augmente le nombre d'écoutes d'une émission (ajax)
    public function playsAction() {
        if(isset($_POST["id"]) && !isset($_SESSION["id"])) {
            $this->emissionManager->addPlay($_POST["id"]);
            echo "1";
        }  
        else echo "0";
        exit();
    }
    
    // erreur 404 
    public function notFoundAction() {
        return array(
            "title" => "Page non trouvée",
            "content" => file_get_contents("static/404.html")
        );
    }
  
}

?>
