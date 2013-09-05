<?php

class UserView extends AbstractView {

    static function updateProfile($user, $control) {
        ob_start();
        ?>
        <h3>Modifier votre profil</h3>
        <form id="updateProfile" method="post" action="index.php?c=user&a=doUpdate">
            <input type="hidden" name="control" value="<?php echo $control; ?>" />
            <p class="row">
                <span>Nom d'utilisateur</span>
                <span><?php echo $user->getPseudo(); ?></span>                
            </p>
            <p class="row">
                <span>Adresse e-mail</span>
                <span><input type="text" name="email" value="<?php echo $user->getEmail(); ?>" /></span>
            </p>
            <p class="row">
                <span>Ancien mot de passe</span>
                <span><input type="password" name="old" /></span>            
            </p>
            <p class="row">
                <span>Nouveau mot de passe</span>
                <span><input type="password" name="new" /></span>            
            </p>
            <p class="row">
                <span>Confirmez le nouveau mot de passe</span>
                <span><input type="password" name="confirm" /></span>            
            </p>
            <p>
                <input type="submit" value="Enregistrer" class="button" />
            </p>
        </form>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

}
?>
