<?php
 /**
  *------
  * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
  * Biomos implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
  * 
  * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
  * See http://en.boardgamearena.com/#!doc/Studio for more information.
  * -----
  * 
  * biomos.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );


class Biomos extends Table
{
	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();
        
        self::initGameStateLabels( array( 
            "selected" => 10,
            "event" => 11,
            "game_mode" => 100,

        ) ); 
        
        $this->terrains = self::getNew( "module.common.deck" );
        $this->terrains->init( "terrain" );
        $this->biomes= self::getNew( "module.common.deck" );
        $this->biomes->init( "biome" );
        $this->bigbiomes= self::getNew( "module.common.deck" );
        $this->bigbiomes->init( "bigbiome" );


	}
	
    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "biomos";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";
        }
        $sql .= implode( ',', $values );
        self::DbQuery( $sql );
        self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();
        
        /************ Start the game initialization *****/

        self::initStat( 'player', 'validate_biome', 0 ); 
        self::initStat( 'player', 'points_biome', 0 );
        self::initStat( 'player', 'validate_bigbiome', 0 );
        self::initStat( 'player', 'points_bigbiome', 0 );
        self::initStat( 'player', 'points_moon', 0 );
        self::initStat( 'player', 'points_board', 0 );


        // creation du sac

        $terrain = array(
            array( 'type' => 1, 'type_arg' => 0, 'nbr' => 12 ),
            array( 'type' => 2, 'type_arg' => 0, 'nbr' => 12 ),
            array( 'type' => 3, 'type_arg' => 0, 'nbr' => 12 ),
            array( 'type' => 4, 'type_arg' => 0, 'nbr' => 12 ),
            array( 'type' => 5, 'type_arg' => 0, 'nbr' => 12 )
        );
        $this->terrains->createCards( $terrain, 'deck' );
        $this->terrains->shuffle( 'deck' );

        $biomes = array();
        for ($i = 1; $i <= 30; $i++)
        {
            $biome[] = array( 'type' => $i, 'type_arg' => 0, 'nbr' => 1);
        }

        $this->biomes->createCards( $biome, 'deck' );
        $this->biomes->shuffle( 'deck' );

        $bigbiomes = array();
        for ($i = 1; $i <= 10; $i++)
        {
            $bigbiome[] = array( 'type' => $i, 'type_arg' => 0, 'nbr' => 1);
        }

        $this->bigbiomes->createCards( $bigbiome, 'deck' );
        self::DbQuery( "UPDATE bigbiome set card_location = 'discard' WHERE card_type = 5" );
        $this->bigbiomes->shuffle( 'deck' );

        for($i=1; $i<=5; $i++)
        {

            $location = "terrainonboard";
            $this->terrains->pickCardForLocation( 'deck', $location, $i );
        
        }

        for($i=1; $i<=4; $i++)
        {

            $location = "biomeonboard";
            $this->biomes->pickCardForLocation( 'deck', $location, $i );
        
        }

        for($i=1; $i<=4; $i++)
        {

            $location = "bigbiomeonboard";
            $this->bigbiomes->pickCardForLocation( 'deck', $location, $i );
        
        }

        
        
        
        // biome supplementaire en debut de partie
        $gamemode = $this->gamestate->table_globals[100];
        if ($gamemode == 1)
        {
            $ordre = self::getObjectListFromDB( "SELECT player_id id, player_name name FROM player ORDER BY player_no ASC");
            $nbreplayers = count($ordre);

            if ($nbreplayers == 2)
            {
                $this->terrains->pickCardForLocation( 'deck', 'terrain_'.$ordre [1]['id'], 6 );
                self::notifyAllPlayers( 'message', clienttranslate('${player_name} starts with a land'),
                array(
                    
                    'player_name' => $ordre [1]['name'],
                                
                ));

            }  

            if ($nbreplayers == 3) 

            {
                $this->terrains->pickCardForLocation( 'deck', 'terrain_'.$ordre [2]['id'], 6 );
                self::notifyAllPlayers( 'message', clienttranslate('${player_name} starts with a land'),
                array(
                    
                    'player_name' => $ordre [2]['name'],
                                
                ));
            }

            if ($nbreplayers == 4)

            {
                $this->terrains->pickCardForLocation( 'deck', 'terrain_'.$ordre [2]['id'], 6 );
                $this->terrains->pickCardForLocation( 'deck', 'terrain_'.$ordre [3]['id'], 6 );
                self::notifyAllPlayers( 'message', clienttranslate('${player_name1} and ${player_name2} start with a land'),
                array(
                    
                    'player_name1' => $ordre [2]['name'],
                    'player_name2' => $ordre [3]['name'],
                                
                ));
            }
        }



       

        // Activate first player (which is in general a good idea :) )
        
        $this->activeNextPlayer();
        
        /************ End of the game initialization *****/
    }

///////////////////////////////////////////////
////////////////////////    getAllDatas: 
//////////////////
    
    protected function getAllDatas()
    {
        $result = array();
    
        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!
        $player_id = self::getActivePlayerId();
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );
        $result['terrainonboard'] = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM terrain WHERE card_location = 'terrainonboard' " );
        $result['terrainonplayer'] = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location location, card_location_arg position FROM terrain WHERE card_location != 'terrainonboard' AND card_location != 'deck' " );
        $result['biomeonboard'] = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM biome WHERE card_location = 'biomeonboard' " );
        $result['bigbiomeonboard'] = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM bigbiome WHERE card_location = 'bigbiomeonboard' " );
        $result['nbreplayers'] = count ($result['players']);
        $result['gamemode'] = $this->gamestate->table_globals[100];

        $result['nbre1'] = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '1' AND card_location = 'deck'"));
        $result['nbre2'] = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '2' AND card_location = 'deck'"));
        $result['nbre3'] = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = 'deck'"));
        $result['nbre4'] = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '4' AND card_location = 'deck'"));
        $result['nbre5'] = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '5' AND card_location = 'deck'"));
        
        
        if ($this->gamestate->table_globals[100] == 2)
        {
        $result['board'] = self::getObjectListFromDB( "SELECT player_id id, player_board board FROM player WHERE player_board !='NULL'" );
        }
        
        
        

        $result['biomename'] = $this->listbiomes;

        $result['bigbiomename'] = $this->listbigbiomes;


        if ($this->gamestate->table_globals[100] == 1)
        {
            $result['moon']= array();
            $listplayers = self::getObjectListFromDB("SELECT player_id id FROM player", true);
            
            
            foreach($listplayers as $player)
        {
            $typelune = self::getUniqueValueFromDB("SELECT card_type type FROM terrain WHERE card_location_arg = '13' AND card_location = CONCAT('terrain_', '{$player}')");
            
            $listterrainsametype = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '{$typelune}' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16'");
            $nombre = count($listterrainsametype);
            $scorelune = 3*$nombre;
            $result['moon'][$player][] = $scorelune;
            $result['moon'][$player][] = $typelune;
            

        }
        }

        if ($this->gamestate->table_globals[100] == 2)
        {
            $result['moon']= array();
            $listplayers = self::getObjectListFromDB("SELECT player_id id FROM player", true);
            
            
            foreach($listplayers as $player)
        {
            $typelune = self::getUniqueValueFromDB("SELECT card_type type FROM terrain WHERE card_location_arg = '13' AND card_location = CONCAT('terrain_', '{$player}')");
            
            $listterrainsametype = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '{$typelune}' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'");
            $nombre = count($listterrainsametype);
            $scorelune = 3*$nombre;
            $result['moon'][$player][] = $scorelune;
            $result['moon'][$player][] = $typelune;
            

        }
        }

        if ($this->gamestate->table_globals[100] == 2)
        {
        $result['scoreboard']= array();
        $listplayers = self::getObjectListFromDB("SELECT player_id id FROM player", true);
        foreach($listplayers as $player)
        {
            $typeboard = self::getUniqueValueFromDB("SELECT player_board board FROM player WHERE player_id = '{$player}'");
            $result['scoreboard'][$player][] = $typeboard;

            if ($typeboard == NULL)
            {
                $score = 0;
                $result['scoreboard'][$player][] = $score;


            }

            if ($typeboard == '1')
            {
                $nbre = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player}') AND card_type = '3' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                $result['scoreboard'][$player][] = $nbre;
                $result['scoreboard'][$player][] = $nbre;


            }
            if ($typeboard == '2')
            {
                $nbre = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player}') AND card_type = '3' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                $nbre2 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player}') AND card_type = '4' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                $result['scoreboard'][$player][] = $nbre;
                $result['scoreboard'][$player][] = $nbre2;


            }
            if ($typeboard == '3')
            {
                $nbre = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player}') AND card_type = '3' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                $nbre2 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player}') AND card_type = '2' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                $result['scoreboard'][$player][] = $nbre;
                $result['scoreboard'][$player][] = $nbre2;


            }
            if ($typeboard == '4')
            {
                $nbre = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player}') AND card_type = '3' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                $nbre2 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player}') AND card_type = '5' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                $result['scoreboard'][$player][] = $nbre;
                $result['scoreboard'][$player][] = $nbre2;


            }

        }
        }




        $result['scorebiome']= array();
        $result['scorebigbiome']= array();
        $listplayers = self::getObjectListFromDB("SELECT player_id id FROM player", true);
            
            
            foreach($listplayers as $player)
        {
            $result['scorebiome'][$player][] = $this->getStat( 'points_biome', $player );
            $result['scorebigbiome'][$player][] = $this->getStat( 'points_bigbiome', $player );
            
        }
        
        
        
        
        
        return $result;
    }

//////////////////////////////////////////////////////////////////////////////
///////////////////////    getGameProgression: 
/////////////////
    


    function getGameProgression()
    {
        // TODO: compute and return the game progression
        $nbrejoueur = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));
        $nbreterrain = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location != 'deck' AND card_location != 'terrainonboard'", true ));

        if ($this->gamestate->table_globals[100] == 1)
        {
            return floor(100 * $nbreterrain / (13*$nbrejoueur));
        }

        if ($this->gamestate->table_globals[100] == 2)
        {
            return floor(100 * $nbreterrain / (15*$nbrejoueur));
        }

        
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    function GameMode()  
    {
        $result = $this->gamestate->table_globals[100];
        return $result;
    }

    function getPlayerRelativePositions()  // permet de mettre dans view.php les joueurs dans l'ordre de la base de données et de positionner le current player en haut avec les autres joueurs dans l'ordre du tour
    {
        $result = array();
        
        $players = self::loadPlayersBasicInfos();
        $nextPlayer = self::createNextPlayerTable(array_keys($players)); //met joueurs dans l'ordre du tour au niveau de l'affichage à droite
        
        $current_player = self::getCurrentPlayerId();
        
        if(!isset($nextPlayer[$current_player])) {
            // Spectator mode: prend la vue du premier joueur de la liste
            $player_id = $nextPlayer[0];
        }
        else {
            // Normal mode: current player est premier de la liste puis les autres dans l ordre de la base de données player
            $player_id = $current_player;
        }
        $result[] = $player_id;
        
        for($i=1; $i<count($players); $i++) {
            $player_id = $nextPlayer[$player_id];
            $result[] = $player_id;
        }
        return $result;
    }

    function getLogsType( $type ) 
    {
		if($type == 1)
        {return "<div class='type_icon1' title=''></div>";}
        if($type == 2)
        {return "<div class='type_icon2' title=''></div>";}
        if($type == 3)
        {return "<div class='type_icon3' title=''></div>";}
        if($type == 4)
        {return "<div class='type_icon4' title=''></div>";}
        if($type == 5)
        {return "<div class='type_icon5' title=''></div>";}

    }

    function getLogsBiome( $biometype ) 
    {
		if($biometype <=15)
        {
        return "<div style='text-align: center;'><div class='biome' title='' style='margin: 0 auto; background-position-x: " . ($biometype - 1) * (-100) . "%; background-position-y: 0%'></div></div>";
        }
        if($biometype >=16)
        {
        return "<div style='text-align: center;'><div class='biome' title='' style='margin: 0 auto; background-position-x: " . ($biometype - 16) * (-100) . "%; background-position-y: -100%'></div></div>";
        }

    }

    function getLogsBigBiome( $bigbiometype ) 
    {
		
        return "<div style='text-align: center;'><div class='bigbiome' title='' style='position: relative; margin: 0 auto; background-position-x: " . ($bigbiometype - 1) * (-100) . "%'></div></div>";
        

    }


    function genererCombinaisons($tableau, $index = 0) 
    {
        $combinaisons = []; // Tableau pour stocker les combinaisons
        
    
        // Appel de la fonction auxiliaire
        $this->genererCombinaisonsAux($tableau, $index, $combinaisons);
        
        // Retourne le tableau de combinaisons
        return $combinaisons;
    }

    // Fonction auxiliaire pour générer les combinaisons récursivement
    function genererCombinaisonsAux($tableau, $index, &$combinaisons)  // le &$combinaisons permet de recupérer $combinaisons dans la fonction "parent" function genererCombinaisons
    {
        // Si nous avons atteint la fin du tableau, ajoutons la combinaison actuelle à la liste
        if ($index == count($tableau)) {
            $combinaisons[] = $tableau;
        } else {
            // Si l'élément actuel est un zéro, nous le remplaçons par des valeurs de 1 à 5
            if ($tableau[$index] == '0') {
                for ($valeur = 1; $valeur <= 5; $valeur++) {
                    $tableau[$index] = strval($valeur);
                    // Appel récursif pour l'élément suivant du tableau
                    self::genererCombinaisonsAux($tableau, $index + 1, $combinaisons);
                    // Réinitialisation de la valeur pour l'itération suivante
                    $tableau[$index] = 0;
                }
            } else {
                // Si l'élément actuel n'est pas un zéro, passez à l'élément suivant
                self::genererCombinaisonsAux($tableau, $index + 1, $combinaisons);
            }
        }
    }

    function boardmoonscore()

    {
        $player_id = self::getActivePlayerId();


        if ($this->gamestate->table_globals[100] == 1)
        {
            
            $typelune = self::getUniqueValueFromDB("SELECT card_type type FROM terrain WHERE card_location_arg = '13' AND card_location = CONCAT('terrain_', '{$player_id}')");
            
            $listterrainsametype = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '{$typelune}' AND card_location = CONCAT('terrain_', '{$player_id}') AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16'");
            $nombre = count($listterrainsametype);
            $scorelune = 3*$nombre;

            
        }

        if ($this->gamestate->table_globals[100] == 2)
        {
            
            $typelune = self::getUniqueValueFromDB("SELECT card_type type FROM terrain WHERE card_location_arg = '13' AND card_location = CONCAT('terrain_', '{$player_id}')");
            
            $listterrainsametype = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '{$typelune}' AND card_location = CONCAT('terrain_', '{$player_id}') AND card_location_arg != '13' AND card_location_arg != '16'");
            $nombre = count($listterrainsametype);
            $scorelune = 3*$nombre;

            
        }



        $this->notifyAllPlayers( "boardmoonscore", "",
                    array(
    
                        'type' => $typelune,
                        'score' => $scorelune,
                        'id' => $player_id,
                
                    )
                    );

        $this->setStat($scorelune, 'points_moon', $player_id);
        $newscore = $this->GetStat('points_bigbiome', $player_id) + $this->GetStat('points_biome', $player_id) + $this->GetStat('points_moon', $player_id) + $this->GetStat('points_board', $player_id);
        self::DbQuery( "UPDATE player set player_score = {$newscore} WHERE player_id = '{$player_id}'" );
        $newscoreplayers = self::getCollectionFromDb( "SELECT player_id, player_score FROM player", true );

        $this->notifyAllPlayers( "moonscore", '',
                    array(
    
                        'newscore' => $newscoreplayers,
                                                
                
                    )
                    );

    }

    function boardscore()

    {
        $player_id = self::getActivePlayerId();


        if ($this->gamestate->table_globals[100] == 2)
        {
            
            $typeboard = self::getUniqueValueFromDB("SELECT player_board board FROM player WHERE player_id = '{$player_id}'");
            
            if ($typeboard == NULL)
            {
                $score = 0;
                
            }
            
            if ($typeboard == '1')
            {
                $nbre = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player_id}') AND card_type = '3' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                //$score = 2*$nbre;
                $nbre2 = $nbre;
                
            }

            if ($typeboard == '2')
            {
                $nbre = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player_id}') AND card_type = '3' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                $nbre2 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player_id}') AND card_type = '4' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                //$score = $nbre+ $nbre2;
                
            }

            if ($typeboard == '3')
            {
                $nbre = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player_id}') AND card_type = '3' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                $nbre2 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player_id}') AND card_type = '2' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                //$score = $nbre+ $nbre2;
                
            }

            if ($typeboard == '4')
            {
                $nbre = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player_id}') AND card_type = '3' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                $nbre2 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', '{$player_id}') AND card_type = '5' AND card_location_arg != '13' AND card_location_arg != '16'", true ));
                //$score = $nbre+ $nbre2;
                
            }
            
            


            $this->notifyAllPlayers( "boardscore", "",
                    array(
    
                        'score1' => $nbre,
                        'score2' => $nbre2,
                        'id' => $player_id,
                        'typeboard' => $typeboard,
                
                    )
                    );

            $scoreboard = $nbre + $nbre2;
            $this->setStat($scoreboard, 'points_board', $player_id);
            $newscore = $this->GetStat('points_bigbiome', $player_id) + $this->GetStat('points_biome', $player_id) + $this->GetStat('points_moon', $player_id) + $this->GetStat('points_board', $player_id);
            self::DbQuery( "UPDATE player set player_score = {$newscore} WHERE player_id = '{$player_id}'" );
            $newscoreplayers = self::getCollectionFromDb( "SELECT player_id, player_score FROM player", true );
    
            $this->notifyAllPlayers( "moonscore", '',
                        array(
        
                            'newscore' => $newscoreplayers,
                                                    
                    
                        )
                        );

            
        }

    }
    


//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 

    
    function actSelect($cible_id )
    {
        self::checkAction( 'selectplace' );
        $player_id = self::getActivePlayerId();
        $terrain_id = $this->getGameStateValue('selected');
        $move_id = 'token_'.$terrain_id;
        $a = explode("_", $cible_id)[1]; 
        $b = explode("_", $cible_id)[2];
        $c = explode("_", $cible_id)[0];
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM terrain WHERE card_id = {$terrain_id}");

        if($type == 1)
        {
            
        }
        

        $position_id = $c.'_'.$a;
        $location_arg = $b;

         
        if($b != 13)
        {
        $this->terrains->moveCard( $terrain_id, $position_id, $location_arg );

        $this->notifyAllPlayers( "move", clienttranslate('${player_name} takes ${nametype} ${type} and places it around the planet'),
        array(

            'i18n' => array( 'nametype' ),
            'mobile' => $move_id,
            'parent' => $cible_id,
            'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
            'type' => self::getLogsType($type),
            'nametype' => $this->types[$type],
            
        )
        );
        }

        if($b == 13)
        {
        $this->terrains->moveCard( $terrain_id, $position_id, $location_arg );

        $this->notifyAllPlayers( "move", clienttranslate('${player_name} takes ${nametype} ${type} and forms the moon'),
        array(

            'i18n' => array( 'nametype' ),
            'mobile' => $move_id,
            'parent' => $cible_id,
            'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
            'type' => self::getLogsType($type),
            'nametype' => $this->types[$type],
            
        )
        );
        }
        
        $terrainonplayeractive = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '16'" );
        $nbre = count($terrainonplayeractive);

        $this->setGameStateValue('selected', 0);

        $this->boardmoonscore();
        

        $gamemode = $this->gamestate->table_globals[100];

        if($gamemode == 1)
        {
            if ($b == 13 && $terrainonplayeractive != NULL)
            {
                $this->gamestate->nextState( 'moon' );
            }

            if ($b == 13 && $terrainonplayeractive == NULL)
            {
                $this->gamestate->nextState( 'next' );
            }

            if ($b != 13)
            {
                $testbiome = $this->argPlayerBiome();
                if (empty($testbiome['BiomeValide']) && empty($testbiome['BigBiomeValide'])) 
                {
                    $this->gamestate->nextState( 'next' );
                }

                else
                {
                    $this->gamestate->nextState( 'biome' );
                }
            }
        }

        if($gamemode == 2)
        {
            $this->boardscore();


            if ($b == 13 && $terrainonplayeractive != NULL)
            {
                $this->gamestate->nextState( 'moon' );
            }

            if ($b == 13 && $terrainonplayeractive == NULL)
            {
                $this->gamestate->nextState( 'next' );
            }

            if ($b != 13)
            {
                $testbiome = $this->argPlayerBiome();
                $testevenement = $this->argPlayerEvenement();
                
                if (empty($testbiome['BiomeValide']) && empty($testbiome['BigBiomeValide']) && empty($testevenement['Event1']) && empty($testevenement['Event2']) && empty($testevenement['Event3']) && empty($testevenement['Event4'])) 
                {
                    $this->gamestate->nextState( 'next' );
                }

                if ((!empty($testbiome['BiomeValide']) || !empty($testbiome['BigBiomeValide'])) && empty($testevenement['Event1']) && empty($testevenement['Event2']) && empty($testevenement['Event3']) && empty($testevenement['Event4'])) 
                {
                    $this->gamestate->nextState( 'biome' );
                }

                if (!empty($testevenement['Event1']) || !empty($testevenement['Event2']) || !empty($testevenement['Event3']) || !empty($testevenement['Event4'])) 
                {
                    $this->gamestate->nextState( 'evenement' );
                }
            }
        }

        
         
            
            
        
        

    }

    function actTurnSelect($move_id)
    {
        self::checkAction( 'selectland' );
        $terrain_id = explode("_", $move_id)[1];        
        $this->setGameStateValue('selected', $terrain_id);
        
        $this->gamestate->nextState( 'next' );
    }


    function actMoonSelect($move_id)
    {
        self::checkAction( 'selectmoon' );
        $terrain_id = explode("_", $move_id)[1];
        $player_id = self::getActivePlayerId();
        $position_id = 'terrain_'.$player_id;
        $this->terrains->moveCard( $terrain_id, $position_id, 16 );
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM terrain WHERE card_id = {$terrain_id}");

        $this->notifyAllPlayers( "moonselect", clienttranslate('${player_name} selects ${nametype} ${type}'),
        array(

            'i18n' => array( 'nametype' ),
            'mobile' => $move_id,
            'parent' => 'terrain_'.$player_id.'_16',
            'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
            'type' => self::getLogsType($type),
            'nametype' => $this->types[$type],
            
        )
        );
        $this->gamestate->nextState( 'select' );
    }

    function actMoonMove ($move_id)
    {
        self::checkAction( 'selectlandmoon' );
        $player_id = self::getActivePlayerId();
        $terrain_player = explode("_", $move_id)[1];
        $terrain_pos = explode("_", $move_id)[2];
        $position_id = 'terrain_'.$terrain_player;
        $tokenid = self::getUniqueValueFromDB( "SELECT card_id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = 16 " );
        $token = 'token_'.$tokenid;
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM terrain WHERE card_id = {$tokenid}");
        $this->terrains->moveCard( $tokenid, $position_id, $terrain_pos );

        $this->notifyAllPlayers( "moonmove", clienttranslate('${player_name} moves ${nametype} ${type}'),
        array(

            'i18n' => array( 'nametype' ),
            'mobile' => $token,
            'parent' => 'terrain_'.$player_id.'_'.$terrain_pos,
            'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
            'type' => self::getLogsType($type),
            'nametype' => $this->types[$type],
            
        )
        );
        
        $testbiome = $this->argPlayerBiome();
            if (empty($testbiome['BiomeValide']) && empty($testbiome['BigBiomeValide'])) 
            {
                $this->gamestate->nextState( 'next' );
            }

            else
            {
                $this->gamestate->nextState( 'biome' );
            }
    }

    function actNotMove()
    {
        self::checkAction( 'selectmoon' );
        $player_id = self::getActivePlayerId();
        $this->notifyAllPlayers( "infopassmoon", clienttranslate('${player_name} does not move Land'),
        array(

            'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
            
            
        )
        );
        
        $testbiome = $this->argPlayerBiome();
            if (empty($testbiome['BiomeValide']) && empty($testbiome['BigBiomeValide'])) 
            {
                $this->gamestate->nextState( 'next' );
            }

            else
            {
                $this->gamestate->nextState( 'biome' );
            }

    }


    function actNotValidate()
    {
        self::checkAction( 'selectbiome' );
        $player_id = self::getActivePlayerId();
        $this->notifyAllPlayers( "infopassbiome", clienttranslate('${player_name} does not validate Biome'),
        array(

            'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
            
            
        )
        );
        $this->gamestate->nextState( 'next' );
    }

    function actCancel()
    {
        self::checkAction( 'cancel' );
        
        $this->gamestate->nextState( 'cancel' );
    }


    function actSelectFleche($fleche_id )
    {
        $this->gamestate->checkPossibleAction('fleches');
        $player_id = self::getCurrentPlayerId();
        $terrainonplayeractive = self::getCollectionFromDB( "SELECT card_location_arg position, card_id id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16'" );
        $nbre = count($terrainonplayeractive);
        $caseoccupee = self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id})", true );
        
        
        if ($nbre == 12)
        {
            $this->gamestate->nextState( 'same' );
        }

        else
        {
            $pos = explode("_", $fleche_id)[2];
            $dir = explode("_", $fleche_id)[3];
            

            if($dir == 'd')
            {   
                $posmove = array();
                for($i=$pos; $i<=12; $i++)
                {
                    if(in_array($i, $caseoccupee))
                    {
                        $posmove[] = $i;
                    }
                    else{
                        break;
                    }

                }

                
                foreach($posmove as $variable)
                {
                
                $terrain_id = $terrainonplayeractive[$variable]['id'];
                $position_id = 'terrain_'.$player_id;
                $location_arg = $variable + 1;
                $this->terrains->moveCard( $terrain_id, $position_id, $location_arg );

                    $this->notifyAllPlayers( "movefleche", "",
                    array(
    
                        'mobile' => 'token_'.$terrain_id,
                        'parent' => $position_id.'_'.$location_arg,
                
                    )
                    );

                }

            }   

            if($dir == 'g')
            {   
                $posmove = array();
                for($i=$pos; $i>=1; $i--)
                {
                    if(in_array($i, $caseoccupee))
                    {
                        $posmove[] = $i;
                    }
                    else{
                        break;
                    }

                }

                
                foreach($posmove as $variable)
                {
                
                $terrain_id = $terrainonplayeractive[$variable]['id'];
                $position_id = 'terrain_'.$player_id;
                $location_arg = $variable - 1;
                $this->terrains->moveCard( $terrain_id, $position_id, $location_arg );

                    $this->notifyAllPlayers( "movefleche", "",
                    array(
    
                        'mobile' => 'token_'.$terrain_id,
                        'parent' => $position_id.'_'.$location_arg,
                
                    )
                    );

                }

                
            }
            self::notifyAllPlayers( 'simplePause', '', [ 'time' => 300] );
            $this->gamestate->nextState( 'same' );
        }


    }

    
    function actValidateBiome( $biome )
    {

        self::checkAction( 'selectbiome' );
        $player_id = self::getActivePlayerId();
        $location_arg = 0;
        $biomeid = explode("_",$biome)[1];
        $typebiome = explode("_",$biome)[0];


        if ($typebiome == 'biome')
        {
        $pos = self::getUniqueValueFromDB("SELECT card_location_arg FROM biome WHERE card_id = {$biomeid}");
        $biometype = self::getUniqueValueFromDB("SELECT card_type FROM biome WHERE card_id = {$biomeid}");
        $score = $this->listbiomes[$biometype]['points'];

        
        
        
        $this->biomes->moveCard( $biomeid, 'discard', $location_arg );
        
        
        $location = "biomeonboard";
        
        $this->biomes->pickCardForLocation( 'deck', $location, $pos );

        $newid = self::getUniqueValueFromDB("SELECT card_id FROM biome WHERE card_location = 'biomeonboard' AND card_location_arg = {$pos}");
        $newtype = self::getUniqueValueFromDB("SELECT card_type FROM biome WHERE card_location = 'biomeonboard' AND card_location_arg = {$pos}");

        self::DbQuery( "UPDATE player set player_score = player_score + {$score} WHERE player_id = '{$player_id}'" );
        $this->incStat($score, 'points_biome', $player_id);
        $this->incStat(1, 'validate_biome', $player_id);
        $scorebiomeplayer = $this->GetStat('points_biome', $player_id);

        $newscore = self::getCollectionFromDb( "SELECT player_id, player_score FROM player", true );

        $this->notifyAllPlayers( "validatebiome", clienttranslate('${player_name} validates the Biome "${name}" and gains ${score} points'),
                    array(
                        'i18n' => array( 'name' ),
                        'mobile' => $biome,
                        'newid' => $newid,
                        'newtype' => $newtype,
                        'position' => $pos,
                        'newscore' => $newscore,
                        'player_id' => $player_id,
                        'score' => $score,
                        'scorebiomeplayer' => $scorebiomeplayer,
                        'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
                        'name' => $this->listbiomes[$biometype]['name'],
                        //'type' => self::getLogsBiome($biometype),

                        
                
                    )
                    );

        }

        if ($typebiome == 'bigbiome')
        {
        
        $biometype = self::getUniqueValueFromDB("SELECT card_type FROM bigbiome WHERE card_id = {$biomeid}");
        $score = $this->listbigbiomes[$biometype]['points'];

        
        
        
        $this->bigbiomes->moveCard( $biomeid, 'discard', $location_arg );
        
        
        
        self::DbQuery( "UPDATE player set player_score = player_score + {$score} WHERE player_id = '{$player_id}'" );
        $this->incStat($score, 'points_bigbiome', $player_id);
        $this->incStat(1, 'validate_bigbiome', $player_id);
        $scorebigbiomeplayer = $this->GetStat('points_bigbiome', $player_id);
        $newscore = self::getCollectionFromDb( "SELECT player_id, player_score FROM player", true );

        $this->notifyAllPlayers( "validatebigbiome", clienttranslate('${player_name} validates the giant Biome "${name}" and gains ${score} points'),
                    array(
                        'i18n' => array( 'name' ),
                        'mobile' => $biome,
                        'newscore' => $newscore,
                        'player_id' => $player_id,
                        'score' => $score,
                        'scorebigbiomeplayer' => $scorebigbiomeplayer,
                        'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
                        'name' => $this->listbigbiomes[$biometype]['name'],
                        //'type' => self::getLogsBigBiome($biometype),
                
                    )
                    );

        }


        self::notifyAllPlayers( 'simplePause', '', [ 'time' => 2100] );
        $this->gamestate->nextState( 'next' );

    }

    function actValidateBoard ( $board )
    {

        self::checkAction( 'select' );
        $player_id = self::getActivePlayerId();
        $nboard = explode("_",$board)[1];
        if($nboard == '1')
        {
            $nameboard = self::_("Forest");

        }
        if($nboard == '2')
        {
            $nameboard = self::_("Mountain");

        }
        if($nboard == '3')
        {
            $nameboard = self::_("Desert");

        }
        if($nboard == '4')
        {
            $nameboard = self::_("Ice");

        }
        self::DbQuery( "UPDATE player set player_board = {$nboard} WHERE player_id = '{$player_id}'" );
        $this->notifyAllPlayers( "validateboard", clienttranslate('${player_name} has chosen the ${nameboard} board'),
                    array(
    
                        'mobile' => $board,
                        'player_id' => $player_id,
                        'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
                        'nameboard' => $nameboard,                 
                
                    )
                    );

        

        $this->boardscore();
        self::notifyAllPlayers( 'simplePause', '', [ 'time' => 600] );
        $this->gamestate->nextState( 'next' );


    }

    function actNotEvent()
    {
        self::checkAction( 'selectevenement' );
        $player_id = self::getActivePlayerId();
        $this->notifyAllPlayers( 'message', clienttranslate('${player_name} does not trigger an event'),
        array(

            'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
            
            
        )
        );
        
        $testbiome = $this->argPlayerBiome();
            if (empty($testbiome['BiomeValide']) && empty($testbiome['BigBiomeValide'])) 
            {
                $this->gamestate->nextState( 'next' );
            }

            else
            {
                $this->gamestate->nextState( 'biome' );
            }
            
    }

    function actEvent1()
    {
        self::checkAction( 'selectevenement' );
        $this->setGameStateValue('event', 1);
        $this->gamestate->nextState( 'select' );
          
    }

    function actEvent2()
    {
        self::checkAction( 'selectevenement' );
        $this->setGameStateValue('event', 2);
        $this->gamestate->nextState( 'select' );
          
    }

    function actEvent3()
    {
        self::checkAction( 'selectevenement' );
        $this->setGameStateValue('event', 3);
        $this->gamestate->nextState( 'select' );
          
    }

    function actEvent4()
    {
        self::checkAction( 'selectevenement' );
        $this->setGameStateValue('event', 4);
        $this->gamestate->nextState( 'select' );
          
    }

    function actEventSelect($cible)
    {
        self::checkAction( 'selectlandevenement' );
        $event = $this->getGameStateValue('event');
        $player_id = self::getActivePlayerId();
        $tokenselectedid = explode("_",$cible)[1];
        $locationselected = self::getUniqueValueFromDB( "SELECT card_location_arg position FROM terrain WHERE card_id = '{$tokenselectedid}'");
        $typeselected = self::getUniqueValueFromDB( "SELECT card_type type FROM terrain WHERE card_id = '{$tokenselectedid}'");
        

        if ($event == 1)
        {
            $idtokenrecup = self::getUniqueValueFromDB( "SELECT MIN(card_id) FROM terrain WHERE card_type = '3' AND card_location ='deck'");
            $locationdeckrecup = self::getUniqueValueFromDB( "SELECT card_location_arg location FROM terrain WHERE card_id = '{$idtokenrecup}'");
            $typerecup = self::getUniqueValueFromDB( "SELECT card_type type FROM terrain WHERE card_id = '{$idtokenrecup}'");
            self::DbQuery( "UPDATE terrain set card_location = 'deck' WHERE card_id = '{$tokenselectedid}'" );
            self::DbQuery( "UPDATE terrain set card_location_arg = {$locationdeckrecup} WHERE card_id = '{$tokenselectedid}'" );
            self::DbQuery( "UPDATE terrain set card_location = CONCAT('terrain_', {$player_id}) WHERE card_id = '{$idtokenrecup}'" );
            self::DbQuery( "UPDATE terrain set card_location_arg = {$locationselected} WHERE card_id = '{$idtokenrecup}'" );
            

            $nbre1 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '1' AND card_location = 'deck'"));
            $nbre2 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '2' AND card_location = 'deck'"));
            $nbre3 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = 'deck'"));
            $nbre4 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '4' AND card_location = 'deck'"));
            $nbre5 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '5' AND card_location = 'deck'"));
            

            $this->notifyAllPlayers( "movecroise", clienttranslate('${player_name} triggers the event: Irrigate a Desert. ${player_name} transforms ${nameselected} ${iconselected} into ${namerecup} ${iconrecup}'),
                    array(
                        'i18n' => array( 'nameselected', 'namerecup'),
                        'mobile1' => 'token_'.$tokenselectedid,
                        'parentlocation1' => 'terrain_'.$player_id,
                        'parentposition1' => $locationselected,
                        'mobile2' => $idtokenrecup,
                        'parent2' => 'pochon',
                        'type' => $typerecup,
                        'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
                        'nbre1' => $nbre1,
                        'nbre2' => $nbre2,
                        'nbre3' => $nbre3,
                        'nbre4' => $nbre4,
                        'nbre5' => $nbre5,
                        'nameselected' => $this->types[$typeselected],
                        'namerecup' => $this->types[$typerecup],
                        'iconselected' => self::getLogsType($typeselected),
                        'iconrecup' => self::getLogsType($typerecup),

                
                                         
                
                    )
                    );
        

            $this->terrains->shuffle( 'deck' );
            $this->boardmoonscore();
            $this->boardscore();
            $testbiome = $this->argPlayerBiome();
            if (empty($testbiome['BiomeValide']) && empty($testbiome['BigBiomeValide'])) 
            {
                $this->gamestate->nextState( 'next' );
            }

            else
            {
                $this->gamestate->nextState( 'biome' );
            }


        }

        if ($event == 2)
        {
            $idtokenrecup = self::getUniqueValueFromDB( "SELECT MIN(card_id) FROM terrain WHERE card_type = '5' AND card_location ='deck'");
            $locationdeckrecup = self::getUniqueValueFromDB( "SELECT card_location_arg location FROM terrain WHERE card_id = '{$idtokenrecup}'");
            $typerecup = self::getUniqueValueFromDB( "SELECT card_type type FROM terrain WHERE card_id = '{$idtokenrecup}'");
            self::DbQuery( "UPDATE terrain set card_location = 'deck' WHERE card_id = '{$tokenselectedid}'" );
            self::DbQuery( "UPDATE terrain set card_location_arg = {$locationdeckrecup} WHERE card_id = '{$tokenselectedid}'" );
            self::DbQuery( "UPDATE terrain set card_location = CONCAT('terrain_', {$player_id}) WHERE card_id = '{$idtokenrecup}'" );
            self::DbQuery( "UPDATE terrain set card_location_arg = {$locationselected} WHERE card_id = '{$idtokenrecup}'" );
        


            $nbre1 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '1' AND card_location = 'deck'"));
            $nbre2 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '2' AND card_location = 'deck'"));
            $nbre3 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = 'deck'"));
            $nbre4 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '4' AND card_location = 'deck'"));
            $nbre5 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '5' AND card_location = 'deck'"));
            

            $this->notifyAllPlayers( "movecroise", clienttranslate('${player_name} triggers the event: Freeze a Sea. ${player_name} transforms ${nameselected} ${iconselected} into ${namerecup} ${iconrecup}'),
                    array(
                        'i18n' => array( 'nameselected', 'namerecup'),
                        'mobile1' => 'token_'.$tokenselectedid,
                        'parentlocation1' => 'terrain_'.$player_id,
                        'parentposition1' => $locationselected,
                        'mobile2' => $idtokenrecup,
                        'parent2' => 'pochon',
                        'type' => $typerecup,
                        'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
                        'nbre1' => $nbre1,
                        'nbre2' => $nbre2,
                        'nbre3' => $nbre3,
                        'nbre4' => $nbre4,
                        'nbre5' => $nbre5,
                        'nameselected' => $this->types[$typeselected],
                        'namerecup' => $this->types[$typerecup],
                        'iconselected' => self::getLogsType($typeselected),
                        'iconrecup' => self::getLogsType($typerecup),
                
                                         
                
                    )
                    );
        

            $this->terrains->shuffle( 'deck' );
            $this->boardmoonscore();
            $this->boardscore();
            $testbiome = $this->argPlayerBiome();
            if (empty($testbiome['BiomeValide']) && empty($testbiome['BigBiomeValide'])) 
            {
                $this->gamestate->nextState( 'next' );
            }

            else
            {
                $this->gamestate->nextState( 'biome' );
            }


        }

        if ($event == 3)
        {

            $idtokenrecup = self::getUniqueValueFromDB( "SELECT MIN(card_id) FROM terrain WHERE card_type = '2' AND card_location ='deck'");
            $locationdeckrecup = self::getUniqueValueFromDB( "SELECT card_location_arg location FROM terrain WHERE card_id = '{$idtokenrecup}'");
            $typerecup = self::getUniqueValueFromDB( "SELECT card_type type FROM terrain WHERE card_id = '{$idtokenrecup}'");
            self::DbQuery( "UPDATE terrain set card_location = 'deck' WHERE card_id = '{$tokenselectedid}'" );
            self::DbQuery( "UPDATE terrain set card_location_arg = {$locationdeckrecup} WHERE card_id = '{$tokenselectedid}'" );
            self::DbQuery( "UPDATE terrain set card_location = CONCAT('terrain_', {$player_id}) WHERE card_id = '{$idtokenrecup}'" );
            self::DbQuery( "UPDATE terrain set card_location_arg = {$locationselected} WHERE card_id = '{$idtokenrecup}'" );
        


            $nbre1 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '1' AND card_location = 'deck'"));
            $nbre2 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '2' AND card_location = 'deck'"));
            $nbre3 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = 'deck'"));
            $nbre4 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '4' AND card_location = 'deck'"));
            $nbre5 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '5' AND card_location = 'deck'"));
            

            $this->notifyAllPlayers( "movecroise", clienttranslate('${player_name} triggers the event: Expand a Desert. ${player_name} transforms ${nameselected} ${iconselected} into ${namerecup} ${iconrecup}'),
                    array(
                        'i18n' => array( 'nameselected', 'namerecup'),
                        'mobile1' => 'token_'.$tokenselectedid,
                        'parentlocation1' => 'terrain_'.$player_id,
                        'parentposition1' => $locationselected,
                        'mobile2' => $idtokenrecup,
                        'parent2' => 'pochon',
                        'type' => $typerecup,
                        'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
                        'nbre1' => $nbre1,
                        'nbre2' => $nbre2,
                        'nbre3' => $nbre3,
                        'nbre4' => $nbre4,
                        'nbre5' => $nbre5,
                        'nameselected' => $this->types[$typeselected],
                        'namerecup' => $this->types[$typerecup],
                        'iconselected' => self::getLogsType($typeselected),
                        'iconrecup' => self::getLogsType($typerecup),
                
                                         
                
                    )
                    );
        

            $this->terrains->shuffle( 'deck' );
            $this->boardmoonscore();
            $this->boardscore();
            $testbiome = $this->argPlayerBiome();
            if (empty($testbiome['BiomeValide']) && empty($testbiome['BigBiomeValide'])) 
            {
                $this->gamestate->nextState( 'next' );
            }

            else
            {
                $this->gamestate->nextState( 'biome' );
            }


        }

        if ($event == 4)
        {
            $listid = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location ='deck'", true );
            //$idtokenrecup = self::getUniqueValueFromDB( "SELECT card_id id FROM terrain WHERE card_location ='deck' ORDER BY RAND() LIMIT 1");
            $indice_aleatoire = array_rand($listid);
            $idtokenrecup = $listid[$indice_aleatoire];            
            $locationdeckrecup = self::getUniqueValueFromDB( "SELECT card_location_arg location FROM terrain WHERE card_id = '{$idtokenrecup}'");
            $typerecup = self::getUniqueValueFromDB( "SELECT card_type type FROM terrain WHERE card_id = '{$idtokenrecup}'");
            self::DbQuery( "UPDATE terrain set card_location = 'deck' WHERE card_id = '{$tokenselectedid}'" );
            self::DbQuery( "UPDATE terrain set card_location_arg = {$locationdeckrecup} WHERE card_id = '{$tokenselectedid}'" );
            self::DbQuery( "UPDATE terrain set card_location = CONCAT('terrain_', {$player_id}) WHERE card_id = '{$idtokenrecup}'" );
            self::DbQuery( "UPDATE terrain set card_location_arg = {$locationselected} WHERE card_id = '{$idtokenrecup}'" );

            $nbre1 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '1' AND card_location = 'deck'"));
            $nbre2 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '2' AND card_location = 'deck'"));
            $nbre3 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = 'deck'"));
            $nbre4 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '4' AND card_location = 'deck'"));
            $nbre5 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '5' AND card_location = 'deck'"));
            

            $this->notifyAllPlayers( "movecroise", clienttranslate('${player_name} triggers the event: Break an Ice. ${player_name} breaks ${nameselected} ${iconselected} and discovers ${namerecup} ${iconrecup}'),
                    array(
                        'i18n' => array( 'nameselected', 'namerecup'),
                        'mobile1' => 'token_'.$tokenselectedid,
                        'parentlocation1' => 'terrain_'.$player_id,
                        'parentposition1' => $locationselected,
                        'mobile2' => $idtokenrecup,
                        'parent2' => 'pochon',
                        'type' => $typerecup,
                        'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}"),
                        'nbre1' => $nbre1,
                        'nbre2' => $nbre2,
                        'nbre3' => $nbre3,
                        'nbre4' => $nbre4,
                        'nbre5' => $nbre5,
                        'nameselected' => $this->types[$typeselected],
                        'namerecup' => $this->types[$typerecup],
                        'iconselected' => self::getLogsType($typeselected),
                        'iconrecup' => self::getLogsType($typerecup),

                
                                         
                
                    )
                    );
        

            $this->terrains->shuffle( 'deck' );
            $this->boardmoonscore();
            $this->boardscore();
            $testbiome = $this->argPlayerBiome();
            if (empty($testbiome['BiomeValide']) && empty($testbiome['BigBiomeValide'])) 
            {
                $this->gamestate->nextState( 'next' );
            }

            else
            {
                $this->gamestate->nextState( 'biome' );
            }


        }







          
    }
    

    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    function argSelectBoard()
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["player"] = array();
        $board = self::getObjectListFromDB( "SELECT player_board FROM player WHERE player_board != 'NULL'", true );
        $player = self::getObjectListFromDB( "SELECT player_id FROM player WHERE player_board IS NULL", true );
        
        for ($i = 1; $i <= 4; $i++)
        {
            if (!in_array($i, $board))
            {
                $ret["selectable"][] = $i;
            }
        }
        
        $ret["player"] = $player;
        
        

        
        
        return $ret;

    }

   
    function argPlayerTurn()
    {
       
        $gamemode = $this->gamestate->table_globals[100];
        $ret = array();
        $ret["selectable"] = array();
        $ret["fleche"] = array();
        $player_id = self::getActivePlayerId();
        $terrainonboard = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM terrain WHERE card_location = 'terrainonboard'" );
        $terrainonplayeractive = self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id})", true );
        
        $listplayer = self::getObjectListFromDB( "SELECT player_id id FROM player", true );
        $allterrainonplayer = array();
        foreach($listplayer as $a)
        {
            $allterrainonplayer[] = [
                'id' => $a,
                'pos' => self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$a})", true ),
            ];
        }

        if($gamemode == 1)
        {
        foreach($terrainonboard as $variable)
        {
            for($i=1; $i<=13; $i++)
                {
                if(!in_array($i, $terrainonplayeractive))
                {
                    $token = 'token_'.$variable['id'];
                    $ret["selectable"][$token][] = 'terrain_'.$player_id.'_'.$i;
                }
                }     
        }
        }

        if($gamemode == 2)
        {
        foreach($terrainonboard as $variable)
        {
            for($i=1; $i<=15; $i++)
                {
                if(!in_array($i, $terrainonplayeractive))
                {
                    $token = 'token_'.$variable['id'];
                    $ret["selectable"][$token][] = 'terrain_'.$player_id.'_'.$i;
                }
                }     
        }
        }

        
        foreach($allterrainonplayer as $variable2)
        {
            foreach($variable2['pos'] as $variable3)
            {
        $testg = 0;
        $testd = 0;
            for($j=1; $j<=$variable3; $j++)
            {
                if(!in_array($j, $variable2['pos']))
                {
                $testg = 1;
                }
            }
            if($testg == 1)
            {
            $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_g';
            }
        
            for($j=$variable3; $j<=12; $j++)
            {
                if(!in_array($j, $variable2['pos']))
                {
                $testd = 1;
                }
            }
            if($testd == 1)
            {
            $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_d';
            }
        }

        }

        $gamemode = $this->gamestate->table_globals[100];
        if($gamemode == 2)
        {
            $ret["board"] = array();
            $ret["gamemode"] = array();
            $ret["gamemode"][] = 2;
            $ret["board"][] = self::getObjectListFromDB( "SELECT player_id id, player_board board FROM player");
            
        }

        $nbreplayers = count (self::getCollectionFromDb("SELECT player_id id FROM player "));
        $ret["nbreplayers"][] = $nbreplayers;

        
        /*$ret["playeractive"] = array();
        $ret["playeractive"][] = $player_id;*/
        
        return $ret;
        
    }

    function argPlayerTurnPlace()
    {
        $gamemode = $this->gamestate->table_globals[100];
        $terrain_id = $this->getGameStateValue('selected');
        $ret = array();
        $ret["selected"][] = 'token_'.$terrain_id;
        $ret["selectable"] = array();
        $ret["fleche"] = array();
        $player_id = self::getActivePlayerId();
        $terrainonboard = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM terrain WHERE card_location = 'terrainonboard'" );
        $terrainonplayeractive = self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id})", true );
        
        $listplayer = self::getObjectListFromDB( "SELECT player_id id FROM player", true );
        $allterrainonplayer = array();
        foreach($listplayer as $a)
        {
            $allterrainonplayer[] = [
                'id' => $a,
                'pos' => self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$a})", true ),
            ];
        }

        if($gamemode == 1)
        {
        for($i=1; $i<=13; $i++)
                {
                if(!in_array($i, $terrainonplayeractive))
                {
                    
                    $ret["selectable"][] = 'terrain_'.$player_id.'_'.$i;
                }
                }
        } 
        
        if($gamemode == 2)
        {
        for($i=1; $i<=15; $i++)
                {
                if(!in_array($i, $terrainonplayeractive))
                {
                    
                    $ret["selectable"][] = 'terrain_'.$player_id.'_'.$i;
                }
                }
        } 
        

        
        foreach($allterrainonplayer as $variable2)
        {
            foreach($variable2['pos'] as $variable3)
            {
        $testg = 0;
        $testd = 0;
            for($j=1; $j<=$variable3; $j++)
            {
                if(!in_array($j, $variable2['pos']))
                {
                $testg = 1;
                }
            }
            if($testg == 1)
            {
            $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_g';
            }
        
            for($j=$variable3; $j<=12; $j++)
            {
                if(!in_array($j, $variable2['pos']))
                {
                $testd = 1;
                }
            }
            if($testd == 1)
            {
            $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_d';
            }
        }

        }

        /*$ret["playeractive"] = array();
        $ret["playeractive"][] = $player_id;*/

        return $ret;
        
    }
   

    function argPlayerMoon()
    {
       
        $ret = array();

        $ret["selectable"] = array();
        $player_id = self::getActivePlayerId();
        $terrainonplayeractive = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '16'" );
        $terrainoccupee = self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id})", true );
        
        $listplayer = self::getObjectListFromDB( "SELECT player_id id FROM player", true );
        $allterrainonplayer = array();
        foreach($listplayer as $a)
        {
            $allterrainonplayer[] = [
                'id' => $a,
                'pos' => self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$a})", true ),
            ];
        }


        foreach($terrainonplayeractive as $variable)
        {
            
                    $token = 'token_'.$variable['id'];
                    $ret["selectable"][] = $token;
                     
        }

        foreach($allterrainonplayer as $variable2)
        {
            foreach($variable2['pos'] as $variable3)
            {
        $testg = 0;
        $testd = 0;
            for($j=1; $j<=$variable3; $j++)
            {
                if(!in_array($j, $variable2['pos']))
                {
                $testg = 1;
                }
            }
            if($testg == 1)
            {
            $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_g';
            }
        
            for($j=$variable3; $j<=12; $j++)
            {
                if(!in_array($j, $variable2['pos']))
                {
                $testd = 1;
                }
            }
            if($testd == 1)
            {
            $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_d';
            }
        }

        }

        /*$ret["playeractive"] = array();
        $ret["playeractive"][] = $player_id;*/

        return $ret;
                
    
    }

    function argPlayerMoonSelect()
    {
        $gamemode = $this->gamestate->table_globals[100];
        $ret = array();
        $player_id = self::getActivePlayerId();
        $ret["selected"] = array();
        $ret["selected"][] = self::getUniqueValueFromDB( "SELECT card_id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = 16 " );
        $terrainoccupee = self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id})", true );
        
        $listplayer = self::getObjectListFromDB( "SELECT player_id id FROM player", true );
        $allterrainonplayer = array();
        foreach($listplayer as $a)
        {
            $allterrainonplayer[] = [
                'id' => $a,
                'pos' => self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$a})", true ),
            ];
        }

        if($gamemode == 1)
        {
            for($i=1; $i<=12; $i++)
                {
                if(!in_array($i, $terrainoccupee))
                {
                    
                    $ret["selectable"][] = 'terrain_'.$player_id.'_'.$i;
                }
                }
        } 
        
        if($gamemode == 2)
        {
            for($i=1; $i<=15; $i++)
                {
                if(!in_array($i, $terrainoccupee))
                {
                    
                    $ret["selectable"][] = 'terrain_'.$player_id.'_'.$i;
                }
                }
        }  
        

            foreach($allterrainonplayer as $variable2)
            {
                foreach($variable2['pos'] as $variable3)
                {
            $testg = 0;
            $testd = 0;
                for($j=1; $j<=$variable3; $j++)
                {
                    if(!in_array($j, $variable2['pos']))
                    {
                    $testg = 1;
                    }
                }
                if($testg == 1)
                {
                $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_g';
                }
            
                for($j=$variable3; $j<=12; $j++)
                {
                    if(!in_array($j, $variable2['pos']))
                    {
                    $testd = 1;
                    }
                }
                if($testd == 1)
                {
                $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_d';
                }
            }
    
            }

            /*$ret["playeractive"] = array();
            $ret["playeractive"][] = $player_id;*/

            return $ret;

    }



    function argPlayerBiome()
    {
        $gamemode = $this->gamestate->table_globals[100];      
        $ret = array();
        $ret['BiomeValide'] = array();
        $ret['BigBiomeValide'] = array();
        $biometype = array();
        $bigbiometype = array();
        $player_id = self::getActivePlayerId();

        if($gamemode == 1)
        {
        $typeterrainonplayeractive = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' ORDER BY card_location_arg ASC", true );
        $biomeonboard = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM biome WHERE card_location = 'biomeonboard'" );
        $bigbiomeonboard = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM bigbiome WHERE card_location = 'bigbiomeonboard'" );

        $countbiomeonboard = count($biomeonboard); // nbre de biome sur le terrain

        foreach($biomeonboard as $variable)
        {
            
            $i = $this->listbiomes[$variable['type']]['type'];

            $zeroPositions = array();
            foreach ($i as $key => $value) 
            {
                if ($value == '0') {
                    $zeroPositions[] = $key;
                }
            }
            $zeroCount = count($zeroPositions);

            if ($zeroCount == 0)
            {

            $lengthB = count($i);
            $lengthP = count($typeterrainonplayeractive);
                if ($lengthP >= $lengthB)
                {
                // Parcourir $typeterrainonplayeractive pour comparer les sous-séquences de longueur $lengthB
                    for ($j = 0; $j <= $lengthP - $lengthB; $j++) 
                    {
                        $subSequence = array_slice($typeterrainonplayeractive, $j, $lengthB);
                
                        // Vérifier si la sous-séquence correspond à $i
                        if ($subSequence === $i) 
                        {
                        
                        $ret['BiomeValide'][] = 'biome_'.$variable['id'];
                        }

                        
                    }
                }

            }

            if ($zeroCount > 0)
            {
                
                $copiebiome = $i;
                $combinaison = array();

                $combinaison = $this->genererCombinaisons($copiebiome);

                foreach ($combinaison as $combi)
                {

                    $lengthC = count($combi);
                    $lengthP = count($typeterrainonplayeractive);
                    if ($lengthP >= $lengthC)
                        {
                        // Parcourir $typeterrainonplayeractive pour comparer les sous-séquences de longueur $lengthB
                            for ($k = 0; $k <= $lengthP - $lengthC; $k++) 
                            {
                                $subSequence = array_slice($typeterrainonplayeractive, $k, $lengthC);
                        
                                // Vérifier si la sous-séquence correspond à $i
                                if ($subSequence === $combi) 
                                {
                                
                                $ret['BiomeValide'][] = 'biome_'.$variable['id'];
                                }

                                
                            }
                        }

                }
 
  
            }
            

        }
        



        foreach($bigbiomeonboard as $variable)
        {
            
            $i = $this->listbigbiomes[$variable['type']]['type'];
            $zeroPositions = array();
            foreach ($i as $key => $value) 
            {
                if ($value == '0') {
                    $zeroPositions[] = $key;
                }
            }
            $zeroCount = count($zeroPositions);
            

            if ($zeroCount == 0)
            {

            $lengthB = count($i);
            $lengthP = count($typeterrainonplayeractive);
                if ($lengthP >= $lengthB)
                {
                // Parcourir $typeterrainonplayeractive pour comparer les sous-séquences de longueur $lengthB
                    for ($j = 0; $j <= $lengthP - $lengthB; $j++) 
                    {
                        $subSequence = array_slice($typeterrainonplayeractive, $j, $lengthB);
                
                        // Vérifier si la sous-séquence correspond à $i
                        if ($subSequence === $i) 
                        {
                        
                        $ret['BigBiomeValide'][] = 'bigbiome_'.$variable['id'];
                        }

                        
                    }
                }

            }

            if ($zeroCount > 0)
            {
                
                $copiebiome = $i;
                $combinaison = array();

                $combinaison = $this->genererCombinaisons($copiebiome);
                

                foreach ($combinaison as $combi)
                {

                    $lengthC = count($combi);
                    $lengthP = count($typeterrainonplayeractive);
                    if ($lengthP >= $lengthC)
                        {
                        // Parcourir $typeterrainonplayeractive pour comparer les sous-séquences de longueur $lengthB
                            for ($k = 0; $k <= $lengthP - $lengthC; $k++) 
                            {
                                $subSequence = array_slice($typeterrainonplayeractive, $k, $lengthC);
                                                        
                                // Vérifier si la sous-séquence correspond à $i
                                if ($subSequence === $combi) 
                                {
                                
                                $ret['BigBiomeValide'][] = 'bigbiome_'.$variable['id'];
                                
                                }

                                
                            }
                        }

                }
 
  
            }
            

        }

        }
        
        if($gamemode == 2)
        {
        $testterrain14 = self::getUniqueValueFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = '14'");
        $testterrain15 = self::getUniqueValueFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = '15'");
        $typeterrainonplayeractive = array();
        $typeterrainonplayeractive1 = array();
        $typeterrainonplayeractive2 = array();
        
        if ($testterrain14 != NULL && $testterrain15 != NULL)
        {
            for ($a=0; $a<=12; $a++)
            {
            $typeterrainonplayeractive1 = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg >= {$a} AND card_location_arg != '13' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true );
            $typeterrainonplayeractive2 = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg < {$a} AND card_location_arg != '13' AND card_location_arg != '16' AND card_location_arg != '14' AND card_location_arg != '15' ORDER BY card_location_arg ASC", true );
            $typeterrainonplayeractive[] = array_merge($typeterrainonplayeractive1, $typeterrainonplayeractive2);
            }
            $typeterrainonplayeractive3a = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = '14'", true);
            $typeterrainonplayeractive3b = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = '15'", true);
            $typeterrainonplayeractive3c = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg >=1 AND card_location_arg <=12 ORDER BY card_location_arg ASC", true );
            $typeterrainonplayeractive[] = array_merge($typeterrainonplayeractive3a, $typeterrainonplayeractive3b, $typeterrainonplayeractive3c);
            $typeterrainonplayeractive[] = array_merge($typeterrainonplayeractive3b, $typeterrainonplayeractive3c, $typeterrainonplayeractive3a);
        } 
        
        if ($testterrain14 != NULL && $testterrain15 == NULL)
        {
            $typeterrainonplayeractive[] = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true );
            
        }

        if ($testterrain14 == NULL && $testterrain15 == NULL)
        {
            $typeterrainonplayeractive[] = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true );
            
        }

        if ($testterrain14 == NULL && $testterrain15 != NULL)
        {
            
            $typeterrainonplayeractive1 = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true );
            $typeterrainonplayeractive2 = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = '15'", true);
            $typeterrainonplayeractive[] = array_merge($typeterrainonplayeractive2, $typeterrainonplayeractive1);
        }

        
        
        
        $biomeonboard = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM biome WHERE card_location = 'biomeonboard'" );
        $bigbiomeonboard = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM bigbiome WHERE card_location = 'bigbiomeonboard'" );

        $countbiomeonboard = count($biomeonboard); // nbre de biome sur le terrain

        foreach($typeterrainonplayeractive as $variable3)
        {
            
        
        foreach($biomeonboard as $variable)
        {
            
            $i = $this->listbiomes[$variable['type']]['type'];

            $zeroPositions = array();
            foreach ($i as $key => $value) 
            {
                if ($value == '0') {
                    $zeroPositions[] = $key;
                }
            }
            $zeroCount = count($zeroPositions);

            if ($zeroCount == 0)
            {

            $lengthB = count($i);
            $lengthP = count($variable3);
                if ($lengthP >= $lengthB)
                {
                // Parcourir $typeterrainonplayeractive pour comparer les sous-séquences de longueur $lengthB
                    for ($j = 0; $j <= $lengthP - $lengthB; $j++) 
                    {
                        $subSequence = array_slice($variable3, $j, $lengthB);
                
                        // Vérifier si la sous-séquence correspond à $i
                        if ($subSequence === $i) 
                        {
                        
                        $ret['BiomeValide'][] = 'biome_'.$variable['id'];
                        }

                        
                    }
                }

            }

            if ($zeroCount > 0)
            {
                
                $copiebiome = $i;
                $combinaison = array();

                $combinaison = $this->genererCombinaisons($copiebiome);

                foreach ($combinaison as $combi)
                {

                    $lengthC = count($combi);
                    $lengthP = count($variable3);
                    if ($lengthP >= $lengthC)
                        {
                        // Parcourir $typeterrainonplayeractive pour comparer les sous-séquences de longueur $lengthB
                            for ($k = 0; $k <= $lengthP - $lengthC; $k++) 
                            {
                                $subSequence = array_slice($variable3, $k, $lengthC);
                        
                                // Vérifier si la sous-séquence correspond à $i
                                if ($subSequence === $combi) 
                                {
                                
                                $ret['BiomeValide'][] = 'biome_'.$variable['id'];
                                }

                                
                            }
                        }

                }
 
  
            }
            

        }
        



        foreach($bigbiomeonboard as $variable)
        {
            
            $i = $this->listbigbiomes[$variable['type']]['type'];
            $zeroPositions = array();
            foreach ($i as $key => $value) 
            {
                if ($value == '0') {
                    $zeroPositions[] = $key;
                }
            }
            $zeroCount = count($zeroPositions);
            

            if ($zeroCount == 0)
            {

            $lengthB = count($i);
            $lengthP = count($variable3);
                if ($lengthP >= $lengthB)
                {
                // Parcourir $typeterrainonplayeractive pour comparer les sous-séquences de longueur $lengthB
                    for ($j = 0; $j <= $lengthP - $lengthB; $j++) 
                    {
                        $subSequence = array_slice($variable3, $j, $lengthB);
                
                        // Vérifier si la sous-séquence correspond à $i
                        if ($subSequence === $i) 
                        {
                        
                        $ret['BigBiomeValide'][] = 'bigbiome_'.$variable['id'];
                        }

                        
                    }
                }

            }

            if ($zeroCount > 0)
            {
                
                $copiebiome = $i;
                $combinaison = array();

                $combinaison = $this->genererCombinaisons($copiebiome);
                

                foreach ($combinaison as $combi)
                {

                    $lengthC = count($combi);
                    $lengthP = count($variable3);
                    if ($lengthP >= $lengthC)
                        {
                        // Parcourir $typeterrainonplayeractive pour comparer les sous-séquences de longueur $lengthB
                            for ($k = 0; $k <= $lengthP - $lengthC; $k++) 
                            {
                                $subSequence = array_slice($variable3, $k, $lengthC);
                                                        
                                // Vérifier si la sous-séquence correspond à $i
                                if ($subSequence === $combi) 
                                {
                                
                                $ret['BigBiomeValide'][] = 'bigbiome_'.$variable['id'];
                                
                                }

                                
                            }
                        }

                }
 
  
            }
            

        }

    }

        
        }

        /*$ret["playeractive"] = array();
        $ret["playeractive"][] = $player_id;*/
        
        
        return $ret;
    }


    function argPlayerEvenement()
    {
        $player_id = self::getActivePlayerId();
        $testterrain14 = self::getUniqueValueFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = '14'");
        $testterrain15 = self::getUniqueValueFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = '15'");
        $typeterrainonplayeractive = array();
        $typeterrainonplayeractive1 = array();
        $typeterrainonplayeractive2 = array();
        $id = array();
        $id1 = array();
        $id2 = array();
        
        

        if ($testterrain14 != NULL && $testterrain15 != NULL)
        {
            $typeterrainonplayeractive1 = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true);
            $typeterrainonplayeractive2 = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true);
            $typeterrainonplayeractive = array_merge($typeterrainonplayeractive1, $typeterrainonplayeractive2);
            $id1 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true);
            $id2 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true);
            $id = array_merge($id1, $id2);
            
        } 
        
        if ($testterrain14 != NULL && $testterrain15 == NULL)
        {
            $typeterrainonplayeractive = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true );
            $id = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true );
        }

        if ($testterrain14 == NULL && $testterrain15 == NULL)
        {
            $typeterrainonplayeractive = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true );
            $id = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true );
        }

        if ($testterrain14 == NULL && $testterrain15 != NULL)
        {
            
            $typeterrainonplayeractive1 = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true );
            $typeterrainonplayeractive2 = self::getObjectListFromDB( "SELECT card_type type FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = '15'", true);
            $typeterrainonplayeractive = array_merge($typeterrainonplayeractive2, $typeterrainonplayeractive1);
            $id1 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16' ORDER BY card_location_arg ASC", true );
            $id2 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_location_arg = '15'", true);
            $id = array_merge($id2, $id1);
        }

        


        $ret = array();
        $ret['Event1'] = array();
        $ret['Event2'] = array();
        $ret['Event3'] = array();
        $ret['Event4'] = array();

        ///////////////Event1
        $nbreforestinbag = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = 'deck'"));
        if ($nbreforestinbag > 0)
        {
            
            $valeur1 = 1; // Remplacez par la valeur1 que vous recherchez
            $valeur2 = 2; // Remplacez par la valeur2 que vous voulez vérifier si elle est avant ou après
            
            $positions1 = [];
            $positions2 = [];

            foreach ($typeterrainonplayeractive as $key => $valeur) 
            {
                if ($valeur == $valeur1) {
                    $positions1[] = $key;
                } elseif ($valeur == $valeur2) {
                    $positions2[] = $key;
                }
            }
            
            
            foreach ($positions1 as $position1) 
            {
                foreach ($positions2 as $position2) 
                {
                    if ($position2 === $position1 - 1) 
                    {
                        $ret['Event1'][] = 'token_'.$id[$position2];
                    } 
                    
                    if ($position2 === $position1 + 1) 
                    {
                         $ret['Event1'][] = 'token_'.$id[$position2];
                    }
                }
            }
                 
        }

        
        ///////////////Event2
        $nbreglaceinbag = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '5' AND card_location = 'deck'"));
        if ($nbreglaceinbag > 0)
        {
            
            $valeur1 = 4; // Remplacez par la valeur1 que vous recherchez
            $valeur2 = 1; // Remplacez par la valeur2 que vous voulez vérifier si elle est avant ou après
            
            $positions1 = [];
            $positions2 = [];

            foreach ($typeterrainonplayeractive as $key => $valeur) 
            {
                if ($valeur == $valeur1) {
                    $positions1[] = $key;
                } elseif ($valeur == $valeur2) {
                    $positions2[] = $key;
                }
            }
            
            
            foreach ($positions1 as $position1) 
            {
                foreach ($positions2 as $position2) 
                {
                    if ($position2 === $position1 - 1) 
                    {
                        $ret['Event2'][] = 'token_'.$id[$position2];
                    } 
                    
                    if ($position2 === $position1 + 1) 
                    {
                        $ret['Event2'][] = 'token_'.$id[$position2];
                    }
                }
            }
                 
        }


        ///////////////Event3
        $nbredesertinbag = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '2' AND card_location = 'deck'"));
        if ($nbredesertinbag > 0)
        {
            
            $valeur1 = 2; // Remplacez par la valeur1 que vous recherchez
            
            
            $positions1 = [];
            $positionsAdjacentes = [];

            foreach ($typeterrainonplayeractive as $key => $valeur) 
            {
                if ($valeur == $valeur1) {
                    $positions1[] = $key;
                }
            }
            
                        
            foreach ($positions1 as $position1) 
            {
                if ($position1 > 0) 
                {
                    
                    if($typeterrainonplayeractive[$position1 - 1] != '2')
                    {
                    $ret['Event3'][] = 'token_'.$id[$position1 - 1];
                    }
                    
                }
                if ($position1 < count($typeterrainonplayeractive) - 1) 
                {
                    if($typeterrainonplayeractive[$position1 + 1] != '2')
                    {
                    $ret['Event3'][] = 'token_'.$id[$position1 + 1];
                    }
                }
            }
            
        }

        ///////////////Event4
        $nbrelandinbag = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_location = 'deck'"));
        if ($nbrelandinbag > 0)
        {
            //$glaces = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_type = '5' AND card_location_arg != '13' AND card_location_arg != '16'", true);
            $glaces = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id}) AND card_type = '5' AND card_location_arg != '16'", true);
            foreach ($glaces as $glace)
            {
                $ret['Event4'][] = 'token_'.$glace;

            }

        }

        ///////////////fleches
        $listplayer = self::getObjectListFromDB( "SELECT player_id id FROM player", true );
        $allterrainonplayer = array();
        foreach($listplayer as $a)
        {
            $allterrainonplayer[] = [
                'id' => $a,
                'pos' => self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$a})", true ),
            ];
        }

        foreach($allterrainonplayer as $variable2)
            {
                foreach($variable2['pos'] as $variable3)
                {
            $testg = 0;
            $testd = 0;
                for($j=1; $j<=$variable3; $j++)
                {
                    if(!in_array($j, $variable2['pos']))
                    {
                    $testg = 1;
                    }
                }
                if($testg == 1)
                {
                $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_g';
                }
            
                for($j=$variable3; $j<=12; $j++)
                {
                    if(!in_array($j, $variable2['pos']))
                    {
                    $testd = 1;
                    }
                }
                if($testd == 1)
                {
                $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_d';
                }
            }
    
            }

            /*$ret["playeractive"] = array();
            $ret["playeractive"][] = $player_id;*/
        
        
        return $ret;

    }

    function argPlayerEvenementSelect()
    {
        $player_id = self::getActivePlayerId();
        $ret = array();
        $ret['Event'] = array();
        $ret['NumberEvent'] = array();
        $tab = $this->argPlayerEvenement();
        $event = $this->getGameStateValue('event');

        if ($event == 1)
        {
            $ret['Event'] = $tab['Event1'];
            $ret['NumberEvent'][] = 1;

        }

        if ($event == 2)
        {
            $ret['Event'] = $tab['Event2'];
            $ret['NumberEvent'][] = 2;

        }

        if ($event == 3)
        {
            $ret['Event'] = $tab['Event3'];
            $ret['NumberEvent'][] = 3;


        }

        if ($event == 4)
        {
            $ret['Event'] = $tab['Event4'];
            $ret['NumberEvent'][] = 4;


        }

        
        ///////////////fleches
        $listplayer = self::getObjectListFromDB( "SELECT player_id id FROM player", true );
        $allterrainonplayer = array();
        foreach($listplayer as $a)
        {
            $allterrainonplayer[] = [
                'id' => $a,
                'pos' => self::getObjectListFromDB( "SELECT card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$a})", true ),
            ];
        }

        foreach($allterrainonplayer as $variable2)
            {
                foreach($variable2['pos'] as $variable3)
                {
            $testg = 0;
            $testd = 0;
                for($j=1; $j<=$variable3; $j++)
                {
                    if(!in_array($j, $variable2['pos']))
                    {
                    $testg = 1;
                    }
                }
                if($testg == 1)
                {
                $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_g';
                }
            
                for($j=$variable3; $j<=12; $j++)
                {
                    if(!in_array($j, $variable2['pos']))
                    {
                    $testd = 1;
                    }
                }
                if($testd == 1)
                {
                $ret["fleche"][] = 'fleche_'.$variable2['id'].'_'.$variable3.'_d';
                }
            }
    
            }

        /*$ret["playeractive"] = array();
        $ret["playeractive"][] = $player_id;*/
        
        
        return $ret;

    }

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

function stGameMode()
{
    $gamemode = $this->gamestate->table_globals[100];
        if($gamemode == 1)
        {
        $this->gamestate->nextState( 'game' );
        }

        if($gamemode == 2)
        {
        $this->activePrevPlayer();
        $player_id = self::getActivePlayerId();
        $board = self::getUniqueValueFromDB("SELECT player_board FROM player WHERE player_id={$player_id}");
        if ($board == NULL)
            {
                $this->gamestate->nextState( 'selectboard' );
            }
        else
            {
                self::activeNextPlayer();
                $this->gamestate->nextState( 'game' );
            }
        
        }

}

function stNextPlayer()
{
    
            
    // Active next player
    $gamemode = $this->gamestate->table_globals[100];
    $player_id = self::activeNextPlayer();
    $terrainonplayeractive = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM terrain WHERE card_location = CONCAT('terrain_', {$player_id})" );
    $nbre = count($terrainonplayeractive);
    
    
    if($gamemode == 1 && $nbre == 13)
    {
        self::notifyAllPlayers( 'simplePause', '', [ 'time' => 500] ); 
        $typelune = self::getObjectListFromDB("SELECT card_location location, card_type type FROM terrain WHERE card_location_arg = '13' AND card_location != 'deck'");

        foreach($typelune as $variable)
        {
            
            $player = explode("_",$variable['location'])[1];
            $type = $variable['type'];
            $listterrainsametype = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '{$type}' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '14' AND card_location_arg != '15' AND card_location_arg != '16'");
            $nombre = count($listterrainsametype);
            $scorelune = 3*$nombre;
            /*self::DbQuery( "UPDATE player set player_score = player_score + {$scorelune} WHERE player_id = '{$player}'" );
            $this->incStat($scorelune, 'points_moon', $player);
            self::notifyAllPlayers( 'message', clienttranslate('${player_name} gains ${scorelune} additional points thanks to the moon'),
            array(
                
                'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player}"),
                'scorelune' => $scorelune,
                               
            ));*/

            //tie-breaker
            $aux1 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '1' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'"));
            $aux2 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '2' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'"));
            $aux3 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'"));
            $aux4 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '4' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'"));
            $aux5 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '5' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'"));

            $tableau_aux = [$aux1, $aux2, $aux3, $aux4, $aux5];
            sort($tableau_aux);
            $score_aux= 100000000*$tableau_aux[0] + 1000000*$tableau_aux[1] + 10000*$tableau_aux[2] + 100*$tableau_aux[3] + 1*$tableau_aux[4];
            self::DbQuery( "UPDATE player set player_score_aux = {$score_aux} WHERE player_id = '{$player}'" );

        }
        
        $newscore = self::getCollectionFromDb( "SELECT player_id, player_score FROM player", true );

        $this->notifyAllPlayers( "moonscore", '',
                    array(
    
                        'newscore' => $newscore,
                                                
                
                    )
                    );
                    
               
        $this->gamestate->nextState( 'endGame' );
    }
    


    
    if($gamemode == 2 && $nbre == 15)
    {
        self::notifyAllPlayers( 'simplePause', '', [ 'time' => 500] ); 
        $typelune = self::getObjectListFromDB("SELECT card_location location, card_type type FROM terrain WHERE card_location_arg = '13' AND card_location != 'deck'");
        $typeboard = self::getObjectListFromDB("SELECT player_id id, player_board board FROM player");

        foreach($typelune as $variable)
        {
            
            $player = explode("_",$variable['location'])[1];
            $type = $variable['type'];
            $listterrainsametype = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '{$type}' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'");
            $nombre = count($listterrainsametype);
            /*$scorelune = 3*$nombre;
            self::DbQuery( "UPDATE player set player_score = player_score + {$scorelune} WHERE player_id = '{$player}'" );
            $this->incStat($scorelune, 'points_moon', $player);
            self::notifyAllPlayers( 'message', clienttranslate('${player_name} gains ${scorelune} additional points thanks to the moon'),
            array(
                
                'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player}"),
                'scorelune' => $scorelune,
                               
            ));*/

            //tie-breaker
            $aux1 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '1' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'"));
            $aux2 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '2' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'"));
            $aux3 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'"));
            $aux4 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '4' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'"));
            $aux5 = count(self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '5' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'"));

            $tableau_aux = [$aux1, $aux2, $aux3, $aux4, $aux5];
            sort($tableau_aux);
            $score_aux= 100000000*$tableau_aux[0] + 1000000*$tableau_aux[1] + 10000*$tableau_aux[2] + 100*$tableau_aux[3] + 1*$tableau_aux[4];
            self::DbQuery( "UPDATE player set player_score_aux = {$score_aux} WHERE player_id = '{$player}'" );

        }

        foreach($typeboard as $variable2)
        {
            $player = $variable2['id'];
            $board = $variable2['board'];

            if ($board == 1)
            {
                $listterrainsametype1 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'");
                $nombre = count($listterrainsametype1);
                $scorebonus = 2*$nombre; 
                /*self::DbQuery( "UPDATE player set player_score = player_score + {$scorebonus} WHERE player_id = '{$player}'" );
                $this->incStat($scorebonus, 'points_board', $player);
                self::notifyAllPlayers( 'message', clienttranslate('${player_name} gains ${scorebonus} additional points thanks to the board bonus'),
                array(
                
                'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player}"),
                'scorebonus' => $scorebonus,
                               
                ));*/

            }

            if ($board == 2)
            {
                $listterrainsametype1 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'");
                $listterrainsametype2 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '4' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'");
                $nombre1 = count($listterrainsametype1);
                $nombre2 = count($listterrainsametype2);
                $scorebonus = $nombre1 + $nombre2; 
                /*self::DbQuery( "UPDATE player set player_score = player_score + {$scorebonus} WHERE player_id = '{$player}'" );
                $this->incStat($scorebonus, 'points_board', $player);
                self::notifyAllPlayers( 'message', clienttranslate('${player_name} gains ${scorebonus} additional points thanks to the board bonus'),
                array(
                
                'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player}"),
                'scorebonus' => $scorebonus,
                               
                ));*/

            }

            if ($board == 3)
            {
                $listterrainsametype1 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'");
                $listterrainsametype2 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '2' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'");
                $nombre1 = count($listterrainsametype1);
                $nombre2 = count($listterrainsametype2);
                $scorebonus = $nombre1 + $nombre2; 
                /*self::DbQuery( "UPDATE player set player_score = player_score + {$scorebonus} WHERE player_id = '{$player}'" );
                $this->incStat($scorebonus, 'points_board', $player);
                self::notifyAllPlayers( 'message', clienttranslate('${player_name} gains ${scorebonus} additional points thanks to the board bonus'),
                array(
                
                'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player}"),
                'scorebonus' => $scorebonus,
                               
                ));*/

            }

            if ($board == 4)
            {
                $listterrainsametype1 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'");
                $listterrainsametype2 = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_type = '5' AND card_location = CONCAT('terrain_', '{$player}') AND card_location_arg != '13' AND card_location_arg != '16'");
                $nombre1 = count($listterrainsametype1);
                $nombre2 = count($listterrainsametype2);
                $scorebonus = $nombre1 + $nombre2; 
                /*self::DbQuery( "UPDATE player set player_score = player_score + {$scorebonus} WHERE player_id = '{$player}'" );
                $this->incStat($scorebonus, 'points_board', $player);
                self::notifyAllPlayers( 'message', clienttranslate('${player_name} gains ${scorebonus} additional points thanks to the board bonus'),
                array(
                
                'player_name' => self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player}"),
                'scorebonus' => $scorebonus,
                               
                ));*/

            }




        }
        
        $newscore = self::getCollectionFromDb( "SELECT player_id, player_score FROM player", true );

        $this->notifyAllPlayers( "moonscore", '',
                    array(
    
                        'newscore' => $newscore,
                                                
                
                    )
                    );
                    
               
        $this->gamestate->nextState( 'endGame' );
    }
    




    else 
    {    
        $terrainonallplayer = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM terrain WHERE card_location != 'terrainonboard' AND card_location != 'deck'" );
        $nbredejoeurs = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));
        $nbretotalterrainplayer = count($terrainonallplayer);

        
        if(($gamemode == 1 && $nbretotalterrainplayer != 13*$nbredejoeurs)||($gamemode == 2 && $nbretotalterrainplayer != 15*$nbredejoeurs))
    {


        $terrainonboard = self::getObjectListFromDB( "SELECT card_id id FROM terrain WHERE card_location = 'terrainonboard'" );
        if ($terrainonboard == NULL)
        {
            for($i=1; $i<=5; $i++)
            {
    
                $location = "terrainonboard";
                $this->terrains->pickCardForLocation( 'deck', $location, $i );
            
            }

            $nbre1 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '1' AND card_location = 'deck'"));
            $nbre2 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '2' AND card_location = 'deck'"));
            $nbre3 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '3' AND card_location = 'deck'"));
            $nbre4 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '4' AND card_location = 'deck'"));
            $nbre5 = count (self::getObjectListFromDB("SELECT card_id id FROM terrain WHERE card_type = '5' AND card_location = 'deck'"));
        
            $newterrainonboard = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location_arg position FROM terrain WHERE card_location = 'terrainonboard' " );
            $this->notifyAllPlayers( "remplir", clienttranslate('5 new lands are available'),
            array(
       
                'mobile' => $newterrainonboard,
                'nbre1' => $nbre1,
                'nbre2' => $nbre2,
                'nbre3' => $nbre3,
                'nbre4' => $nbre4,
                'nbre5' => $nbre5,
                
            )
            );
    
        }
    }

    // This player can play. Give him some extra time
    self::giveExtraTime( $player_id );
    $this->gamestate->nextState( 'next' );
    }
    
}

//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */

    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }   
    
    
    /*public function loadBugReportSQL(int $reportId, array $studioPlayers): void
    {
        $prodPlayers = $this->getObjectListFromDb("SELECT `player_id` FROM `player`", true);
        $prodCount = count($prodPlayers);
        $studioCount = count($studioPlayers);
        if ($prodCount != $studioCount) {
            throw new BgaVisibleSystemException("Incorrect player count (bug report has $prodCount players, studio table has $studioCount players)");
        }

        // SQL specific to your game
        // For example, reset the current state if it's already game over
        $sql = [
            "UPDATE `global` SET `global_value` = 10 WHERE `global_id` = 1 AND `global_value` = 99"
        ];
        foreach ($prodPlayers as $index => $prodId) {
            $studioId = $studioPlayers[$index];
            // SQL common to all games
            $sql[] = "UPDATE `player` SET `player_id` = $studioId WHERE `player_id` = $prodId";
            $sql[] = "UPDATE `global` SET `global_value` = $studioId WHERE `global_value` = $prodId";
            $sql[] = "UPDATE `stats` SET `stats_player_id` = $studioId WHERE `stats_player_id` = $prodId";

            // SQL specific to your game
            //$sql[] = "UPDATE `card` SET `card_location_arg` = $studioId WHERE `card_location_arg` = $prodId";
            //$sql[] = "UPDATE `my_table` SET `my_column` = REPLACE(`my_column`, $prodId, $studioId)";
        }
        foreach ($sql as $q) {
            $this->DbQuery($q);
        }
        $this->reloadPlayersBasicInfos();
    }*/

}
