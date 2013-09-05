<?php

class LinkController extends AbstractController {
    public function __construct($action) {        
        if(!$action) {
            $action = "print";
        }        
        parent::__construct($action);
    }
    
    public function printAction() {        
        $pagination = $this->getPagination($this->linkManager->getLinkQuery(), 10);
        $pagination["url"] = "index.php?c=link&amp;a=print";
        $query = $pagination["query"];
        $links = $query->getObjects("Link");
        
        return array(
            "title" => "Nos amis",
            "content" => LinkView::printLinks($links, $pagination)
        );
    }
    
    public function printOneAction() {
        $link = $this->linkManager->getOne($_GET["id"]);
        
        return array(
            "title" => "Voir un lien",
            "content" => LinkView::printLinks(array($link), false)
        );
    }
       
    public function editAction() {
        if(isset($_GET["id"])) {
            $link = $this->linkManager->getOne($_GET["id"]);           
        }
        else {
            $link = false;
        }
        $content = LinkView::edit($link);
        $title = "Éditer un lien";
        
        return array(
            "title" => $title,
            "content" => $content
        );
    }
    
    public function doEditAction() {
        $link = new Link($_REQUEST);              
        if($this->linkManager->edit($link)) {
            header("Location: index.php?c=link&a=print");
        }
    }
    
    public function confirmDeleteAction() {
        if(isset($_GET["id"]) && preg_match("#[\d]*#", $_GET["id"])) {
            $link = $this->linkManager->getOne($_GET["id"]);
            $title = "Confirmer la suppression d'un lien";
            $content = LinkView::confirmDelete($link);
            return array(
                "title" => $title,
                "content" => $content
            );
        }        
        else throw new Exception("l'id n'est pas un nombre");
    }
    
    public function deleteAction() {
        if(isset($_GET["id"]) && preg_match("#[\d]*#", $_GET["id"])) {
            $link = $this->linkManager->getOne($_GET["id"]);            
            if($this->linkManager->delete($_GET["id"])) {
                header("Location: index.php?c=link&a=print");                
            }            
        }        
        else throw new Exception("l'id n'est pas un nombre");
    }
}

?>
