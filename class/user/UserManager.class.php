<?php

class UserManager extends AbstractManager {
    
    private static $instance;
    
    private function __construct($db) {
        $this->class = "User";
        $this->table = "users";
        $this->db = $db;
    }

    static public function getInstance($db) {
        if (self::$instance == null) {
            self::$instance = new UserManager($db);
        }
        return self::$instance;
    }
        
    public function getAll() {
        $query = new QuerySelect("*", "users");
        return $query->getObjects("User");
    }    
    public function getOne($id) {
        $query = new QuerySelect("*", "users", array("id" => $id));        
        return $query->getObject("User");
    }
    

    public function login($login, $password) {
        if (empty($login) || empty($password))
            throw new Exception("Les champs ne peuvent pas être vides.");
        else {
            $login = htmlspecialchars($login);
            $password = htmlspecialchars($password);
            $hash = $this->hashPwd($password);

            try {
                $query = 'SELECT * FROM users WHERE (pseudo = :login OR email = :login) and password = :hash';
                $statement = $this->db->prepare($query);
                $statement->bindValue("login", $login);
                $statement->bindValue("hash", $hash);
                $statement->execute();

                $data = $statement->fetch();
                if (empty($data)) {
                    $logged = false;                    
                }
                else {                    
                    $_SESSION["id"] = $data["id"];                    
                    $logged = true;
                }
                $statement->closeCursor();
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }

            if($logged) {
                return "index.php?c=news&a=print";
            }
            else {
                throw new Exception("Erreur lors du login.");
            }
        }
    }

    public function logout() {
        session_destroy();
    }
    
    public function updateProfile() {
        if(!isset($_POST["control"]) || !isset($_SESSION["control"]) || $_POST["control"] != $_SESSION["control"]) {
            return new Exception("Problème de contrôle du formulaire");
        }
        else {
            unset($_SESSION["control"]);
        }
        $id = $_SESSION["id"];
        $user = $this->getOne($id);
        
        $email = htmlspecialchars($_POST["email"]);
        $old = $this->hashPwd(htmlspecialchars($_POST["old"]));
        $new = $this->hashPwd(htmlspecialchars($_POST["new"]));
        $confirm = $this->hashPwd(htmlspecialchars($_POST["confirm"]));
                
        // si le champ "ancien mdp" n'est pas vide
        if($old != "") {
            // si le champ "ancien mdp" correspond à l'ancien mdp stocké en session
            if($old == $user->getPassword()) {
                // si le champ "nouveau mdp" n'est pas vide
                if($new != "") {
                    // si le champ "confirmation" n'est pas vide
                    if($confirm != "") {
                        // si les champs "confirmation" et "nouveau mdp" correspondent
                        if($confirm == $new) {
                            // alors on change le mot de passe
                            $password = $new;                            
                        }
                        else {
                            throw new Exception("Les deux mots de passe ne correspondent pas.");
                        }
                    }
                    else {
                        throw new Exception("Vous devez confirmer le nouveau mot de passe.");
                    }
                }
                else {
                    throw new Exception("Vous devez renseigner un nouveau mot de passe.");
                }
            }
            else {
                throw new Exception("Vous vous êtes trompé dans votre ancien mot de passe.");
            }
        }
        // sinon on met à jour avec l'ancien mot de passe
        else $password = $user->getPassword();                
        
        try {
            $query = "UPDATE users SET email = :email, password = :password WHERE id = :id";
            $statement = $this->db->prepare($query);            
            $statement->bindValue("email", $email);            
            $statement->bindValue("password", $password);            
            $statement->bindValue("id", $id);
            $statement->execute();

            return 'index.php?c=user';
            
        } catch (Exception $e) {
            echo "erreur sql update profile : ".$e->getMessage();
        }
    }
    
    private function hashPwd($password) {
        $sel = "fqj#H.ze^15%0Lks";
        $hash = hash ( "sha512" , hash ( "sha512", $password . $sel));
        return $hash;
    }

}

?>
