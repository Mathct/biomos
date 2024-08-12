/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Biomos implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * biomos.js
 *
 * Biomos user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

 define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter"
],
function (dojo, declare) {
    return declare("bgagame.biomos", ebg.core.gamegui, {
        constructor: function(){
            console.log('biomos constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

        },

        updatePlayerOrdering() {
            this.inherited(arguments);
            dojo.place('player_board_reserve', 'player_boards', 'last');
        },  
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
            
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                         
                // TODO: Setting up players boards if needed
            }
            
            // TODO: Set up your game interface here, according to "gamedatas"

           

           if (this.prefs[100].value == 1)
           {
            dojo.query("#pref").removeClass("masque");
           }
           if (this.prefs[100].value == 2)
           {
            dojo.query("#pref").addClass("masque");
           }

            
            if (gamedatas.nbreplayers == 2)
            {
                dojo.query("#global").addClass("global2");

                if ((window.innerHeight > 730) && (this.prefs[100].value == 1))
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
        }

            if (gamedatas.nbreplayers == 3)
            {
                dojo.query("#global").addClass("global3");

                if ((window.innerHeight > 730) && (this.prefs[100].value == 1)) 
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
        }

            if (gamedatas.nbreplayers == 4)
            {
                dojo.query("#global").addClass("global4");

                if ((window.innerHeight > 730) && (this.prefs[100].value == 1)) 
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
        }

        


               
            

            for (var i in gamedatas.terrainonboard) {
                var variable = gamedatas.terrainonboard[i];
                
                if (variable.id !== null) {
                    
                            this.addTerrainOnBoard(variable.id, variable.type, variable.position);
                        
                    }
            }
            

            for( var i in gamedatas.terrainonplayer )
                {
                    var variable = gamedatas.terrainonplayer[i];
                    
                    if( variable.id !== null )
                    {
                        this.addTerrainOnPlayer( variable.id, variable.type, variable.location, variable.position);
                    }

                }

            for( var i in gamedatas.biomeonboard )
                {
                    var variable = gamedatas.biomeonboard[i];
                    
                    if( variable.id !== null )
                    {
                        this.addBiomeOnBoard( variable.id, variable.type, variable.position);
                    }
                }

            for( var i in gamedatas.bigbiomeonboard )
                {
                    var variable = gamedatas.bigbiomeonboard[i];
                    
                    if( variable.id !== null )
                    {
                        this.addBigBiomeOnBoard( variable.id, variable.type, variable.position);
                    }
                }

            if (gamedatas.gamemode == 2)
            {
                for( var i in gamedatas.board)
                {
                    var variable = gamedatas.board[i];
                    
                    if( variable.id !== null )
                    {
                        dojo.query("#playerboard_"+variable.id).addClass("board"+variable.board);

                        
                        // couleur du board
                        if (variable.board == 1)
                        {
                            var divElement1 = document.getElementById("playerposition_"+variable.id);
                            divElement1.style.color = "#00794E";
                        }
                        if (variable.board == 2)
                        {
                            var divElement2 = document.getElementById("playerposition_"+variable.id);
                            divElement2.style.color = "#8A3434";
                        }
                        if (variable.board == 3)
                        {
                            var divElement3 = document.getElementById("playerposition_"+variable.id);
                            divElement3.style.color = "#B88552";
                        }
                        if (variable.board == 4)
                        {
                            var divElement4 = document.getElementById("playerposition_"+variable.id);
                            divElement4.style.color = "#005D99";
                        }

                    }


                  

                }

            }        
            
            $('text').innerHTML = _("Reserve:");
            $('nbr1').innerHTML = gamedatas.nbre1;
            $('nbr2').innerHTML = gamedatas.nbre2; 
            $('nbr3').innerHTML = gamedatas.nbre3; 
            $('nbr4').innerHTML = gamedatas.nbre4; 
            $('nbr5').innerHTML = gamedatas.nbre5;  
            
            
            for( var player_id in gamedatas.players )   
            {
                                        
                var player_board_div = $('player_board_'+player_id);
                dojo.place( this.format_block('jstpl_player_board_moon', {id: player_id } ), player_board_div );
                dojo.place( this.format_block('jstpl_player_score_biome', {id: player_id } ), player_board_div );
                
            }

            for( var i in gamedatas.moon )   
            {
                var player = i;
                
                $('score_moon_'+i).innerHTML = gamedatas.moon[i][0];
                dojo.query("#icon_moon_"+i).addClass("type_icon"+gamedatas.moon[i][1]);
                if (gamedatas.moon[i][1] != '1' && gamedatas.moon[i][1] != '2' && gamedatas.moon[i][1] != '3' && gamedatas.moon[i][1] != '4' && gamedatas.moon[i][1] != '5')
                {
                    dojo.query("#icon_moon_"+i).addClass("icon_nomoon");

                }
                
            }
            
            
            if (gamedatas.gamemode == 2)
            {
                for( var player_id in gamedatas.players )   
                {
                
                var player_board_div = $('emplacement_'+player_id);
                dojo.place( this.format_block('jstpl_player_score_board', {id: player_id } ), player_board_div );
                
                }

                for( var i in gamedatas.scoreboard )   
                {
                    var player = i;
                    
                    $('score_board1_'+i).innerHTML = gamedatas.scoreboard[i][1];
                    $('score_board2_'+i).innerHTML = gamedatas.scoreboard[i][2];

                if (gamedatas.scoreboard[i][0] == 1)
                    {
                    dojo.query("#icon_board1_"+i).addClass("type_icon3");
                    dojo.query("#icon_board2_"+i).addClass("type_icon3");
                    
                    var divElement = document.getElementById("icon_board1_"+i);
                    divElement.style.marginRight = "5px";
                    var divElement = document.getElementById("icon_board2_"+i);
                    divElement.style.marginRight = "5px";
                    
                    }

                if (gamedatas.scoreboard[i][0] == 2)
                    {
                    dojo.query("#icon_board1_"+i).addClass("type_icon3");
                    dojo.query("#icon_board2_"+i).addClass("type_icon4");
                    
                    var divElement1 = document.getElementById("icon_board1_"+i);
                    divElement1.style.marginRight = "5px";
                    var divElement2 = document.getElementById("icon_board2_"+i);
                    divElement2.style.marginRight = "5px";
                    }

                if (gamedatas.scoreboard[i][0] == 3)
                    {
                    dojo.query("#icon_board1_"+i).addClass("type_icon3");
                    dojo.query("#icon_board2_"+i).addClass("type_icon2");
                    
                    var divElement1 = document.getElementById("icon_board1_"+i);
                    divElement1.style.marginRight = "5px";
                    var divElement2 = document.getElementById("icon_board2_"+i);
                    divElement2.style.marginRight = "5px";
                    }

                if (gamedatas.scoreboard[i][0] == 4)
                    {
                    dojo.query("#icon_board1_"+i).addClass("type_icon3");
                    dojo.query("#icon_board2_"+i).addClass("type_icon5");
                    
                    var divElement1 = document.getElementById("icon_board1_"+i);
                    divElement1.style.marginRight = "5px";
                    var divElement2 = document.getElementById("icon_board2_"+i);
                    divElement2.style.marginRight = "5px";
                    }

                    
                }
            }

            for( var player in gamedatas.scorebiome )   
            {
                $('score_player_biome_'+player).innerHTML = gamedatas.scorebiome[player][0];                       
                
            }

            for( var player in gamedatas.scorebigbiome )   
            {
                $('score_player_bigbiome_'+player).innerHTML = gamedatas.scorebigbiome[player][0];                       
                
            }

            
            
               
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            dojo.query(".token").connect('onclick', this, 'onSelect' );
            dojo.query(".terrain").connect('onclick', this, 'onSelectTerrain' );
            dojo.query(".flecheg").connect('onclick', this, 'onSelectFleche' );
            dojo.query(".fleched").connect('onclick', this, 'onSelectFleche' );
            dojo.query(".biome").connect('onclick', this, 'onValidateBiome' );
            dojo.query(".bigbiome").connect('onclick', this, 'onValidateBiome' );
            dojo.query(".boardselectable").connect('onclick', this, 'onValidateBoard' );

            console.log( "Ending game setup" );
        },
       

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            dojo.query(".selectable").removeClass("selectable");
            dojo.query(".selectable2").removeClass("selectable2");
            dojo.query(".selectable3").removeClass("selectable3");
            dojo.query(".selectable4").removeClass("selectable4");
            dojo.query(".selected").removeClass("selected");
            dojo.query(".fleched").addClass("masque");
            dojo.query(".flecheg").addClass("masque");
            dojo.query(".biomeselectable").removeClass("biomeselectable");
            dojo.query(".biomeselected").removeClass("biomeselected");
            
             


            console.log( 'Entering state: '+stateName );

              switch( stateName )
                {
                
                case 'playerSelectBoard':

                dojo.query("#playersboard").addClass("masque");
                dojo.destroy('boardselect_1');
                dojo.destroy('boardselect_2');
                dojo.destroy('boardselect_3');
                dojo.destroy('boardselect_4');

                
                
                
                
                dojo.query("#global").removeClass("global2");
                dojo.query("#global").removeClass("global3");
                dojo.query("#global").removeClass("global4");
                dojo.query("#global").addClass("global1");
                

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

                                    
                    var id = 0;
                for ( var x = 0; x<=1; x++)
                {
                    for ( var y = 0; y<=1; y++)
                    {
                        id = id + 1;
                        var left = x * 294;
                        var top = y * 201;
                        this.addSelectOnBoard (id, left, top);
                    }
                }
                

                this.args = args.args;

                for( var p in this.args.player )
                {
                
                dojo.query("#emplacement_"+this.args.player[p]).addClass("masque");
                }
                                
                for( var bd in this.args.selectable )
                    {

                dojo.place( "<div id=miniboard_"+this.args.selectable[bd]+" class=boardselectable></div>", "boardselect_"+this.args.selectable[bd] );
                var html = '<div class="anatooltip"><div class="anatboard">'+this.format_block('jstpl_boardtool',{x: (this.args.selectable[bd]-1)*(-400)})+'</div></div>';
	            this.addTooltipHtml( 'miniboard_'+this.args.selectable[bd], html,1000);
                dojo.query("#miniboard_"+this.args.selectable[bd]).connect('onclick', this, 'onValidateBoard' );

                    }
                    
                break;
                
                
                case 'playerTurnSelect':

                this.args = args.args;

                //dojo.query("#position_"+this.args.playeractive).addClass("positionactive");

                dojo.query("#global").removeClass("global1");

                
            

            if (this.args.nbreplayers == 2)
            {
                    dojo.query("#global").addClass("global2");

                    if ((window.innerHeight > 730) && (this.prefs[100].value == 1))
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
            }


            if (this.args.nbreplayers == 3)
            {
                dojo.query("#global").addClass("global3");

                if ((window.innerHeight > 730) && (this.prefs[100].value == 1)) 
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
        }

            if (this.args.nbreplayers == 4)
            {
                dojo.query("#global").addClass("global4");

                if ((window.innerHeight > 730) && (this.prefs[100].value == 1)) 
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
            }

            


                
                dojo.destroy('select');
                dojo.query("#playersboard").removeClass("masque");

                
                if (this.args.gamemode == 2)
                    {
                    for( var bd in this.args.board[0])
                        {
                            dojo.query("#playerboard_"+this.args.board[0][bd].id).addClass("board"+this.args.board[0][bd].board);

                                    // couleur du board
                                if (this.args.board[0][bd].board == 1)
                                {
                                    var divElement1 = document.getElementById("playerposition_"+this.args.board[0][bd].id);
                                    divElement1.style.color = "#00794E";
                                }
                                if (this.args.board[0][bd].board == 2)
                                {
                                    var divElement2 = document.getElementById("playerposition_"+this.args.board[0][bd].id);
                                    divElement2.style.color = "#8A3434";
                                }
                                if (this.args.board[0][bd].board == 3)
                                {
                                    var divElement3 = document.getElementById("playerposition_"+this.args.board[0][bd].id);
                                    divElement3.style.color = "#B88552";
                                }
                                if (this.args.board[0][bd].board == 4)
                                {
                                    var divElement4 = document.getElementById("playerposition_"+this.args.board[0][bd].id);
                                    divElement4.style.color = "#005D99";
                                }
                        }
                    }
                
                for( var sid in this.args.selectable )
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+sid).addClass("selectable");
                        }
                    }
                for( var fid in this.args.fleche )
                    {
                        var flecheid = this.args.fleche[fid].split("_")[1];
                        if(this.isCurrentPlayerActive() && flecheid == this.getActivePlayerId())
                        {
                        dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                        }
                        if(!this.isCurrentPlayerActive() && flecheid == this.getCurrentPlayerId())
                        {
                        dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                        }
                    }
                break;

                case 'playerTurnPlace':
                dojo.query(".selectable").removeClass("selectable");
                this.args = args.args;
                //dojo.query("#position_"+this.args.playeractive).addClass("positionactive");
                if(this.isCurrentPlayerActive())
                {
                dojo.query("#"+ this.args.selected).addClass("selected");
                }
                setTimeout(() => 
                { 
                for( var sid in this.args.selectable)
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.selectable[sid]).addClass("selectable");
                        }
                    }
                }, "1");
                for( var fid in this.args.fleche )
                    {
                        var flecheid = this.args.fleche[fid].split("_")[1];
                        if(this.isCurrentPlayerActive() && flecheid == this.getActivePlayerId())
                        {
                        dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                        }
                        if(!this.isCurrentPlayerActive() && flecheid == this.getCurrentPlayerId())
                        {
                        dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                        }
                    }
                break;

                case 'playerMoon':
                this.args = args.args;
                //dojo.query("#position_"+this.args.playeractive).addClass("positionactive");
                for( var sid in this.args.selectable )
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.selectable[sid]).addClass("selectable2");
                        }
                    }
                for( var fid in this.args.fleche )
                    {
                        var flecheid = this.args.fleche[fid].split("_")[1];
                        if(this.isCurrentPlayerActive() && flecheid == this.getActivePlayerId())
                        {
                        dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                        }
                        if(!this.isCurrentPlayerActive() && flecheid == this.getCurrentPlayerId())
                        {
                        dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                        }
                    }
                break;


                case 'playerMoonSelect':
                dojo.query(".selectable").removeClass("selectable3");
                this.args = args.args;
                //dojo.query("#position_"+this.args.playeractive).addClass("positionactive");
                if(this.isCurrentPlayerActive())
                {
                dojo.query("#token_"+this.args.selected).addClass("selected");
                }
                
                setTimeout(() => 
                {                       
                    for( var sid in this.args.selectable)
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.selectable[sid]).addClass("selectable3");
                        }
                    }
                }, "1");
                
                for( var fid in this.args.fleche )
                    {
                        var flecheid = this.args.fleche[fid].split("_")[1];
                        if(this.isCurrentPlayerActive() && flecheid == this.getActivePlayerId())
                        {
                        dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                        }
                        if(!this.isCurrentPlayerActive() && flecheid == this.getCurrentPlayerId())
                        {
                        dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                        }
                    }
                
                break;
                

                case 'playerBiome':
                this.args = args.args;
                //dojo.query("#position_"+this.args.playeractive).addClass("positionactive");
                for( var sid in this.args.BiomeValide )
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.BiomeValide[sid]).addClass("biomeselectable");
                        }
                    }
                for( var sid in this.args.BigBiomeValide )
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.BigBiomeValide[sid]).addClass("biomeselectable");
                        }
                    }
                break;

                
                
                
                case 'playerEvenement':
                this.args = args.args;
                //dojo.query("#position_"+this.args.playeractive).addClass("positionactive");
                /*for( var sid in this.args.Event2 )
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.Event2[sid]).addClass("selectable4");
                        }
                    }*/

                for( var fid in this.args.fleche )
                {
                    var flecheid = this.args.fleche[fid].split("_")[1];
                    if(this.isCurrentPlayerActive() && flecheid == this.getActivePlayerId())
                    {
                    dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                    }
                    if(!this.isCurrentPlayerActive() && flecheid == this.getCurrentPlayerId())
                    {
                    dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                    }
                }
                    
                break;


                case 'playerEvenementSelect':
                this.args = args.args;
                //dojo.query("#position_"+this.args.playeractive).addClass("positionactive");
                for( var sid in this.args.Event)
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.Event[sid]).addClass("selectable4");
                        }
                    }

                for( var fid in this.args.fleche )
                {
                    var flecheid = this.args.fleche[fid].split("_")[1];
                    if(this.isCurrentPlayerActive() && flecheid == this.getActivePlayerId())
                    {
                    dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                    }
                    if(!this.isCurrentPlayerActive() && flecheid == this.getCurrentPlayerId())
                    {
                    dojo.query("#"+this.args.fleche[fid]).removeClass("masque");
                    }
                }
                
                
                if(this.args.NumberEvent[0] == 1)
                {
                    
                    this.gamedatas.gamestate.descriptionmyturn = _('Replace any Desert next to a Sea with a Forest');
                    this.updatePageTitle();
                                        

                }
                if(this.args.NumberEvent[0] == 2)
                {
                    
                    this.gamedatas.gamestate.descriptionmyturn = _('Replace any Sea next to a Mountain with a Glacier');
                    this.updatePageTitle();
                                        

                }
                if(this.args.NumberEvent[0] == 3)
                {
                    
                    this.gamedatas.gamestate.descriptionmyturn = _('Replace any Land next to a Desert with a Desert');
                    this.updatePageTitle();
                                        

                }
                if(this.args.NumberEvent[0] == 4)
                {
                    
                    this.gamedatas.gamestate.descriptionmyturn = _('Replace any Glacier with a randomly drawn Land');
                    this.updatePageTitle();
                                        

                }
                                   
                break;
            
                
                
                
                
                
                
                
                
                
                
                
                
                case 'dummmy':
                    break;
                }
            
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {

                    case "playerTurnPlace":
                        this.addActionButton( 'cancel', _("Cancel") ,'onOpCancel', null, null, 'gray' );
                    break;

                    case "playerMoon":
                        this.addActionButton( 'notmove', _("Not move Land token") ,'onOpNotMove', null, null, 'gray' );
                    break;

                    case "playerBiome":
                        this.addActionButton( 'notvalidate', _("Not validate Biome") ,'onOpNotValidate', null, null, 'gray' );
                    break;

                    case "playerEvenement":

                        if(args.Event1 != 0)
                        {
                        this.addActionButton( 'Event1', '<div class="event1"></div>' ,'onOpEvent1', null, null, 'gray' );
                        this.addTooltipToClass( 'event1', '', _('Replace any Desert next to a Sea with a Forest'), 1000 );
                        }
                        if(args.Event2 != 0)
                        {
                        this.addActionButton( 'Event2', '<div class="event2"></div>' ,'onOpEvent2', null, null, 'gray' );
                        this.addTooltipToClass( 'event2', '', _('Replace any Sea next to a Mountain with a Glacier'), 1000 );
                        }
                        if(args.Event3 != 0)
                        {
                        this.addActionButton( 'Event3', '<div class="event3"></div>' ,'onOpEvent3', null, null, 'gray' );
                        this.addTooltipToClass( 'event3', '', _('Replace any Land next to a Desert with a Desert'), 1000 );
                        }
                        if(args.Event4 != 0)
                        {
                        this.addActionButton( 'Event4', '<div class="event4"></div>' ,'onOpEvent4', null, null, 'gray' );
                        this.addTooltipToClass( 'event4', '', _('Replace any Glacier with a randomly drawn Land'), 1000 );
                        }
                        this.addActionButton( 'notEvent', _("Do not trigger an event") ,'onOpNotEvent', null, null, 'gray' );
                        
                    break;

                    case "playerEvenementSelect":
                        
                        this.addActionButton( 'cancel', _("Cancel") ,'onOpCancel', null, null, 'gray' );
                    break;

                    


                }
            }
        }, 



        
        onOpCancel: function(evt)
            {
                this.ajaxcall( "/biomos/biomos/actCancel.html", { 
                    lock: true,
                             
                    }, 
                    this, function( result ) {}, function( is_error) {} );
    
            },

        onOpNotMove: function(evt)
            {
                this.ajaxcall( "/biomos/biomos/actNotMove.html", { 
                        lock: true,
                                 
                        }, 
                        this, function( result ) {}, function( is_error) {} );
            },

        onOpNotValidate: function(evt)
            {
                this.ajaxcall( "/biomos/biomos/actNotValidate.html", { 
                        lock: true,
                                 
                        }, 
                        this, function( result ) {}, function( is_error) {} );
            },

        onOpNotEvent: function(evt)
        {
            this.ajaxcall( "/biomos/biomos/actNotEvent.html", { 
                    lock: true,
                                
                    }, 
                    this, function( result ) {}, function( is_error) {} );
        },

        onOpEvent1: function(evt)
        {
            this.ajaxcall( "/biomos/biomos/actEvent1.html", { 
                    lock: true,
                                
                    }, 
                    this, function( result ) {}, function( is_error) {} );
        },

        onOpEvent2: function(evt)
        {
            this.ajaxcall( "/biomos/biomos/actEvent2.html", { 
                    lock: true,
                                
                    }, 
                    this, function( result ) {}, function( is_error) {} );
        },

        onOpEvent3: function(evt)
        {
            this.ajaxcall( "/biomos/biomos/actEvent3.html", { 
                    lock: true,
                                
                    }, 
                    this, function( result ) {}, function( is_error) {} );
        },

        onOpEvent4: function(evt)
        {
            this.ajaxcall( "/biomos/biomos/actEvent4.html", { 
                    lock: true,
                                
                    }, 
                    this, function( result ) {}, function( is_error) {} );
        },

        ///////////////////////////////////////////////////
        //// Utility methods
        
        /*
        
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
        
        */

            addSelectOnBoard: function( id, x, y )  
                {
                    dojo.place( this.format_block( 'jstpl_boardselect', {
                        id: id,
                        LEFT: x,
                        TOP: y,
                                               
                    } ) , 'select' );  
                    
                                        
                },


            addTerrainOnBoard: function( id, type, position )  
                {
                    dojo.place( this.format_block( 'jstpl_token', {
                        id: id,
                        type: type,
                                               
                    } ) , 'terrainonboard_'+position );  
                    
                    this.placeOnObject( 'token_'+id, 'pochon' );
                    this.slideToObject( 'token_'+id, 'terrainonboard_'+position ).play();
                },


            addTerrainOnPlayer: function( id, type, location, position )  
                
                {
                    if(position!=0)
                    {
                    dojo.place( this.format_block( 'jstpl_token', {
                        id: id,
                        type: type,
                                           
                    } ) , location+'_'+position );
                }
                    
                },

            addTerrainOnPlayer2: function( id, type, location, position )  
                {
                    dojo.place( this.format_block( 'jstpl_token', {
                        id: id,
                        type: type,
                                           
                    } ) , location+'_'+position );

                    this.placeOnObject( 'token_'+id, 'pochon' );
                    this.slideToObject( 'token_'+id, location+'_'+position ).play();
                    
                },


            addBiomeOnBoard: function( id, type, position )  
                {
                    if(type <=15)
                    {
                    dojo.place( this.format_block( 'jstpl_biome', {
                        id: id,
                        type: type,
                        x: (type-1)*(-100),
                        y: 0,
                        
            
                                               
                    } ) , 'biomeonboard_'+position );
                    }
                    
                    if(type >=16)
                    {
                    dojo.place( this.format_block( 'jstpl_biome', {
                        id: id,
                        type: type,
                        x: (type-16)*(-100),
                        y: -100,
                        
            
                                               
                    } ) , 'biomeonboard_'+position );
                    }

                    var name = _(this.gamedatas.biomename[type].name);
                    var html = '<div class="anatooltip"><div class="anatcard">'+this.format_block('jstpl_biometool',{name: name, x: (type-1)*(-250)})+'</div></div>';
	                this.addTooltipHtml( 'biome_'+id, html,1000);
                    
                    this.placeOnObject( 'biome_'+id, 'biomecard' );
                    this.slideToObject( 'biome_'+id, 'biomeonboard_'+position ).play();
                },

            addBigBiomeOnBoard: function( id, type, position )  
                {
                    dojo.place( this.format_block( 'jstpl_bigbiome', {
                        id: id,
                        type: type,
                        x: (type-1)*(-100)
                                               
                    } ) , 'bigbiomeonboard_'+position ); 
                    
                    var name = _(this.gamedatas.bigbiomename[type].name);
                    var html = '<div class="anatooltip"><div class="anatcard">'+this.format_block('jstpl_bigbiometool',{name: name, x: (type-1)*(-250)})+'</div></div>';
	                this.addTooltipHtml( 'bigbiome_'+id, html,1000);
                    
                    this.placeOnObject( 'bigbiome_'+id, 'bigbiomecard' );
                    this.slideToObject( 'bigbiome_'+id, 'bigbiomeonboard_'+position ).play();
                },



            attachToNewParentNoDestroy: function (mobile_in, new_parent_in, relation, place_position) {
    
                    const mobile = $(mobile_in);
                    const new_parent = $(new_parent_in);
        
                    var src = dojo.position(mobile);
                    if (place_position)
                        mobile.style.position = place_position;
                    dojo.place(mobile, new_parent, relation);
                    mobile.offsetTop;//force re-flow
                    var tgt = dojo.position(mobile);
                    var box = dojo.marginBox(mobile);
                    var cbox = dojo.contentBox(mobile);
                    var left = box.l + src.x - tgt.x;
                    var top = box.t + src.y - tgt.y;
        
                    mobile.style.position = "absolute";
                    mobile.style.left = left + "px";
                    mobile.style.top = top + "px";
                    box.l += box.w - cbox.w;
                    box.t += box.h - cbox.h;
                    mobile.offsetTop;//force re-flow
                    return box;
                },


        ///////////////////////////////////////////////////
        //// Player's action
        
        
        onSelect: function(evt)
            {        	 
                // Preventing default browser reaction
                 dojo.stopEvent( evt );
    
                
                 
                if( !this.isCurrentPlayerActive() || (!(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selectable4'))) )
                {   
                    return; 
                }
                
                if(this.isCurrentPlayerActive() && evt.currentTarget.classList.contains('selectable'))
                {
                    
                    this.ajaxcall( "/biomos/biomos/actTurnSelect.html", { 
                        lock: true,
                        arg1: evt.currentTarget.id
                        
                     }, 
                     this, function( result ) {}, function( is_error) {} );
                }

                if(this.isCurrentPlayerActive() && evt.currentTarget.classList.contains('selectable2'))
                {
                
                this.ajaxcall( "/biomos/biomos/actMoonSelect.html", { 
                    lock: true,
                    arg1: evt.currentTarget.id
                    
                 }, 
                 this, function( result ) {}, function( is_error) {} );
                }

                if(this.isCurrentPlayerActive() && evt.currentTarget.classList.contains('selectable4'))
                {
                
                this.ajaxcall( "/biomos/biomos/actEventSelect.html", { 
                    lock: true,
                    arg1: evt.currentTarget.id
                    
                 }, 
                 this, function( result ) {}, function( is_error) {} );
                }


            },


        onSelectTerrain: function(evt)
            {        	 
                         
                 // Preventing default browser reaction
                 dojo.stopEvent( evt );
                 
                if( !this.isCurrentPlayerActive() || (!(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable3'))) )
                {   return; 
                }
                 
                if(this.isCurrentPlayerActive() && evt.currentTarget.classList.contains('selectable'))
                
                {
    
                this.ajaxcall( "/biomos/biomos/actSelect.html", { 
                    lock: true,
                    arg1: evt.currentTarget.id
                 }, 
                 this, function( result ) {}, function( is_error) {} );

                }

                if(this.isCurrentPlayerActive() && evt.currentTarget.classList.contains('selectable3') && !(evt.currentTarget.classList.contains('selectable')))
                
                {
    
                this.ajaxcall( "/biomos/biomos/actMoonMove.html", { 
                    lock: true,
                    arg1: evt.currentTarget.id
                 }, 
                 this, function( result ) {}, function( is_error) {} );

                }



    
                 
            
            },

        onSelectFleche: function(evt)
            {        	 
                         
                 // Preventing default browser reaction
                 dojo.stopEvent( evt );
                 
                if( (evt.currentTarget.classList.contains('masque')) )
                {   return; 
                }
                
                var player = evt.currentTarget.id.split("_")[1];
                var pos = evt.currentTarget.id.split("_")[2];
                var dir = evt.currentTarget.id.split("_")[3];
                dojo.query(".fleched").addClass("masque");
                dojo.query(".flecheg").addClass("masque");
    
                this.ajaxcall( "/biomos/biomos/actSelectFleche.html", { 
                    lock: true,
                    arg1: evt.currentTarget.id
                    
                 }, 
                 this, function( result ) {}, function( is_error) {} );
    
                 
            
            },

        onValidateBiome: function(evt)
            {
                dojo.stopEvent( evt );
                 
                if( !this.isCurrentPlayerActive() || !(evt.currentTarget.classList.contains('biomeselectable')) )
                {   return; 
                }
                
                var targetid = evt.currentTarget.id;
                dojo.query("#"+targetid).removeClass("biomeselectable");
                dojo.query("#"+targetid).addClass("biomeselected");

                this.ajaxcall( "/biomos/biomos/actValidateBiome.html", { 
                    lock: true,
                    arg1: evt.currentTarget.id
                    
                 }, 
                 this, function( result ) {}, function( is_error) {} );


            },

        onValidateBoard: function(evt)
        {
            dojo.stopEvent( evt );
                
            if( !this.isCurrentPlayerActive() || !(evt.currentTarget.classList.contains('boardselectable')) )
            {   return; 
            }
            
            var targetid = evt.currentTarget.id;
            dojo.query("#"+targetid).removeClass("boardselectable");
           

            this.ajaxcall( "/biomos/biomos/actValidateBoard.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                
                }, 
                this, function( result ) {}, function( is_error) {} );


        },

        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your biomos.game.php file.
        
        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );

            dojo.subscribe( 'move', this, "notif_move" );
            dojo.subscribe( 'moonselect', this, "notif_moonselect" );
            dojo.subscribe( 'moonmove', this, "notif_moonmove" );
            dojo.subscribe( 'remplir', this, "notif_remplir" );
            dojo.subscribe( 'infopassmoon', this, "notif_infopassmoon" );
            dojo.subscribe( 'infopassbiome', this, "notif_infopassbiome" );
            dojo.subscribe( 'validatebiome', this, "notif_validatebiome" );
            dojo.subscribe( 'validatebigbiome', this, "notif_validatebigbiome" );
            dojo.subscribe( 'validateboard', this, "notif_validateboard" );
            dojo.subscribe( 'moonscore', this, "notif_moonscore" );
            dojo.subscribe( 'movefleche', this, "notif_movefleche" );
            dojo.subscribe( 'movecroise', this, "notif_movecroise" );
            dojo.subscribe( 'boardmoonscore', this, "notif_boardmoonscore" );
            dojo.subscribe( 'boardscore', this, "notif_boardscore" );
            

            
            // TODO: here, associate your game notifications with local methods
            
            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
        },  
        
        // TODO: from this point and below, you can write your game notifications handling methods

        notif_boardmoonscore: function( notif )
        {

            $('score_moon_'+notif.args.id).innerHTML = notif.args.score;
            dojo.query("#icon_moon_"+notif.args.id).removeClass("type_icon5");
            if(notif.args.type == '1' || notif.args.type == '2' || notif.args.type == '3' || notif.args.type == '4' || notif.args.type == '5')
            {
            dojo.query("#icon_moon_"+notif.args.id).removeClass("icon_nomoon");
            dojo.query("#icon_moon_"+notif.args.id).addClass("type_icon"+notif.args.type);
            }



        },

        notif_boardscore: function( notif )
        {
            dojo.query("#emplacement_"+notif.args.id).removeClass("masque");

            $('score_board1_'+notif.args.id).innerHTML = notif.args.score1;
            $('score_board2_'+notif.args.id).innerHTML = notif.args.score2;
            if (notif.args.typeboard == 1)
                {
                dojo.query("#icon_board1_"+notif.args.id).addClass("type_icon3");
                dojo.query("#icon_board2_"+notif.args.id).addClass("type_icon3");
                //$('textmulti1_'+notif.args.id).innerHTML = 'x2';
                var divElement = document.getElementById("icon_board1_"+notif.args.id);
                divElement.style.marginRight = "5px";
                var divElement = document.getElementById("icon_board2_"+notif.args.id);
                divElement.style.marginRight = "5px";
                }

            if (notif.args.typeboard == 2)
                {
                dojo.query("#icon_board1_"+notif.args.id).addClass("type_icon3");
                //$('textmulti1_'+notif.args.id).innerHTML = 'x1';
                dojo.query("#icon_board2_"+notif.args.id).addClass("type_icon4");
                //$('textmulti2_'+notif.args.id).innerHTML = 'x1';
                var divElement1 = document.getElementById("icon_board1_"+notif.args.id);
                divElement1.style.marginRight = "5px";
                var divElement2 = document.getElementById("icon_board2_"+notif.args.id);
                divElement2.style.marginRight = "5px";
                }

            if (notif.args.typeboard == 3)
                {
                dojo.query("#icon_board1_"+notif.args.id).addClass("type_icon3");
                //$('textmulti1_'+notif.args.id).innerHTML = 'x1';
                dojo.query("#icon_board2_"+notif.args.id).addClass("type_icon2");
                //$('textmulti2_'+notif.args.id).innerHTML = 'x1';
                var divElement1 = document.getElementById("icon_board1_"+notif.args.id);
                divElement1.style.marginRight = "5px";
                var divElement2 = document.getElementById("icon_board2_"+notif.args.id);
                divElement2.style.marginRight = "5px";
                }

            if (notif.args.typeboard == 4)
                {
                dojo.query("#icon_board1_"+notif.args.id).addClass("type_icon3");
                //$('textmulti1_'+notif.args.id).innerHTML = 'x1';
                dojo.query("#icon_board2_"+notif.args.id).addClass("type_icon5");
                //$('textmulti2_'+notif.args.id).innerHTML = 'x1';
                var divElement1 = document.getElementById("icon_board1_"+notif.args.id);
                divElement1.style.marginRight = "5px";
                var divElement2 = document.getElementById("icon_board2_"+notif.args.id);
                divElement2.style.marginRight = "5px";
                }



        }, 
        
        notif_infopassmoon: function( notif )
            {
            },

        notif_infopassbiome: function( notif )
            {
            },
        
        notif_move: function( notif )
            {
                
                this.attachToNewParentNoDestroy( notif.args.mobile, notif.args.parent );
                this.slideToObject( notif.args.mobile, notif.args.parent ).play();

                   
            },

        notif_moonselect: function( notif )
            {
                
                this.attachToNewParentNoDestroy( notif.args.mobile, notif.args.parent );
                this.slideToObject( notif.args.mobile, notif.args.parent ).play();

                   
            },

        notif_moonmove: function( notif )
            {
                
                this.attachToNewParentNoDestroy( notif.args.mobile, notif.args.parent );
                this.slideToObject( notif.args.mobile, notif.args.parent ).play();

                   
            },
        

        notif_remplir: function( notif )
            {
                for (var i in notif.args.mobile) {
                    var variable = notif.args.mobile[i];
                    
                    if (variable.id !== null) {
                        
                                this.addTerrainOnBoard(variable.id, variable.type, variable.position);
                                dojo.query("#token_"+variable.id).connect('onclick', this, 'onSelect' ); // connecter au clic les nouveaux terrain par leur id (ne pas le faire par la class sinon ca risque de generer des doubles clic sur les autres deja présents)
                            
                        }
                }

                $('nbr1').innerHTML = notif.args.nbre1;
                $('nbr2').innerHTML = notif.args.nbre2; 
                $('nbr3').innerHTML = notif.args.nbre3; 
                $('nbr4').innerHTML = notif.args.nbre4; 
                $('nbr5').innerHTML = notif.args.nbre5;
            },

        notif_validatebiome: function( notif )
            {
                
                this.attachToNewParentNoDestroy( notif.args.mobile, 'playerboard_'+notif.args.player_id );
                this.removeTooltip(notif.args.mobile);               
                this.slideToObject( notif.args.mobile, 'playerboard_'+notif.args.player_id ).play();
                this.fadeOutAndDestroy (notif.args.mobile, 500, 700);
                
                
                for( var player_id in notif.args.newscore )
                {
                    var newScore = notif.args.newscore[ player_id ];
                    this.scoreCtrl[ player_id ].toValue( newScore );
                    
                }

                
                
                setTimeout(() => 
                {
                
                    if(notif.args.newid == null)
                    {

                    }

                    else
                    {
                        this.addBiomeOnBoard( notif.args.newid, notif.args.newtype, notif.args.position )  ;
                        dojo.query("#biome_"+notif.args.newid).connect('onclick', this, 'onValidateBiome' );

                    }

                }, "1600");

                $('score_player_biome_'+notif.args.player_id).innerHTML = notif.args.scorebiomeplayer;

            },


            notif_validatebigbiome: function( notif )
            {
                
                this.attachToNewParentNoDestroy( notif.args.mobile, 'playerboard_'+notif.args.player_id );
                this.removeTooltip(notif.args.mobile); 
                  
                this.slideToObject( notif.args.mobile, 'playerboard_'+notif.args.player_id ).play();
                this.fadeOutAndDestroy (notif.args.mobile, 500, 700);
                
                
                for( var player_id in notif.args.newscore )
                {
                    var newScore = notif.args.newscore[ player_id ];
                    this.scoreCtrl[ player_id ].toValue( newScore );
                }

                $('score_player_bigbiome_'+notif.args.player_id).innerHTML = notif.args.scorebigbiomeplayer;           
                

            },

            
            
            notif_moonscore: function( notif )
            {
                                            
                
                for( var player_id in notif.args.newscore )
                {
                    var newScore = notif.args.newscore[ player_id ];
                    this.scoreCtrl[ player_id ].toValue( newScore );
                }



            },

            notif_movefleche: function( notif )
            {
                
                /*
                

                var objet = document.getElementById(notif.args.mobile);
                var parentDirect = objet.parentNode;
                var idParentDirect = parentDirect.id;
                var parent = document.getElementById(idParentDirect);

                // Obtenez une référence à l'élément d'arrivée par son ID
                var arriveeElement = document.getElementById(notif.args.parent);

                // Obtenez les coordonnées de départ de l'objet
                var startX = parent.offsetLeft;
                var startY = parent.offsetTop;

                // Obtenez les coordonnées de l'élément d'arrivée
                var endX = arriveeElement.offsetLeft;
                var endY = arriveeElement.offsetTop;

                // Définissez le centre de rotation
                var centerX = 283;
                var centerY = -198;

                var debut = 0;
                var fin = 1;

                //var angledebut = Math.atan((startY+198)/(startX-283));
                //var anglefin = Math.atan((endY+198)/(endX-283));

                // Créez une animation avec Dojo Toolkit
                var animation = new dojo.Animation({
                    curve: [debut, fin],

                    onAnimate: (t) => {
                        
                    var x = (endX - startX)*t;
                    
                    //var x = centerX + Math.cos(t) * 128; // Rayon de 128 pixels
                    //var y = centerY + Math.sin(t) * 128; // Rayon de 128 pixels
                    //var angle = acos ((startX + x - centerX)/128);
                    //var y = ((sin(angle))*128);
                    
        
                        objet.style.left = x + "px";
                        //objet.style.top = y + "px";
                    }
                });

                // Exécutez l'animation
                animation.play();


                */

                
                this.attachToNewParentNoDestroy( notif.args.mobile, notif.args.parent );
                this.slideToObject( notif.args.mobile, notif.args.parent, 300 ).play();
                


                   
            },

            notif_validateboard: function( notif )
            {
                
                this.fadeOutAndDestroy (notif.args.mobile, 500);
                this.removeTooltip(notif.args.mobile);
                

            },
            
            notif_movecroise: function( notif )
            {
                
                this.attachToNewParentNoDestroy( notif.args.mobile1, notif.args.parent2 );
                this.slideToObject( notif.args.mobile1, notif.args.parent2 ).play();
                this.fadeOutAndDestroy (notif.args.mobile1, 500);

                this.addTerrainOnPlayer2(notif.args.mobile2, notif.args.type, notif.args.parentlocation1, notif.args.parentposition1);
                dojo.query("#token_"+notif.args.mobile2).connect('onclick', this, 'onSelect' );
                
                $('nbr1').innerHTML = notif.args.nbre1;
                $('nbr2').innerHTML = notif.args.nbre2; 
                $('nbr3').innerHTML = notif.args.nbre3; 
                $('nbr4').innerHTML = notif.args.nbre4; 
                $('nbr5').innerHTML = notif.args.nbre5;

            },

            



   });             
});


