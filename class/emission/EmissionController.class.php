<?php

class EmissionController extends AbstractController {

    public function __construct($action) {
        if (!$action) {
            $action = "printEmissions";
        }
        parent::__construct($action);
    }        

    public function printEmissionsAction() {
        $type = (isset($_GET["type"])) ? $_GET["type"] : "recentes";
        switch($_GET["type"]) {
            case "recentes" : $method = "getRecentesQuery"; break;
            case "speciales" : $method = "getSpecialesQuery"; break;
            case "live" : $method = "getLivesQuery"; break;
        }        
        $pagination = $this->getPagination($this->emissionManager->$method(), 10);
        $pagination["url"] = "index.php?c=emission&amp;a=printEmissions&type=" . $type;
        $query = $pagination["query"];
        $emissions = $query->getObjects("Emission");
        
        foreach($emissions as $emission) {
            $emission->setComments($this->commentManager->getEmissionComments($emission));
        }
        
        return array(
            "title" => "Les émissions",
            "content" => EmissionView::printEmissions($emissions, $pagination, $type)
        );
    }  
    
    public function printPlayerAction() {
        $emission = $this->emissionManager->getOne($_GET["id"]); 
        echo EmissionView::printPlayer($emission);
        exit;
    }
    
    public function printEmissionAction() {
        $emission = $this->emissionManager->getOne($_GET["id"]);                                             
        $emission->setComments($this->commentManager->getEmissionComments($emission));
                
        return array(
            "title" => $emission->getTitle(),
            "content" => EmissionView::printEmissions($emission, false, $emission->getType())
        );
    }
    
    public function editEmissionAction() {
        $type = (isset($_GET["type"])) ? $_GET["type"] : "recentes";
        if(isset($_GET["id"])) {            
            $emission = $this->emissionManager->getOne($_GET["id"]);           
        }
        else {            
            $emission = false;
        }                
        $content = EmissionView::edit($type, $emission);
        $title = "Éditer une émission";
        return array(
            "title" => $title,
            "content" => $content
        );
    }
   
    public function doEditEmissionAction() {
        $emission = new Emission($_REQUEST);             
        if($this->emissionManager->edit($emission)) {            
            header("Location: index.php?c=emission&a=printEmissions&type=" . $emission->getType());
        }     
    }
    
    public function confirmDeleteAction() {
        $title = "Confirmer la suppression d'une émission";
        $emission = $this->emissionManager->getOne($_GET["id"]);
        $content = EmissionView::confirmDelete($emission);
        return array(
            "title" => $title,
            "content" => $content
        );
    }
    public function deleteAction() {
        if(isset($_POST["id"]) && preg_match("#[\d]*#", $_POST["id"])) {
            $emission = $this->emissionManager->getOne($_POST["id"]);
            $emission->setComments($this->commentManager->getEmissionComments($emission));                    
            foreach($emission->getComments() as $comment) {
                $this->commentManager->delete($comment->getId());
            }
            if($this->emissionManager->delete($_POST["id"])) {
                header("Location: index.php?c=emission&a=printEmissions&type=" . $emission->getType() );
            }            
        }        
        else throw new Exception("l'id n'est pas un nombre");
    }
    
    public function helpAction() {
        $title = "Aide";
        $content = file_get_contents("static/help.html");
        return array(
            "title" => $title,
            "content" => $content
        );
    }

    public function filelistAction() {  
        $type = $_POST["type"];
        $emissions = $this->emissionManager->getAll();
        // on crée un tableau avec les filenames des émissions
        $filenames = array();
        foreach ($emissions as $emission) {
            $filenames[] = $emission->getFilename();
        }
        // on prépare un tableau avec les fichiers "ajoutables"
        $toAdd = array();
        
        $dir = opendir("store/audio/" . $type);
        $found = false;        
        $noMp3 = false;
        while (($file = readdir($dir)) !== false) {            
            $ext = substr($file, strlen($file) - 4); // ".mp3"
	  	 
            if ($file != "." && $file != ".." && !in_array($file, $filenames)) {
		if($ext == ".mp3") {			
			$basename = substr($file, 0, strlen($file) - 4); // "Ma Super émission"
			$slug = self::slug($basename); // "ma-super-emission"
			$found = true;
			$toAdd[] = $slug.".mp3";
			rename("store/audio/" . $type . "/" . $file, "store/audio/" . $type . "/" . $slug.".mp3");
		}        
		else {			
			$noMp3 = true;
		}
            }
        }
        closedir($dir);
        // on retourne une variable avec une liste
        if (!$found) {
            echo "<p>Aucun nouveau fichier n'a été trouvé.</p>";
	if($noMp3) {
		echo "<p>Certains fichiers ont été trouvés mais ne sont pas en mp3. Peut-être que le problème vient de là...</p>";
	}
            echo '<p><a href="index.php?c=emission&a=help">POURQUOI ÇA MARCHE PAS ???!!!</a></p>';
            exit();
        }
        $return = "<p>Choisissez un fichier : </p><ul>";
        foreach ($toAdd as $item) {
            $return .= "<li><a href='index.php?c=emission&a=editEmission&amp;type=".$type."&file=".$item."' class='newFile'>" . $item . "</a></li>";
        }
        $return .= "</ul>";        
        echo $return;
        exit();
    }
    
    static private function removeAccent($str) {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
        'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã',
        'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ',
        'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ',
        'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę',
        'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī',
        'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ',
        'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ',
        'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť',
        'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ',
        'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ',
        'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');

        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O',
        'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c',
        'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u',
        'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D',
        'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
        'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K',
        'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o',
        'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S',
        's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W',
        'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i',
        'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
        return str_replace($a, $b, $str);
    }
    
    static private function slug($str) {
        return mb_strtolower(preg_replace(array('/[^a-zA-Z0-9 \'-]/', '/[ -\']+/', '/^-|-$/'), array('', '-', ''), self::removeAccent($str)));
    }

}

?>
