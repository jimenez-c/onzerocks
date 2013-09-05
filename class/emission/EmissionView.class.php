<?php

class EmissionView extends AbstractView {
    
    static function printEmissions($emissions, $pagination, $type) {
        ob_start(); 
        if(isset($_SESSION["id"])) { ?>
            <div id="addEmission">
                <p><a href="<?php echo $type; ?>"class="button" data-open="no">Ajouter</a></p>
                <div id="filelist" style="display:none"></div>
            </div> 
        <?php }         
        if (empty($emissions) || !isset($emissions)) {
            echo "Pas d'émissions pour le moment. Revenez plus tard !";
        } 
        else {
            $page = (isset($_GET["page"])) ? $_GET["page"] : "1";
            if($emissions instanceof Emission) {
                $emissions = array($emissions);
            }
            if($pagination["nbPages"] != 1) {
                echo AbstractView::pagination($pagination);   
            }              
            foreach ($emissions as $emission) {            
                $id = $emission->getId();    
                $nbComments = count($emission->getComments());
                ?>                 
                <div class="item" id="item-<?php echo $id; ?>">
                    <h3><?php echo $emission->getTitle(); ?></h3>
                    <ul>
                        <li><a href="#infos-<?php echo $id; ?>">Infos</a></li>
                        <li data-loaded="false"><a href="index.php?c=emission&a=printPlayer&id=<?php echo $id; ?>">Écouter</a></li>
                        <li><a href="#comments-<?php echo $id; ?>">Commentaires (<span><?php echo $nbComments; ?></span>)</a></li>
                        <?php if($type != "live") { ?>
                        <li><a href="#playlist-<?php echo $id; ?>">Playlist</a></li>
                        <?php } ?>
                        <?php if(isset($_SESSION["id"])) { ?>
                        <li><a href="#edit-<?php echo $id; ?>">Modifier</a></li>
                        <li><a href="#delete-<?php echo $id; ?>">Supprimer</a></li>
                        <?php } ?>
                    </ul>
                    
                <div id="infos-<?php echo $id; ?>">
                    <div class="infos-right">
                        <div><?php echo $emission->getDate()->format("d/m/Y"); ?></div>
                        <div class="plays" data-played="false"><?php echo $emission->getPlays(); ?></div>                    
                    </div>                                        
                    <?php echo $emission->getDescription(); ?>                       
                </div>                    
                    
                <div id="comments-<?php echo $id; ?>">
                    <?php                    
                    AbstractView::comments($emission, "emission");   
                    ?>
                </div>
                                        
                <?php if($type != "live") { ?>
                <div id="playlist-<?php echo $id; ?>">               
                    <?php 
                    if($emission->getPlaylist()) {
                        echo nl2br($emission->getPlaylist());
                    }
                    else {
                        echo "Manu n'a pas encore mis la playlist de cette émission. Ouh le vilain.";
                    }
                    ?>
                </div>
                <?php } ?>
                    
                <?php if(isset($_SESSION["id"])) { ?>
                <div id="edit-<?php echo $id; ?>">
                    <?php echo self::edit($type, $emission); ?>
                </div>
                <div id="delete-<?php echo $id; ?>">
                    <?php echo self::confirmDelete($emission); ?>
                </div>
                <?php } ?>
                                                    
            </div>
            <?php              
            
            } // end foreach	  
            if($pagination["nbPages"] != 1) {
                echo AbstractView::pagination($pagination);   
            }                
        } 
        
        return ob_get_clean();
    }
    
    static function printPlayer($emission) {
        $id = $emission->getId();
        ob_start(); ?>            
        <!-- Div that contains player. -->
        <div id="player-<?php echo $id; ?>"></div>

        <!-- Script that embeds player. -->
        <script type="text/javascript">                   
            jwplayer("player-<?php echo $id; ?>").setup({
                width:'500',
                height:'24',
                controlbar: 'bottom',
                file: "store/audio/<?php echo $emission->getType(). "/" . $emission->getFilename(); ?>"
            });
            jwplayer("player-<?php echo $id; ?>").onBeforePlay( function(event){
                var plays = $("#infos-<?php echo $id; ?> .plays");                        
                var nb = parseInt(plays.text());  
                
                if(plays.attr("data-played") === "false") {
                    plays.attr("data-played", "true");
                    $.ajax({
                        url: "index.php?c=public&a=plays",
                        data: "id=<?php echo $id; ?>",
                        type: "POST",
                        success: function(success) {
                            if(success === "1") {
                                plays.text(nb+1);
                            }                                    
                        }
                    });
                }
                else {
                    console.log('pas de requête, on veut juste continuer à écouter tranquille son morceau...');   
                }
            });
        </script>                             
        <?php
        return ob_get_clean();
    }

    static function edit($type, $emission = false) {                
        if($emission) {
            $id = $emission->getId();
            $url = "&id=".$id;
            $title = $emission->getTitle();
            $description = $emission->getDescription();
            $date = $emission->getDate()->format("d/m/Y");
            $playlist = $emission->getPlaylist();
        }
        else {
            $url = "";
            $title = "";
            $description = "";
            $date = "";                        
            $playlist = "";
        }        
        ob_start();                
        ?>        
            <h3>Éditer une émission</h3>
            
            <form class="updateForm updateEmission" method="post" action="index.php?c=emission&a=doEditEmission<?php echo $url; ?>">                
                <input type="hidden" name="type" value="<?php echo $type ?>" />
                <?php if(isset($_GET["file"])) { ?>
                <div>
                    <input type="hidden" name="filename" value="<?php echo $_GET["file"]; ?>" />
                    <strong><?php echo $_GET["file"]; ?></strong>
                </div>
                <?php } ?>
                <p><label for="title">Titre de l'émission</label></p>
                <p><input type="text" name="title" class="title" id="title" placeholder="Indiquez le titre de l'émission" value="<?php echo $title; ?>" /></p>
                <p><label for="date">Date de l'émission</label></p>
                <p><input class="datepickable" type="text" name="date" id="date" placeholder="Indiquez la date de l'émission" value="<?php echo $date; ?>" /></p>                
                <textarea name="description" class="ckeditor"><?php echo $description; ?></textarea>
                <?php if($type != "live") { ?>
                <p><label for="playlist">Playlist</label></p>
                <textarea name="playlist" id="playlist"><?php echo $playlist; ?></textarea>
                <?php } ?>
                <div class="btnZone">
                    <input class="button" type="submit" value="Enregister" />                   
                    <a href="index.php?c=emission&a=printEmissions&type=<?php echo $type;?>" class="button">Revenir</a>
                </div>
            </form>             
        <?php
        return ob_get_clean();
    }

    static function confirmDelete($emission) {
        ob_start();
        ?>        
        <h3>Supprimer l'émission du <?php echo $emission->getDate()->format("d/m/Y"); ?> ?</h3>
        <p>Cette action est irréversible.</p>
		<p>Fichier : <em><?php echo $emission->getFilename(); ?></em></p>
        <form method="post" action="index.php?c=emission&a=delete">
            <input type="hidden" name="id" value="<?php echo $emission->getId(); ?>" />
            <p><input type="checkbox" name="delFile" id="delFile" />
            <label for="delFile">Supprimer le fichier en même temps</label></p>
            <input type="submit" class="button" value="Confirmer" />            
        </form>                        
        <?php
        return ob_get_clean();
    }
       
}
?>
