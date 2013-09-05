<?php

class NewsController extends AbstractController {
    public function __construct($action) {        
        if(!$action) {
            $action = "print";
        }        
        parent::__construct($action);
    }
    
    public function printAction() {        
        $pagination = $this->getPagination($this->newsManager->getNewsQuery(), 6);
        $pagination["url"] = "index.php?c=news&amp;a=print";
        $query = $pagination["query"];
        $news = $query->getObjects("News");
        foreach($news as $one) {            
            $one->setComments($this->commentManager->getNewsComments($one));            
        }        
        return array(
            "title" => "Les actus",
            "content" => NewsView::printNews($news, $pagination)
        );
    }
    
    public function printOneAction() {
        $news = $this->newsManager->getOne($_GET["id"]);
        $news->setComments($this->commentManager->getNewsComments($news));
        return array(
            "title" => $news->getTitle(),
            "content" => NewsView::printNews(array($news), false)
        );
    }
       
    public function editAction() {
        if(isset($_GET["id"])) {            
            $news = $this->newsManager->getOne($_GET["id"]);           
        }
        else {            
            $news = false;
        }        
        $content = NewsView::edit($news);
        $title = "Éditer une actu";
        
        return array(
            "title" => $title,
            "content" => $content
        );
    }
    
    public function doEditAction() {
        $news = new News($_REQUEST);        
        if($this->newsManager->edit($news)) {
            header("Location: index.php?c=news&a=print");
        }
    }
    
    public function confirmDeleteAction() {
        if(isset($_GET["id"]) && preg_match("#[\d]*#", $_GET["id"])) {
            $news = $this->newsManager->getOne($_GET["id"]);
            $title = "Confirmer la suppression d'une actu";
            $content = NewsView::confirmDelete($news);
            return array(
                "title" => $title,
                "content" => $content
            );
        }        
        else throw new Exception("l'id n'est pas un nombre");
    }
    
    public function deleteAction() {
        if(isset($_GET["id"]) && preg_match("#[\d]*#", $_GET["id"])) {
            $news = $this->newsManager->getOne($_GET["id"]);
            $news->setComments($this->commentManager->getNewsComments($news));            
            foreach($news->getComments() as $comment) {
                $this->commentManager->delete($comment->getId());
            }
            if($this->newsManager->delete($_GET["id"])) {
                header("Location: index.php?c=news&a=print");                
            }            
        }        
        else throw new Exception("l'id n'est pas un nombre");
    }
}

?>
