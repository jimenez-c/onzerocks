<?php

abstract class AbstractController {    
    /**
     * @var EmissionManager
     */
    protected $emissionManager;
    /**     
     * @var NewsManager 
     */
    protected $newsManager;
    /**     
     * @var UserManager 
     */
    protected $userManager; 
    /**     
     * @var CommentManager 
     */
    protected $commentManager;
    /**
     * @var LinkManager
     */
    protected $LinkManager;

    protected $action;
    
    public function __construct($action) {
        // on vérifie que la personne a le droit de consulter la page
        $publicActions = array(
            "Emission" => array(
                "printEmissions",
                "printEmission",
                "printPlayer"             
            ),
            "News" => array(
                "print",
                "printOne"
            ),
            "Link" => array(
                "print",
                "printOne"
            )
        );
        
        if(!isset($_SESSION["id"]) && get_class($this) != "PublicController") {
            $authorized = false;            
            foreach($publicActions as $controller => $actions) {
                foreach($actions as $one) {
                    if($controller."Controller" == get_class($this) && $action == $one) {
                        $authorized = true;
                    }
                }                                
            }            
            if(!$authorized) {
                throw new Exception("Accès refusé");
            }
        }
                
        // appel des managers       
        $database = Database::getInstance()->getConnexion();
        $this->userManager = UserManager::getInstance($database);
        $this->emissionManager = EmissionManager::getInstance($database); 
        $this->newsManager = NewsManager::getInstance($database);
        $this->commentManager= CommentManager::getInstance($database);
        $this->linkManager = LinkManager::getInstance($database);
        
        // on vérifie que le contrôleur a bien l'action demandée
        if(!method_exists($this, $action."Action")) {
        	header("Location: index.php?c=public&a=notFound");
        }
        
        // gestion de l'action
        $this->action = $action;
        $str = $action."Action";        
        $data = $this->$str();
        // on rajoute des infos pour la vue        
        $data["controller"] = get_class($this);
        $data["action"] = $action;            
        $data["user"] = false;
        if(isset($_SESSION["id"])) {
            $data["user"] = $this->userManager->getOne($_SESSION["id"]);            
        }        
        include("ui/pages/page.php");       
    }  
    
    protected function dateToJS(DateTime $date) {
        $year = $date->format("Y");
        $month = $date->format("m");
        $month -= 1;
        $day = $date->format("d");
        $hour = $date->format("H");        
        $min = $date->format("i");
        $sec = $date->format("s");
        return $year.", ".$month.", ".$day.", ".$hour.", ".$min.", ".$sec;
    }
    
    public function getPagination(QuerySelect $query, $perPage) {        
        $cPage = (isset($_GET["page"])) ? $_GET["page"] : 1;
        $count = clone($query);
        $count->setSelector("count(*) as nbItems");
        $nbItems = $count->getResults();
        $nbItems = $nbItems[0]["nbItems"];
                
        $nbPages = ceil($nbItems / $perPage);
        
        $query->setLimit(array(
            ($cPage-1)*$perPage,
            $perPage
        ));
        
        return array(
            "query" => $query,
            "cPage" => $cPage,
            "nbItems" => $nbItems,
            "nbPages" => $nbPages
        );         
    
    }
    
    public function mailCommentNews($news, $comment) {
        $headers = "From: onzerocks.net <manu@onzerocks.net> \n"; 				
        $headers .= "Content-type: text/html; charset=UTF-8 \n"; 
        $headers .= "Content-Transfer-Encoding: 8bit"; 

        $message = '
        <html>
                <head>
                        <title>Nouveau commentaire</title>
                </head>
                <body>
                        <p><a href="http://www.onzerocks.net/index.php?c=news&amp;a=printOne&amp;id='.$news->getId().'">Nouveau commentaire sur ONZEROCKS</a></p>                        
                        <p>A propos de l\'actu : <strong>'.$news->getTitle().'</strong></p>
                        <p>Pseudo : <strong>'.$comment->getPseudo().'</strong></p>
                        <blockquote>'.$comment->getDescription().'</blockquote>
                </body>
        </html>';

        mail("manu@onzerocks.net", "ONZEROCKS - Nouveau Commentaire", $message, $headers);
        mail("webmaster@onzerocks.net", "ONZEROCKS - Nouveau Commentaire", $message, $headers);
    }
    
    public function mailCommentEmission($emission, $comment) {
        $headers = "From: onzerocks.net <manu@onzerocks.net> \n"; 				
        $headers .= "Content-type: text/html; charset=UTF-8 \n"; 
        $headers .= "Content-Transfer-Encoding: 8bit"; 

        $message = '
        <html>
                <head>
                        <title>Nouveau commentaire</title>
                </head>
                <body>
                        <p><a href="http://www.onzerocks.net/index.php?c=emission&amp;a=printEmission&amp;id='.$emission->getId().'">Nouveau commentaire sur ONZEROCKS</a></p>                        
                        <p>A propos de l\'émission : <strong>'.$emission->getTitle().'</strong></p>
                        <p>Pseudo : <strong>'.$comment->getPseudo().'</strong></p>
                        <blockquote>'.$comment->getDescription().'</blockquote>                        
                </body>
        </html>';

        mail("manu@onzerocks.net", "ONZEROCKS - Nouveau Commentaire", $message, $headers);
        mail("webmaster@onzerocks.net", "ONZEROCKS - Nouveau Commentaire", $message, $headers);
    }
}

?>
