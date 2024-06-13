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
 * material.inc.php
 *
 * Biomos game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */


/*

Example:

$this->card_types = array(
    1 => array( "card_name" => ...,
                ...
              )
);

*/
$this->types = [
  1=>clienttranslate("a Sea"),
  2=>clienttranslate("a Desert"),
  3=>clienttranslate("a Forest"),
  4=>clienttranslate("a Mountain"),
  5=>clienttranslate("an Ice"),
];

$this->listbiomes = [
  '1' => [
    'type' => ['5','5'],
    'name' => clienttranslate("Ice cave"),
    'points'=>3,    
   ],
 
   '2' => [
    'type' => ['2','2'],
    'name' => clienttranslate("Grassland"),
    'points'=>3,    
   ],

   '3' => [
    'type' => ['3','3'],
    'name' => clienttranslate("Broadleaf Forest"),
    'points'=>3,    
   ],

   '4' => [
    'type' => ['1','1'],
    'name' => clienttranslate("Marginal Sea"),
    'points'=>3,    
   ],

   '5' => [
    'type' => ['4','4'],
    'name' => clienttranslate("Volcano"),
    'points'=>3,    
   ],

   '6' => [
    'type' => ['5','0','5'],
    'name' => clienttranslate("Frozen Landscape"),
    'points'=>3,    
   ],
 
   '7' => [
    'type' => ['2','0','2'],
    'name' => clienttranslate("Steppe"),
    'points'=>3,    
   ],

   '8' => [
    'type' => ['3','0','3'],
    'name' => clienttranslate("Forest Glade"),
    'points'=>3,    
   ],

   '9' => [
    'type' => ['1','0','1'],
    'name' => clienttranslate("Seabed"),
    'points'=>3,    
   ],

   '10' => [
    'type' => ['4','0','4'],
    'name' => clienttranslate("Gorge"),
    'points'=>3,    
   ],

   '11' => [
    'type' => ['3','4','4','3'],
    'name' => clienttranslate("Stone Forest"),
    'points'=>7,    
   ],
 
   '12' => [
    'type' => ['4','3','4'],
    'name' => clienttranslate("Small Valley"),
    'points'=>5,    
   ],

   '13' => [
    'type' => ['2','5','5','2'],
    'name' => clienttranslate("Tundra"),
    'points'=>7,    
   ],

   '14' => [
    'type' => ['5','2','5'],
    'name' => clienttranslate("Dry Lake"),
    'points'=>5,    
   ],

   '15' => [
    'type' => ['1','5','1'],
    'name' => clienttranslate("Iceberg"),
    'points'=>5,    
   ],

   '16' => [
    'type' => ['5','1','1','5'],
    'name' => clienttranslate("Fjord"),
    'points'=>7,    
   ],
 
   '17' => [
    'type' => ['4','2','2','4'],
    'name' => clienttranslate("Mountain Range"),
    'points'=>7,    
   ],

   '18' => [
    'type' => ['2','4','2'],
    'name' => clienttranslate("Inselberg"),
    'points'=>5,    
   ],

   '19' => [
    'type' => ['3','1','3'],
    'name' => clienttranslate("Mangrove"),
    'points'=>5,    
   ],

   '20' => [
    'type' => ['1','3','3','1'],
    'name' => clienttranslate("Bay"),
    'points'=>7,    
   ],

   '21' => [
    'type' => ['5','4','5'],
    'name' => clienttranslate("Nunatak"),
    'points'=>5,    
   ],
 
   '22' => [
    'type' => ['4','5','5','4'],
    'name' => clienttranslate("Glacial Cirque"),
    'points'=>7,    
   ],

   '23' => [
    'type' => ['3','5','3'],
    'name' => clienttranslate("Frozen Lake"),
    'points'=>5,    
   ],

   '24' => [
    'type' => ['5','3','3','5'],
    'name' => clienttranslate("Taiga"),
    'points'=>7,    
   ],

   '25' => [
    'type' => ['2','3','2'],
    'name' => clienttranslate("Shrubland"),
    'points'=>5,    
   ],

   '26' => [
    'type' => ['3','2','2','3'],
    'name' => clienttranslate("Giant trees"),
    'points'=>7,    
   ],
 
   '27' => [
    'type' => ['1','2','1'],
    'name' => clienttranslate("Geyser Field"),
    'points'=>5,    
   ],

   '28' => [
    'type' => ['2','1','1','2'],
    'name' => clienttranslate("Lagoon"),
    'points'=>7,    
   ],

   '29' => [
    'type' => ['4','1','4'],
    'name' => clienttranslate("Canyon"),
    'points'=>5,    
   ],

   '30' => [
    'type' => ['1','4','4','1'],
    'name' => clienttranslate("Submarine volcano"),
    'points'=>7,    
   ],
 
 
 ];


 $this->listbigbiomes = [
  '1' => [
    'type' => ['4','0','4','0','4','0','4'],
    'name' => clienttranslate("Majestic Cordillera"),
    'points'=>10,    
   ],
 
   '2' => [
    'type' => ['3','0','3','0','3','0','3'],
    'name' => clienttranslate("Mixed Forest"),
    'points'=>10,    
   ],

   '3' => [
    'type' => ['5','0','5','0','5','0','5'],
    'name' => clienttranslate("Aurora Borealis"),
    'points'=>10,    
   ],

   '4' => [
    'type' => ['2','0','2','0','2','0','2'],
    'name' => clienttranslate("Immense Savanna"),
    'points'=>10,    
   ],

   '5' => [
    'type' => ['2','2','2','2'],
    'name' => clienttranslate("Great Sand Dunes"),
    'points'=>10,    
   ],

   '6' => [
    'type' => ['1','1','1','1'],
    'name' => clienttranslate("Ocean"),
    'points'=>10,    
   ],
 
   '7' => [
    'type' => ['1','0','1','0','1','0','1'],
    'name' => clienttranslate("Archipelago"),
    'points'=>10,    
   ],

   '8' => [
    'type' => ['5','5','0','0','5','5'],
    'name' => clienttranslate("Salt Pan"),
    'points'=>10,    
   ],

   '9' => [
    'type' => ['3','3','0','0','3','3'],
    'name' => clienttranslate("Tropical Forest"),
    'points'=>10,    
   ],

   '10' => [
    'type' => ['4','4','0','0','4','4'],
    'name' => clienttranslate("Lush Valley"),
    'points'=>10,    
   ],

  ];

