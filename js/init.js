$(function(){
    /** External links **/
    $("a").click(function(){
        if($(this).attr("rel") == "external") {
            window.open($(this).attr("href"));
            return false;
        }
    });

	/** SuckerFish Menu **/
	$('ul.dropdown').superfish({ autoArrows: false });
	
	/** Datepicker **/
    $(".datepickable").datepicker();
	
    /** btn ajout émission **/
    $("#addEmission a").click(function(evt){        
        evt.preventDefault();
        if($(this).attr("data-open") == "no") {
            $("#filelist").html("Chargement...").show();
            $.ajax({
                url: "index.php?c=emission&a=filelist",
                type: "POST",
                data: "type=" + $(this).attr("href"),
                success: function(list) {
                    $("#filelist").hide().html(list).show("400");                                           
                }
            });
            $(this).html("Fermer");
            $(this).attr("data-open", "yes");
        }      
        else {
            $(this).html("Ajouter");
            $(this).attr("data-open", "no");
            $("#filelist").hide("400");
        }
    });

    /** bouton "supprimer un commentaire" **/
    $(".delComment .delBtn").click(function(evt){
        evt.preventDefault();
        $(this).hide();        
        $(this).parent().find(".confirm").show();
        $(this).parent().find(".cancel").show();
    });
    $(".delComment .confirm").click(function(evt){
        evt.preventDefault();
        var id = $(this).parent().parent().parent().attr("id").substr(9);
        var nb = $("#item-" + id + " li span").html();
        nb = parseInt(nb);        
        nb--;
        var blockquote = $(this).parent().parent();
        $(this).parent().text("Chargement...");                
        $.ajax({
            url: $(this).attr("href"),
            //url : "index.php",
            type: "GET",
            success: function() {                
                blockquote.remove(); 
                //console.log($("#item-" + id));
                $("#item-" + id + " li span").html(nb);
            }
        });        
    });
    $(".delComment .cancel").click(function(evt){
        evt.preventDefault();        
        $(this).hide();        
        $(this).parent().find(".confirm").hide();
        $(this).parent().find(".delBtn").show();
    });

    /** tabs **/   
    $(".item").tabs({
        beforeLoad: function(event, ui) {			
            
            if(ui.tab.attr("data-loaded") === "false") {
				ui.panel.html("Chargement...");
                ui.tab.attr("data-loaded", "true");
            }
            else {
                return false;
            }
        },
        activate: function(evt, ui) {
            var node = ui.newPanel;
            var id = node.attr("id");            
            node.attr("id", "");
            location.hash = id;
            node.attr("id", id);
        }        
    });
    
        
    /** gestion du hash **/
    if(location.hash !== "") {        
        var tab = location.hash.split("-");
        var item = tab[1];
        var panel = tab[0];
        if(location.href.match(/emission/)) {
            switch(panel) {
                case "#infos" :
                    nb = 0;
                    break;
                case "#comments" :
                    nb = 2;
                    break;
                case "#playlist" :
                    nb = 3;
                    break;
                case "#edit" :
                    nb = 4;
                    break;
                case "#delete" :
                    nb = 5;
                break;
                default :
                    nb = 0;
            }  
        }                
        else if(location.href.match(/news/)) {
            switch(panel) {
                case "#news" :
                    nb = 0;
                    break;
                case "#comments" :
                    nb = 1;
                    break;                
                case "#edit" :
                    nb = 2;
                    break;
                case "#delete" :
                    nb = 3;
                break;
                default :
                    nb = 0;
            } 
        }
        if(typeof(nb) != "undefined") {
            $("#item-" + item).tabs( "option",  "active", nb);
        }        
    }    
    
    /**********************************************/
    /****  SCROLL *****/
    /**********************************************/    
    if($(".item").length > 0) {            
            $("#scroll").show();
    }
    $(window).scroll(function(evt){        
        $("#scroll").css("top", $(window).scrollTop());
    }); 
    $("#scrollTop").click(function(){        
        $('html, body').animate({
            scrollTop: 0
        }, 500);        
    });
    $("#scrollBottom").click(function(){        
        $('html, body').animate({
            scrollTop: $(document).height() - $(window).height()
        }, 500);        
    });
    $("#scrollUp").click(function(){
        var prevScroll = 0;
        var currentScroll = $(window).scrollTop();
        $(".item").each(function(index, element) {
            var elementScroll = $(element).offset().top;            
            if(elementScroll < currentScroll && elementScroll > prevScroll) {                
                prevScroll = elementScroll;
            }
        });
        $('html, body').animate({
            scrollTop: prevScroll
        }, 250);
        //window.scrollTo(0, prevScroll);
    });
    $("#scrollDown").click(function(){
        var nextScroll = 10000;
        var currentScroll = $(window).scrollTop();
        $(".item").each(function(index, element) {
            var elementScroll = $(element).offset().top;            
            if(elementScroll > currentScroll + 5 && elementScroll < nextScroll) {                
                nextScroll = elementScroll;
            }
        });
        $('html, body').animate({
        	scrollTop: nextScroll
        }, 250);
    });
    
    /**********************************************/
    /****  CAPTCHA *****/
    /**********************************************/    
    if($(".item").length > 1) {
        $(".item").each(function(index, item){
            if(index == 0) {
                first_captcha = $(item);
            }
            else {
                $(item).find("#recaptcha_widget_div").replaceWith( first_captcha.find("#recaptcha_widget_div").clone(true, true) );
                $(item).find("#recaptcha_widget_div").show();
            }
        }); 
    }
    if($("#captcha-error").length > 0) {        
        var tab = location.hash.split("-");
        var id = tab[1];        
        $("#captcha-error").insertAfter($("#comments-" + id).find("#recaptcha_widget_div"));        
    }

});
