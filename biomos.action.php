<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Biomos implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * biomos.action.php
 *
 * Biomos main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/biomos/biomos/myAction.html", ...)
 *
 */
  
  
  class action_biomos extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "biomos_biomos";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
  	// TODO: defines your action entry points there


    public function actSelect()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    
  	    $this->game->actSelect( $arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actTurnSelect()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	      	    
  	    $this->game->actTurnSelect( $arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actCancel()
  	{
  	    self::setAjaxMode();
  	    
  	    
  	    $this->game->actCancel( );
  	    
  	    self::ajaxResponse( );
  	}


    public function actNotMove()
  	{
  	    self::setAjaxMode();
  	    
  	    
  	    $this->game->actNotMove( );
  	    
  	    self::ajaxResponse( );
  	}

    public function actSelectFleche()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    
  	    $this->game->actSelectFleche( $arg1 );
  	    
  	    self::ajaxResponse( );
  	}

	
	public function actNotValidate()
  	{
		self::setAjaxMode();
  	    
  	    
		$this->game->actNotValidate( );
		
		self::ajaxResponse( );
  	}

	  public function actValidateBiome()
  	{
		self::setAjaxMode();

		$arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    
		$this->game->actValidateBiome( $arg1 );
		
		self::ajaxResponse( );
  	}

	  public function actMoonSelect()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	      	    
  	    $this->game->actMoonSelect( $arg1 );
  	    
  	    self::ajaxResponse( );
  	}

	  public function actMoonMove()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	      	    
  	    $this->game->actMoonMove ( $arg1 );
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidateBoard()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	      	    
  	    $this->game->actValidateBoard( $arg1 );
  	    
  	    self::ajaxResponse( );
  	}

	  public function actNotEvent()
  	{
		self::setAjaxMode();
  	    
  	    
		$this->game->actNotEvent( );
		
		self::ajaxResponse( );
  	}

	  public function actEvent1()
  	{
		self::setAjaxMode();
  	    
  	    
		$this->game->actEvent1( );
		
		self::ajaxResponse( );
  	}

	  public function actEvent2()
  	{
		self::setAjaxMode();
  	    
  	    
		$this->game->actEvent2( );
		
		self::ajaxResponse( );
  	}

	  public function actEvent3()
  	{
		self::setAjaxMode();
  	    
  	    
		$this->game->actEvent3( );
		
		self::ajaxResponse( );
  	}

	  public function actEvent4()
  	{
		self::setAjaxMode();
  	    
  	    
		$this->game->actEvent4( );
		
		self::ajaxResponse( );
  	}

	  public function actEventSelect()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	      	    
  	    $this->game->actEventSelect( $arg1 );
  	    
  	    self::ajaxResponse( );
  	}

	  


    /*
    
    Example:
  	
    public function myAction()
    {
        self::setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }
    
    */

  }
  

