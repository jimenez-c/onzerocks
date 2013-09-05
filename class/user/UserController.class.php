<?php

class UserController extends AbstractController {
    public function __construct($action) {
        if(!$action) {
            $action = "update";
        }
        parent::__construct($action);
    }
    public function updateAction() {
        $user = $this->userManager->getOne($_SESSION["id"]);
        $control = uniqid();
        $_SESSION["control"] = $control;
        return array(
            "title" => "Modifier votre profil",
            "content" => UserView::updateProfile($user, $control)
        );
    }
    
    public function doUpdateAction() {
        $url = $this->userManager->updateProfile();
        header("Location: ".$url);
    }
}

?>
