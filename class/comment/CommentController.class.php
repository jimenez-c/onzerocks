<?php

class CommentController extends AbstractController {

    public function __construct($action) {
        if (!$action) {
            $action = "confirmDelete";
        }
        parent::__construct($action);
    }

    public function deleteAction() {
        if(isset($_GET["id"]) && preg_match("#[\d]*#", $_GET["id"])) {            
            if($this->commentManager->delete($_GET["id"])) {
                echo "1";
                exit;
            }            
        }        
        else echo "l'id n'est pas un nombre";
        exit;
    }    
}

?>
