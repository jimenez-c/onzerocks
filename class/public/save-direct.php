<?php if (isset($_SESSION["id"])) { ?>
            <p>En tant que Super-Manu, vous disposez de l'immense pouvoir de changer <span class="crossed">le destin du monde</span> la date de la prochaine émission.
                Mais attention : un grand pouvoir implique de grandes responsabilités.</p>
            <form id="nextDate" method="post" action="index.php?c=misc&amp;a=setDirect">
                <input type="text" class="datepickable" name="date" value="<?php echo $nextStd->format("d/m/Y"); ?>" />
                <input type="submit" value="Enregistrer" />
            </form>       
        <?php } ?>
        <div id="player-wrap" style="display:none">
            <div id="player"></div>   
            <div id="help">
                <h4>À savoir : </h4>
                <ul>
                    <li>Le flux audio est de mauvaise qualité, nous n'y pouvons rien. Cela entraîne des coupures régulières.</li>
                    <li>La <strong>lecture automatique</strong> est activée, donc vous n'avez pas à appuyer sur "play" normalement.</li>
                    <li>En cas de plantage, essayez d'appuyer sur <strong>Ctrl + F5</strong>, ça recharge la page en vidant le cache.</li>
                    <li>Sinon, vous pouvez utiliser <a target="_blank" href="http://www.videolan.org/vlc">VLC</a> pour lire le flux. Dans "Média", cliquez sur "Ouvrir un flux réseau", et entrez l'adresse 
                    suivante : <br />http://mp3.live.tv-radio.com/arcenciel/all/arcenciel-128k.mp3</li>
                </ul>
            </div>
        </div>
        <div id="countdown-wrap" style="display:none">            
            <div id="countdown"></div>
        </div>

        <script type="text/javascript">
            $(function() {
                today = new Date(<?php echo $today; ?>);
                next = new Date(<?php echo $next; ?>);
                end = new Date(next.getTime());

                //end = new Date(next.getTime() + (2 * 60 * 60 * 1000));  


                end.setHours(21);
                end.setMinutes(40);
                end.setSeconds(0);

                (function check() {
                    // à chaque seconde, on augmente de 1 le nb de secondes de today
                    today.setSeconds(today.getSeconds() + 1);

                    // si c'est l'heure
                    if (today.getTime() >= next.getTime() && today.getTime() < end.getTime()) {
                        //console.log("C'est l'heure !");
                        printPlayer();
                    }
                    // sinon
                    else {
                        // s'il y a eu un pb, et que next est avant today
                        if (end.getTime() <= today.getTime()) {
                            $.ajax({
                                url: "index.php?c=public&a=calculateNext",
                                type: "POST",
                                success: function(newDate) {
                                    //next = new Date(parseInt(newDate));
                                    location.href = "index.php?c=public&a=direct";
                                }
                            });

                        }
                        // s'il reste moins de 24h
                        else if (today.getTime() >= next.getTime() - 24 * 3600 * 1000) {
                            //console.log("c'est bientôt l'heure");
                            printCountdown(next);
                        }
                        else {
                            // on calcule l'écart entre next et today en jours
                            //console.log(next);
                            var diff = Math.floor((next.getTime() - today.getTime()) / (1000 * 60 * 60 * 24));
                            $("#countdown-wrap").show();
                            if (diff == 1)
                                var txt = "jour";
                            else
                                var txt = "jours";
                            $("#countdown").text("La prochaine émission aura lieu le " + next.toLocaleDateString() + ", dans " + diff + " " + txt + ".");
                        }
                    }

                    timer = window.setTimeout(check, 1000);
                })();
            });
            function printPlayer() {
                // on vérifie que le player n'est pas déjà affiché
                if (!$("#player-wrap").is(":visible")) {
                    $("#countdown-wrap").hide();
                    $("#player-wrap").show();
                    
                jwplayer("player").setup({
                    width:'500',
                    height:'24',
                    controlbar: 'bottom',
                    file: "http://mp3.live.tv-radio.com/arcenciel/all/arcenciel-128k.mp3",
                    autostart: 'true'
                });
                jwplayer("player").onIdle( function(evt) {
                    console.log("bug");
                    jwplayer("player").stop();
                    jwplayer("player").play();
                });
                }
            }
            function printCountdown(next) {
                // on vérifie que le countdown n'est pas déjà affiché
                if (!$("#countdown-wrap").is(":visible")) {
                    // on arrête le player et on le cache
                    //jwplayer("player").stop();
                    $("#player-wrap").hide();
                    // on affiche le compte à rebours
                    $("#countdown-wrap").show();
                    $('#countdown').countdown({
                        timestamp: next
                    });
                    $(".countDays, .countDiv0").css("display", "none");
                }
            }
        </script>