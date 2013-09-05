<?php

class NewsView extends AbstractView {

    static function printNews($news, $pagination) {        
        ob_start(); 
        if(isset($_SESSION["id"])) { ?>        
        <div>
            <p><a class="button" href="index.php?c=news&amp;a=edit">Ajouter</a></p>
        </div>
        <?php }
        if (empty($news)) {
            echo "Pas d'actus pour le moment. Revenez plus tard !";
        }    
        if($pagination["nbPages"] != 1) {
            echo AbstractView::pagination($pagination);   
        }        
        foreach ($news as $one) {
            $id = $one->getId();
            $nbComments = count($one->getComments());
            ?>                            
                <div class="item" id="item-<?php echo $id; ?>">
                    <h3><?php echo $one->getTitle(); ?></h3>                
                    <ul>
                        <li><a href="#news-<?php echo $id; ?>">Actu</a></li>
                        <li><a href="#comments-<?php echo $id; ?>">Commentaires (<?php echo $nbComments; ?>)</a></li>
                        <?php if(isset($_SESSION["id"])) { ?>
                        <li><a href="#edit-<?php echo $one->getId(); ?>">Modifier</a></li>
                        <li><a href="#delete-<?php echo $one->getId(); ?>">Supprimer</a></li>
                        <?php } ?>
                    </ul>
                    <div id="news-<?php echo $id; ?>">
                        <div class="date"><?php echo $one->getDate()->format("d/m/Y"); ?></div>                    
                        <div><?php echo $one->getDescription(); ?></div>   
                    </div>
                    <div id="comments-<?php echo $id; ?>">
                        <?php AbstractView::comments($one, "news"); ?>
                    </div>
                    <?php if(isset($_SESSION["id"])) { ?>
                    <div id="edit-<?php echo $id; ?>">
                        <?php echo self::edit($one); ?>
                    </div>
                    <div id="delete-<?php echo $id; ?>">
                        <?php echo self::confirmDelete($one); ?>
                    </div>
                    <?php } ?>
                </div>                            
            <?php            
        }
        if($pagination["nbPages"] != 1) {
            echo AbstractView::pagination($pagination);   
        }
        return ob_get_clean();
    }

    static function edit($news = false) {
        if($news) {
            $url = "&amp;id=".$news->getId();            
            $title = $news->getTitle();
            $description = $news->getDescription();           
        }
        else {
            $url = "";
            $title = "";
            $description = "";
        }
        ob_start();
        ?>    
        <div class="item odd">
            <form class="updateNews updateForm" method="post" action="index.php?c=news&amp;a=doEdit<?php echo $url; ?>">                
                <p><input type="text" name="title" class="title" value="<?php echo $title; ?>" /></p>
                <textarea class="ckeditor" name="description"><?php echo $description; ?></textarea>
                <div class="btnZone">                
                    <input type="submit" value="Enregistrer" class="button" />                                       
                </div>
            </form>
        </div>
<script>
//    $(function(){
//        var config = {};
//        var html = $("#editor").html();
//        editor = CKEDITOR.appendTo( 'editor', config, html );
//    });
</script>
        <?php
        return ob_get_clean();
    }

    static function confirmDelete($news) {
        ob_start();
        ?>        
        <h3>Supprimer cette actu ?</h3>
        <p>Cette action est irréversible.</p>
        <a class="button" href="index.php?c=news&amp;a=delete&amp;id=<?php echo $news->getId(); ?>">Confirmer</a>        
        <?php
        return ob_get_clean();
    }

}
?>
