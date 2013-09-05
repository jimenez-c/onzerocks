<?php

class MiscController extends AbstractController {
    public function __construct($action) {
        if (!$action) {
            $action = "setDirect";
        }
        parent::__construct($action);
    }
    public function setDirectAction() {                                
        $date = DateTime::createFromFormat("d/m/Y", $_POST["date"]);
        $date->setTime("19", "30");
        $file = fopen("store/next.txt", "w");
        fputs($file, $date->format("Y-m-d H:i"));
        fclose($file);        
        header("Location: index.php?c=public&amp;a=direct");
    }   
    
    public function migrateAction() {
//        $database = Database::getInstance()->getConnexion();
//        $statement = $database->prepare("UPDATE emissions SET type = 'emission' WHERE type = 'default'");
//        $statement->execute();
//        
//        $statement = $database->prepare("SELECT * FROM emissions");
//        $statement->execute();
//        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $line) {
//            if($line["type"] == "emission") {
//                echo "préparation du déplacement de store/audio/".$line["filename"]." vers store/audio/emission/" . $line["filename"] . "<br />";
//                //rename("store/audio/".$line["filename"], "store/audio/emission/" . $line["filename"]);
//            }
//            if($line["type"] == "live") {
//                echo "préparation du déplacement de store/audio/".$line["filename"]." vers store/audio/live/" . $line["filename"] . "<br />";
//                //rename("store/audio/" . $line["filename"], "store/audio/live/" . $line["filename"]);
//            }
//        }
//        exit;
    }
}

?>
