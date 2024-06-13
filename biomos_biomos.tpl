{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- Biomos implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    biomos_biomos.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->


<div id="pochon"></div>

<div id="global">


<div id="biome">
        <div id="biomecard" class="biomecard"></div>
        <div id="biomeonboard_1" class="biomeonboard" style="left: 0px; top: 0px;"></div>
        <div id="biomeonboard_2" class="biomeonboard" style="left: 0px; top: 130px;"></div>
        <div id="biomeonboard_3" class="biomeonboard" style="left: 0px; top: 260px;"></div>
        <div id="biomeonboard_4" class="biomeonboard" style="left: 0px; top: 390px;"></div>
</div>
<div id="bigbiome">
        <div id="bigbiomecard" class="bigbiomecard"></div>
        <div id="bigbiomeonboard_1" class="bigbiomeonboard" style="left: 0px; top: 0px;"></div>
        <div id="bigbiomeonboard_2" class="bigbiomeonboard" style="left: 0px; top: 130px;"></div>
        <div id="bigbiomeonboard_3" class="bigbiomeonboard" style="left: 0px; top: 260px;"></div>
        <div id="bigbiomeonboard_4" class="bigbiomeonboard" style="left: 0px; top: 390px;"></div>
</div>
<div id="terrainonboard">
        <div id="terrainonboard_1" class="terrainonboard" style="left: 15px; top: 15px;"></div>
        <div id="terrainonboard_2" class="terrainonboard" style="left: 80px; top: 25px;"></div>
        <div id="terrainonboard_3" class="terrainonboard" style="left: 145px; top: 15px;"></div>
        <div id="terrainonboard_4" class="terrainonboard" style="left: 210px; top: 25px;"></div>
        <div id="terrainonboard_5" class="terrainonboard" style="left: 275px; top: 15px;"></div>
</div>

<div id="select"></div>
<div id="playersboard">
    <!-- BEGIN player -->
        <div class="playerposition_{POS}" style="text-align: center; color:#0b3d66; font-weight: bold;">
        {PLAYER_NAME}
        </div>
            <div id="playerboard_{PLAYER_ID}" class="playerboard">
                <div id="terrain_{PLAYER_ID}_1" class="terrain" style="left: 128px; top: 189px;"></div>
                <div id="terrain_{PLAYER_ID}_2" class="terrain" style="left: 148px; top: 242px;"></div>
                <div id="terrain_{PLAYER_ID}_3" class="terrain" style="left: 190px; top: 281px;"></div>
                <div id="terrain_{PLAYER_ID}_4" class="terrain" style="left: 245px; top: 298px;"></div>
                <div id="terrain_{PLAYER_ID}_5" class="terrain" style="left: 302px; top: 291px;"></div>
                <div id="terrain_{PLAYER_ID}_6" class="terrain" style="left: 349px; top: 259px;"></div>
                <div id="terrain_{PLAYER_ID}_7" class="terrain" style="left: 378px; top: 210px;"></div>
                <div id="terrain_{PLAYER_ID}_8" class="terrain" style="left: 383px; top: 154px;"></div>
                <div id="terrain_{PLAYER_ID}_9" class="terrain" style="left: 363px; top: 100px;"></div>
                <div id="terrain_{PLAYER_ID}_10" class="terrain" style="left: 322px; top: 61px;"></div>
                <div id="terrain_{PLAYER_ID}_11" class="terrain" style="left: 268px; top: 43px;"></div>
                <div id="terrain_{PLAYER_ID}_12" class="terrain" style="left: 211px; top: 50px;"></div>
                <div id="terrain_{PLAYER_ID}_13" class="terrain" style="left: 466px; top: 55px;"></div>
                <div id="terrain_{PLAYER_ID}_16" class="terrain" style="left: 256px; top: 171px;"></div>
                <div id="fleche_{PLAYER_ID}_1_d" class="fleched" style="left: 96px; top: 225px; transform: rotate(83deg);"></div>
                <div id="fleche_{PLAYER_ID}_2_g" class="flecheg" style="left: 117px; top: 275px; transform: rotate(62deg)"></div>
                <div id="fleche_{PLAYER_ID}_2_d" class="fleched" style="left: 128px; top: 295px; transform: rotate(58deg)"></div>
                <div id="fleche_{PLAYER_ID}_3_g" class="flecheg" style="left: 170px; top: 334px; transform: rotate(30deg)"></div>
                <div id="fleche_{PLAYER_ID}_3_d" class="fleched" style="left: 190px; top: 345px; transform: rotate(30deg)"></div>
                <div id="fleche_{PLAYER_ID}_4_g" class="flecheg" style="left: 243px; top: 364px; transform: rotate(5deg)"></div>
                <div id="fleche_{PLAYER_ID}_4_d" class="fleched" style="left: 268px; top: 366px; transform: rotate(5deg)"></div>
                <div id="fleche_{PLAYER_ID}_5_g" class="flecheg" style="left: 326px; top: 356px; transform: rotate(-21deg)"></div>
                <div id="fleche_{PLAYER_ID}_5_d" class="fleched" style="left: 349px; top: 349px; transform: rotate(-21deg)"></div>
                <div id="fleche_{PLAYER_ID}_6_g" class="flecheg" style="left: 395px; top: 318px; transform: rotate(312deg)"></div>
                <div id="fleche_{PLAYER_ID}_6_d" class="fleched" style="left: 411px; top: 301px; transform: rotate(312deg)"></div>
                <div id="fleche_{PLAYER_ID}_7_g" class="flecheg" style="left: 439px; top: 255px; transform: rotate(286deg)"></div>
                <div id="fleche_{PLAYER_ID}_7_d" class="fleched" style="left: 445px; top: 231px; transform: rotate(286deg)"></div>
                <div id="fleche_{PLAYER_ID}_8_g" class="flecheg" style="left: 452px; top: 179px; transform: rotate(263deg)"></div>
                <div id="fleche_{PLAYER_ID}_8_d" class="fleched" style="left: 449px; top: 153px; transform: rotate(263deg)"></div>
                <div id="fleche_{PLAYER_ID}_9_g" class="flecheg" style="left: 427px; top: 97px; transform: rotate(235deg)"></div>
                <div id="fleche_{PLAYER_ID}_9_d" class="fleched" style="left: 416px; top: 78px; transform: rotate(235deg)"></div>
                <div id="fleche_{PLAYER_ID}_10_g" class="flecheg" style="left: 372px; top: 37px;transform: rotate(206deg)"></div>
                <div id="fleche_{PLAYER_ID}_10_d" class="fleched" style="left: 352px; top: 27px;transform: rotate(206deg)"></div>
                <div id="fleche_{PLAYER_ID}_11_g" class="flecheg" style="left: 301px; top: 12px;transform: rotate(189deg)"></div>
                <div id="fleche_{PLAYER_ID}_11_d" class="fleched" style="left: 276px; top: 10px;transform: rotate(189deg)"></div>
                <div id="fleche_{PLAYER_ID}_12_g" class="flecheg" style="left: 212px; top: 17px;transform: rotate(154deg)"></div>
            </div>
            
        
    <!-- END player -->
    <!-- BEGIN playeravance -->
        <div id="playerposition_{PLAYER_ID}" class="playerposition_{POS}" style="text-align: center; font-weight: bold">
        {PLAYER_NAME}
        </div>
            <div id="playerboard_{PLAYER_ID}" class="playerboardavance">
                <div id="terrain_{PLAYER_ID}_1" class="terrain" style="left: 384px; top: 189px;"></div>
                <div id="terrain_{PLAYER_ID}_2" class="terrain" style="left: 364px; top: 242px;"></div>
                <div id="terrain_{PLAYER_ID}_3" class="terrain" style="left: 322px; top: 281px;"></div>
                <div id="terrain_{PLAYER_ID}_4" class="terrain" style="left: 267px; top: 298px;"></div>
                <div id="terrain_{PLAYER_ID}_5" class="terrain" style="left: 210px; top: 291px;"></div>
                <div id="terrain_{PLAYER_ID}_6" class="terrain" style="left: 163px; top: 259px;"></div>
                <div id="terrain_{PLAYER_ID}_7" class="terrain" style="left: 134px; top: 210px;"></div>
                <div id="terrain_{PLAYER_ID}_8" class="terrain" style="left: 129px; top: 154px;"></div>
                <div id="terrain_{PLAYER_ID}_9" class="terrain" style="left: 149px; top: 100px;"></div>
                <div id="terrain_{PLAYER_ID}_10" class="terrain" style="left: 190px; top: 61px;"></div>
                <div id="terrain_{PLAYER_ID}_11" class="terrain" style="left: 244px; top: 43px;"></div>
                <div id="terrain_{PLAYER_ID}_12" class="terrain" style="left: 301px; top: 50px;"></div>
                <div id="terrain_{PLAYER_ID}_14" class="terrain" style="left: 349px; top: 84px;"></div>
                <div id="terrain_{PLAYER_ID}_15" class="terrain" style="left: 377px; top: 132px;"></div>
                <div id="terrain_{PLAYER_ID}_13" class="terrain" style="left: 46px; top: 55px;"></div>
                <div id="terrain_{PLAYER_ID}_16" class="terrain" style="left: 256px; top: 171px;"></div>
                <div id="fleche_{PLAYER_ID}_1_d" class="fleched" style="left: 451px; top: 225px; transform: rotate(97deg);"></div>
                <div id="fleche_{PLAYER_ID}_2_g" class="flecheg" style="left: 430px; top: 275px; transform: rotate(118deg)"></div>
                <div id="fleche_{PLAYER_ID}_2_d" class="fleched" style="left: 419px; top: 295px; transform: rotate(122deg)"></div>
                <div id="fleche_{PLAYER_ID}_3_g" class="flecheg" style="left: 377px; top: 334px; transform: rotate(150deg)"></div>
                <div id="fleche_{PLAYER_ID}_3_d" class="fleched" style="left: 357px; top: 345px; transform: rotate(150deg)"></div>
                <div id="fleche_{PLAYER_ID}_4_g" class="flecheg" style="left: 304px; top: 364px; transform: rotate(175deg)"></div>
                <div id="fleche_{PLAYER_ID}_4_d" class="fleched" style="left: 279px; top: 366px; transform: rotate(175deg)"></div>
                <div id="fleche_{PLAYER_ID}_5_g" class="flecheg" style="left: 221px; top: 356px; transform: rotate(201deg)"></div>
                <div id="fleche_{PLAYER_ID}_5_d" class="fleched" style="left: 198px; top: 349px; transform: rotate(201deg)"></div>
                <div id="fleche_{PLAYER_ID}_6_g" class="flecheg" style="left: 152px; top: 318px; transform: rotate(-132deg)"></div>
                <div id="fleche_{PLAYER_ID}_6_d" class="fleched" style="left: 136px; top: 301px; transform: rotate(-132deg)"></div>
                <div id="fleche_{PLAYER_ID}_7_g" class="flecheg" style="left: 108px; top: 255px; transform: rotate(-106deg)"></div>
                <div id="fleche_{PLAYER_ID}_7_d" class="fleched" style="left: 102px; top: 231px; transform: rotate(-106deg)"></div>
                <div id="fleche_{PLAYER_ID}_8_g" class="flecheg" style="left: 95px; top: 179px; transform: rotate(-83deg)"></div>
                <div id="fleche_{PLAYER_ID}_8_d" class="fleched" style="left: 98px; top: 153px; transform: rotate(-83deg)"></div>
                <div id="fleche_{PLAYER_ID}_9_g" class="flecheg" style="left: 120px; top: 97px; transform: rotate(-60deg)"></div>
                <div id="fleche_{PLAYER_ID}_9_d" class="fleched" style="left: 131px; top: 78px; transform: rotate(-60deg)"></div>
                <div id="fleche_{PLAYER_ID}_10_g" class="flecheg" style="left: 175px; top: 37px;transform: rotate(-26deg)"></div>
                <div id="fleche_{PLAYER_ID}_10_d" class="fleched" style="left: 195px; top: 27px;transform: rotate(-26deg)"></div>
                <div id="fleche_{PLAYER_ID}_11_g" class="flecheg" style="left: 246px; top: 12px;transform: rotate(-9deg)"></div>
                <div id="fleche_{PLAYER_ID}_11_d" class="fleched" style="left: 271px; top: 10px;transform: rotate(-9deg)"></div>
                <div id="fleche_{PLAYER_ID}_12_g" class="flecheg" style="left: 335px; top: 17px;transform: rotate(26deg)"></div>
            </div>
            
        
    <!-- END playeravance -->
</div>
</div>

<div class='player_board_reserve' id="player_board_reserve" style="display: flex; align-items: center; flex-direction: column; justify-content: center; z-index: 100; position: relative;">
        <div id="text" style="text-align: left; color: gray;"></div>
        <div id="icon" style="text-align: center; display: flex; margin-top: 3px;">
            <div id="icon1" class="type_icon1" style="display: inline-block; margin-right: 4px;"></div>
            <div id="nbr1" class="nbr1" style="display: inline-block; margin-right: 4px;"></div>
            <div id="icon2" class="type_icon2" style="display: inline-block; margin-right: 4px;"></div>
            <div id="nbr2" class="nbr2" style="display: inline-block; margin-right: 4px;"></div>
            <div id="icon3" class="type_icon3" style="display: inline-block; margin-right: 4px;"></div>
            <div id="nbr3" class="nbr3" style="display: inline-block; margin-right: 4px;"></div>
            <div id="icon4" class="type_icon4" style="display: inline-block; margin-right: 4px;"></div>
            <div id="nbr4" class="nbr4" style="display: inline-block; margin-right: 4px;"></div>
            <div id="icon5" class="type_icon5" style="display: inline-block; margin-right: 4px;"></div>
            <div id="nbr5" class="nbr5" style="display: inline-block; margin-bottom: 5px"></div>
        </div>
		
</div>

<div id="pref"></div>






<script type="text/javascript">
var jstpl_boardselect='<div class="boardselect" style="left: ${LEFT}px; top: ${TOP}px" id="boardselect_${id}"></div>';
var jstpl_token='<div class="token tokentype_${type}" id="token_${id}"></div>';
var jstpl_biome='<div class=" biome biometype_${type}" id="biome_${id}" style="background-position-x: ${x}%; background-position-y: ${y}%;"></div>';
var jstpl_biometool='<div style="text-align: center;">${name}</div><br><div class=" biometool" style="background-position-x: ${x}px;"></div>';
var jstpl_bigbiome='<div class=" bigbiome bigbiometype_${type}" id="bigbiome_${id}" style="background-position-x: ${x}%;"></div>';
var jstpl_bigbiometool='<div style="text-align: center;">${name}</div><br><div class=" bigbiometool" style="background-position-x: ${x}px;"></div>';
var jstpl_player_board_moon='<div class="player_points"><div id="player_board_moon_${id}" class="player_score_token"><div id="icon_moon_${id}" class ="moon" style="margin-right: 5px; margin-left: 0px; display: inline-block;"></div><div id="score_moon_${id}" style="text-align: center; display: inline-block; margin-right: 0px;"></div><div id="icon_moon" class="icon_moon" style="display: inline-block;"></div></div><div id ="emplacement_${id}"></div></div>';
var jstpl_player_score_board='<div id="player_score_board_${id}" class="player_score_board player_score_token" style="text-align: center; margin-top: 10px;"><div id="icon_board1_${id}" style="display: inline-block;"></div><div id="score_board1_${id}" style="text-align: center; display: inline-block; margin-right: 4px;"></div><div id="icon_board2_${id}" style="display: inline-block;"></div><div id="score_board2_${id}" style="text-align: center; display: inline-block; margin-right: 2px"></div></div>';
var jstpl_player_score_biome='<div class="player_points"><div id="player_score_biome_${id}" class="player_score_token2"><div class="player_score_token3"><div class="biome_card_score" style="background-position-x: 0px;"></div><div id="score_player_biome_${id}" class="player_biome_nb" style="text-align: center; margin-left: 5px;"></div></div><div class="player_score_token3"><div class="biome_card_score" style="background-position-x: -74px;"></div><div id="score_player_bigbiome_${id}" class="player_biome_nb" style="text-align: center; margin-left: 5px;"></div></div></div></div>';
var jstpl_boardtool='<div class="boardtool" style="background-position-x: ${x}px;"></div>';



var preference = document.getElementById("pref");


var windowHeight;
        window.addEventListener("resize", function() {
                // Code à exécuter lorsque la fenêtre du navigateur est redimensionnée
                windowHeight = window.innerHeight;



                    if ((windowHeight <= 730) || (document.querySelector('#select')))
                    {
                    window.addEventListener("scroll", function() {
                        var biome = document.getElementById("biome");
                        var scrollY = window.scrollY;
                        var newPosition = 40; // Ajustez la valeur selon vos besoins
                        biome.style.top = 40 + "px";
                        });
    
                    window.addEventListener("scroll", function() {
                        var bigbiome = document.getElementById("bigbiome");
                        var scrollY = window.scrollY;
                        var newPosition = 40; // Ajustez la valeur selon vos besoins
                        bigbiome.style.top = 40 + "px";
                        });
                    } 
                

                    if ((windowHeight > 730) && (!preference.classList.contains("masque")) && (!document.querySelector('.playerposition_3')) && (!document.querySelector('.playerposition_4')) && (!document.querySelector('#select')))
                    {

                    window.addEventListener("scroll", function() {
                        var biome = document.getElementById("biome");
                        var scrollY = window.scrollY;
                        var newPosition = 40 + scrollY; // Ajustez la valeur selon vos besoins
                        if (scrollY >= 405)
                        {
                            biome.style.top = 445 + "px";
                        }
                        else{
                        biome.style.top = newPosition + "px";
                        }
            
                        var bigbiome = document.getElementById("bigbiome");
                        var scrollY = window.scrollY;
                        var newPosition = 40 + scrollY; // Ajustez la valeur selon vos besoins
                        if (scrollY >= 405)
                        {
                            bigbiome.style.top = 445 + "px";
                        }
                        else{
                        bigbiome.style.top = newPosition + "px";
                        }

                        
            
                        });
                    }

                    if ((windowHeight > 730) && (!preference.classList.contains("masque")) && (document.querySelector('.playerposition_3')) && (!document.querySelector('.playerposition_4')) && (!document.querySelector('#select')))
                    {

                        window.addEventListener("scroll", function() {
                        var biome = document.getElementById("biome");
                        var scrollY = window.scrollY;
                        var newPosition = 40 + scrollY; // Ajustez la valeur selon vos besoins
                        if (scrollY >= 835)
                        {
                            biome.style.top = 875 + "px";
                        }
                        else{
                        biome.style.top = newPosition + "px";
                        }
            
                        var bigbiome = document.getElementById("bigbiome");
                        var scrollY = window.scrollY;
                        var newPosition = 40 + scrollY; // Ajustez la valeur selon vos besoins
                        if (scrollY >= 835)
                        {
                            bigbiome.style.top = 875 + "px";
                        }
                        else{
                        bigbiome.style.top = newPosition + "px";
                        }

                                
                        });
                    }
        

                    if ((windowHeight > 730) && (!preference.classList.contains("masque")) && (document.querySelector('.playerposition_4')) && (!document.querySelector('#select')))
                    {

                        window.addEventListener("scroll", function() {
                        var biome = document.getElementById("biome");
                        var scrollY = window.scrollY;
                        var newPosition = 40 + scrollY; // Ajustez la valeur selon vos besoins
                        if (scrollY >= 1260)
                        {
                            biome.style.top = 1300 + "px";
                        }
                        else{
                        biome.style.top = newPosition + "px";
                        }
            
                        var bigbiome = document.getElementById("bigbiome");
                        var scrollY = window.scrollY;
                        var newPosition = 40 + scrollY; // Ajustez la valeur selon vos besoins
                        if (scrollY >= 1260)
                        {
                            bigbiome.style.top = 1300 + "px";
                        }
                        else{
                        bigbiome.style.top = newPosition + "px";
                        }

                        
            
                        });
                    }


            });














</script>  

{OVERALL_GAME_FOOTER}
