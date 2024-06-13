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
 * states.inc.php
 *
 * Biomos game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with "game" type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by "st" (ex: "stMyGameStateName").
   _ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in "nextState" PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on "onEnteringState" or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

//    !! It is not a good idea to modify this file when a game is running !!

 
$machinestates = array(

    // The initial state. Please do not modify.
    1 => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => array( "" => 2 )
    ),

    2 => array(
        "name" => "gameMode",
        "type" => "game",
        "action" => "stGameMode",
        "transitions" => array( "game" => 10, "selectboard" => 3, "cantPlay" => 2, "endGame" => 99 )
        ),

    3 => array(
        "name" => "playerSelectBoard",
        "description" => clienttranslate('${actplayer} must select a board'),
        "descriptionmyturn" => clienttranslate('${you} must select a board'),
        "type" => "activeplayer",
        "args" => "argSelectBoard",
        "possibleactions" => array("select"),
        "transitions" => array( "next" => 2, "same" => 3, "game" => 10, "zombiePass" => 2 )
        ),
    
    
    10 => array(
        "name" => "playerTurnSelect",
        "description" => clienttranslate('${actplayer} must select and place a Land token'),
        "descriptionmyturn" => clienttranslate('${you} must select a Land token'),
        "type" => "activeplayer",
        "args" => "argPlayerTurn",
        "possibleactions" => array("selectland", "fleches"),
        "transitions" => array( "next" => 11, "same" => 10, "zombiePass" => 40 )
        ),

    11 => array(
        "name" => "playerTurnPlace",
        "description" => clienttranslate('${actplayer} must select and place a Land token'),
        "descriptionmyturn" => clienttranslate('${you} must place the Land token'),
        "type" => "activeplayer",
        "args" => "argPlayerTurnPlace",
        "possibleactions" => array("selectplace", "cancel", "fleches"),
        "transitions" => array( "next" => 40, "moon" => 20, "evenement" => 22, "biome" => 30, "same" => 11, "cancel" => 10, "zombiePass" => 40 )
        ),

    20 => array(
        "name" => "playerMoon",
        "description" => clienttranslate('${actplayer} can select a Land token and move it'),
        "descriptionmyturn" => clienttranslate('${you} can select a Land token and move it'),
        "type" => "activeplayer",
        "args" => "argPlayerMoon",
        "possibleactions" => array("selectmoon", "fleches"),
        "transitions" => array( "next" => 40, "select" => 21, "same" => 20, "biome" => 30, "zombiePass" => 40 )
        ),

    21 => array(
        "name" => "playerMoonSelect",
        "description" => clienttranslate('${actplayer} must place the Land token'),
        "descriptionmyturn" => clienttranslate('${you} must place the Land token'),
        "type" => "activeplayer",
        "args" => "argPlayerMoonSelect",
        "possibleactions" => array("selectlandmoon", "fleches"),
        "transitions" => array( "next" => 40, "same" => 21, "biome" => 30, "zombiePass" => 40 )
        ),

    22 => array(
        "name" => "playerEvenement",
        "description" => clienttranslate('${actplayer} can trigger a planetary event'),
        "descriptionmyturn" => clienttranslate('${you} can trigger a planetary event'),
        "type" => "activeplayer",
        "args" => "argPlayerEvenement",
        "possibleactions" => array("selectevenement", "fleches"),
        "transitions" => array( "next" => 40, "select" => 23, "same" => 22, "biome" => 30, "zombiePass" => 40 )
        ),

    23 => array(
        "name" => "playerEvenementSelect",
        "description" => clienttranslate('${actplayer} can trigger a planetary event'),
        "descriptionmyturn" => clienttranslate('${you} must select the Land token to replace'),
        "type" => "activeplayer",
        "args" => "argPlayerEvenementSelect",
        "possibleactions" => array("selectlandevenement", "cancel", "fleches"),
        "transitions" => array( "next" => 40, "same" => 23, "cancel" => 22, "biome" => 30, "zombiePass" => 40 )
        ),

    30 => array(
            "name" => "playerBiome",
            "description" => clienttranslate('${actplayer} can validate a Biome'),
            "descriptionmyturn" => clienttranslate('${you} can validate a Biome'),
            "type" => "activeplayer",
            "args" => "argPlayerBiome",
            "possibleactions" => array("selectbiome", "fleches"),
            "transitions" => array( "next" => 40, "same" => 30, "zombiePass" => 40 )
            ),

    
    40 => array(
        "name" => "nextPlayer",
        "type" => "game",
        "action" => "stNextPlayer",
        "updateGameProgression" => true,
        "transitions" => array( "next" => 10, "cantPlay" => 40, "endGame" => 99 )
        ),
    

    // Final state.
    // Please do not modify (and do not overload action/args methods).
    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )

);