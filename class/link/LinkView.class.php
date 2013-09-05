<?php

class LinkView extends AbstractView {

    static function printLinks($links, $pagination) {        
        ob_start(); 
        if(isset($_SESSION["id"])) { ?>        
        <div>
            <p><a class="button" href="index.php?c=link&amp;a=edit">Ajouter</a></p>
        </div>
        <?php }
        if (empty($links)) {
            echo "Pas de liens pour le moment. Revenez plus tard !";
        }    
        if($pagination["nbPages"] != 1) {
            echo AbstractView::pagination($pagination);   
        }        
        foreach ($links as $one) {
            $id = $one->getId();            
            ?>                            
                <a href="<?php echo $one->getUrl(); ?>" rel="external"><?php echo $one->getUrl(); ?></a>
                <blockquote><?php echo $one->getDescription(); ?></blockquote>
            <?php
            if(isset($_SESSION["id"])) { ?>
                <p>
                    <a class="button" href="index.php?c=link&amp;a=edit&amp;id=<?php echo $id; ?>">Modifier</a>
                    <a class="button" href="index.php?c=link&amp;a=confirmDelete&amp;id=<?php echo $id; ?>">Supprimer</a>
                </p>
            <?php
            }
        }
        if($pagination["nbPages"] != 1) {
            echo AbstractView::pagination($pagination);
        }
        return ob_get_clean();
    }

    static function edit($link = false) {
        if($link) {
            $id = "&amp;id=".$link->getId();            
            $url = $link->getUrl();
            $description = $link->getDescription();           
        }
        else {
            $id = "";
            $url = "";
            $description = "";
        }
        ob_start();
        ?>    
        <div class="item odd">
            <form class="updateLink updateForm" method="post" action="index.php?c=link&amp;a=doEdit<?php echo $id; ?>">                
                <p>
                    <label for="url">Adresse URL du site</label>
                    <input type="text" name="url" class="url" id="url" value="<?php echo $url; ?>" />
                </p>
                <p>
                    <label for="description">Description (sous le lien)</label>
                </p>
                <textarea class="ckeditor" name="description" id="description"><?php echo $description; ?></textarea>
                <div class="btnZone">
                    <input type="submit" value="Enregistrer" class="button" />                                       
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    static function confirmDelete($link) {
        ob_start();
        ?>        
        <h3>Supprimer ce lien ?</h3>
        <p>Cette action est irréversible.</p>
        <a class="button" href="index.php?c=link&amp;a=delete&amp;id=<?php echo $link->getId(); ?>">Confirmer</a>        
        <?php
        return ob_get_clean();
    }

}
?>
