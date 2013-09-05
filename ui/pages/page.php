<?php 
    if(!isset($data["title"])) $data["title"] = "Erreur";
    if(!isset($data["content"])) $data["content"] = "Erreur";
    if(!isset($data["controller"])) $data["controller"] = false;
    if(!isset($data["action"])) $data["action"] = false;
    if(!isset($data["user"])) $data["user"] = false;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- website template design by serprise design - http://www.serprisedesign.co.uk -->
        <title>On Ze Rocks :: <?php echo $data["title"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="ui/images/favicon.png" />
        
        <link rel="stylesheet" type="text/css" media="screen" href="js/jquery-ui/css/smoothness/jquery-ui-1.10.0.custom.min.css" />
        <link rel="stylesheet" type="text/css" href="js/lightbox/css/jquery.lightbox-0.5.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="js/countdown/jquery.countdown.css" media="screen" />
        <link rel="stylesheet" type="text/css" media="screen" href="ui/css/style.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="ui/css/perso.css" />
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'/>
        
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/jquery-ui/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui/js/jquery.ui.datepicker-fr.js"></script>
        <script type="text/javascript" src="js/lightbox/js/jquery.lightbox-0.5.min.js"></script>
        <script type="text/javascript" src="js/countdown/jquery.countdown.js"></script>        
        <script type="text/javascript" src="js/superfish.js"></script>
        <script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
        
        <script type="text/javascript" src="jwplayer/jwplayer.js" ></script>
        <script type="text/javascript" src="js/init.js" ></script>        
    </head>
    <body>
        <div id="headercont" class="clearfix">            
            <div id="header">                
                <div id="headerlogo">
                    <h1><a title="On Ze Rocks" href="index.php">On Ze Rocks</a></h1>
                </div>                
                <?php echo PublicView::printHeaderMenu($data["controller"], $data["action"]); ?>                
            </div>            
        </div>

        <div id="maincont" class="clearfix">
            <div id="mainleft">                    
                <p>Depuis 1992, dans On ze rocks, il y a bien sûr du rock “ tout à fond”, mais aussi 
                de la pop, de la folk, des interviews, des sessions live  en direct, l’avenir du rock, 
                le glorieux passé et quelques bêtises... C’est tous les mercredis dès 19h30 sur le 96.2 fm
                dans la région d’Orléans, ou sur ce site en direct live ... </p>
                <h6>Keep on rockin’ !</h6>
                <div id="logomenu"></div>
                <?php echo PublicView::printSideMenu($data["controller"], $data["action"], $data["user"]); ?>                                 
            </div>
            <div id="mainright">
                <?php echo $data["content"]; ?>
                <nav id="scroll" style="display:none">
                    <button class="mini" id="scrollTop"></button>
                    <button class="maxi" id="scrollUp"></button>
                    <button class="maxi" id="scrollDown"></button>
                    <button class="mini" id="scrollBottom"></button>
                </nav>
            </div>
        </div>

        <div id="footercont" class="clearfix">
            <p>Template Design by <a title="derby web design" href="http://www.serprisedesign.co.uk" rel="external">Derby Web Design</a></p>
            <p>Powered by <a href="http://www.longtailvideo.com/jw-player/about/">JW Player</a></p>
        </div>          
    </body>
</html>

