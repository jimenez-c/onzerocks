<?php

abstract class AbstractView {
     static protected function shorten($str) {
         $limit = 40;
        if(strlen($str) > $limit) {
            return substr($str, 0, $limit)." [...]";
        }
        else return $str;
    }
    
    static function pagination($pagination) {
        $return = '<div id="pagination">';
        for($i=1; $i <= $pagination["nbPages"]; $i++) {	
            $return .= '<a class="button '.self::is_active($i).'" href="'.$pagination["url"].'&amp;page='.$i.'">'.$i.'</a>';
        }
        $return .= '</div>';
        return $return;
    }
    
    static function is_active($nb) {
	if(!isset($_GET["page"]) && $nb == 1) {
		return 'active';
	}
	if(isset($_GET["page"]) && $_GET["page"] == $nb) {
		return 'active';
	}	
    }
    
    static function comments($object, $rel) {  
        $pseudo = (isset($_SESSION["pseudo"])) ? $_SESSION["pseudo"] : "";        
        ?>                                            
            <form class="add-comment" method="post" action="index.php?c=public&amp;a=addComment&amp;rel=<?php echo $rel; ?>&amp;id=<?php echo $object->getId(); ?>">
                <p>
                    <label for="pseudo">Pseudo : (facultatif)</label>
                    <input type="text" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>" />
                    <input type="checkbox" checked="checked" id="remember" name="remember" />
                    <label for="remember">Retenir ce pseudo</label>
                </p>
                <textarea name="description"></textarea>
                <?php echo recaptcha_get_html(PUBLIC_KEY); ?>
                <?php if(isset($_SESSION["error"])) { ?>
                <div class="error" id="captcha-error"><?php echo $_SESSION["error"]; ?></div>
                <?php } ?>
                <p><input type="submit" class="button" value="Poster le commentaire" /></p>
            </form>      
            <?php if($object->hasComment()) { ?>
                <?php foreach($object->getComments() as $comment) { ?>                    
                <blockquote class="comment">
                    <?php if(isset($_SESSION["id"])) { ?>
                    <div class="delComment">
                        <a class="delBtn" href="#">Supprimer</a>
                        <a class="confirm" style="display:none" href="index.php?c=comment&amp;a=delete&amp;id=<?php echo $comment->getId(); ?>">Confirmer</a>
                        <a class="cancel" style="display:none" href="#">Annuler</a>
                    </div>
                    <?php } ?>
                    <div class="by">Par <?php echo $comment->getPseudo(); ?>, le <?php echo $comment->getDate()->format("d/m/Y à H:i"); ?></div>
                    <?php echo nl2br($comment->getDescription()); ?>
                </blockquote>
                <?php } ?>                    
            <?php } else { ?>
                <p>Pas de commentaire pour le moment.</p>
            <?php } ?>                                             
        <?php
        unset($_SESSION["error"]);
    }
}

?>
