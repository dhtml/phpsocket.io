<?php
/**
 *  P H P S O C K E T . I O 
 * 
 *  a PHP 5 Socket API
 * 
 *  For more informations: {@link https://github.com/dhtml/phpsocket.io}
 *  
 *  @author Anthony Ogundipe
 *  @e-mail: diltony@yahoo.com
 *  @copyright Copyright (c) 2014 Anthony Ogundipe
 *  @license http://opensource.org/licenses/mit-license.php The MIT License
 *  @package phpsocket.io
 */
 
//include websockets dependencies
include "users.php";
include "websockets.php";
 
 
class PHPWebSockets  extends WebSocketServer{

    /**
     *  commands
     *  
     *  An object holding all available commands
     *
     *  @access public
     *
     *  @var  object
     *
     */
	public $commands;


    /**
     *  usernames
     *  
     *  An object holding all available users
     *
     *  @access public
     *
     *  @var  object
     *
     */
	public $usernames=Array();


    /**
     *  socketID
     *  
     *  An array holding all available socket IDs
     *
     *  @access public
     *
     *  @var  array
     *
     */
	public $socketID=Array();


    /**
     *  numUsers
     *  
     *  An integer holding number of users
     *
     *  @access public
     *
     *  @var  int
     *
     */
	public $numUsers=0;
	
	
	/**
    *  on
    *  
	*  This is used to package a request to the socket server
	*
    *  @param  string  $cmd     The command to be broadcasted e.g. chat
    *  @param  object  $cb      The function callback attached to the command
	*/
	public function on($cmd,$cb) {
		$this->commands["$cmd"]=$cb;
	}
	
	
	/**
    *  process
    *  
	*  This is called immediately when the data is recieved. and is used internally to send messages to clients. 
	*
    *  @param  object  $user     The user object of the client sending the message
    *  @param  object  $message  The message object to be sent
	*
	*  @access protected
	*/
	protected function process ($user, $message) 
    {
		$this->user=$user;
		
		$message=json_decode($message);
		
		$message->data= isset($message->data) ? $message->data : '';
		$message->sender= isset($message->sender) ? $message->sender : 0;
		
		if($message->broadcast) {
		//broadcast message
		$message->broadcast=false;
		$data=json_encode($message);

		foreach($this->users as $user) {
			$this->send($user, $data);
		}
		
		} else {
		//non-broadcast message
		$this->trigger($message->cmd,$message->data,$message->sender);
		}
	}
	
	/**
    *  trigger
    *  
	* This will trigger a command that has already been using using the on function
	*
    *  @param  string  $cmd     The command to be broadcasted e.g. chat
    *  @param  string  $data    (Optional) The data to be broadcasted along with the command e.g. hello world
    *  @param  string  $sender   (Optional) the id of the user that is sending the message
	*/
	public function trigger($cmd,$params='',$sender=null) {
		if(!isset($this->commands["$cmd"])) {return;}
		$this->commands["$cmd"]($this,$params,$sender);
	}
	
	/**
    *  connected
    *  
    *  This is executed when socket connection is established for a particular user
	*  A welcome message is also send back to the client
	*
    *  @param  object  $user     The user object of the client sending the message
	*
	*  @access protected
	*
    */
    protected function connected ($user) 
    {
		$this->user=$user;
		$this->trigger("connect",$user->id);
    }
	
    /**
    *  disconnect
    *  
    *   This is executed when a client is disconnected. It is a cleanup function.
	*
    *   @param  object   $socket    			The socket object of the connected client
    *   @param  boolean  $triggerClosed   		Flag to determine if close was triggered by client
    *   @param  boolean  $sockErrNo   			(optional) Socket error number
	*
	*   @access protected
    */
	 protected function disconnect($socket, $triggerClosed = true, $sockErrNo = null) {
	  $this->trigger("disconnect",$this->user->id);
	  parent::disconnect($socket, $triggerClosed, $sockErrNo);
	 }
     
    /**
    *  closed
    *  
    *   This is where cleanup would go, in case the user had any sort of
    *   open files or other objects associated with them.  This runs after the socket 
    *   has been closed, so there is no need to clean up the socket itself here.
	*
    *   @param  object  $user    The user object of the connected client
	*
	*   @access protected
    */
    protected function closed ($user) 
    {
    }
	
	/**
    *  emit
    *  
	* send message to current user only
	*
    *  @param  string  $cmd     The command to be broadcasted e.g. chat
    *  @param  string  $data    (Optional) The data to be broadcasted along with the command e.g. hello world
	*/
	public function emit($cmd,$data) {
		$this->send($this->user, $this->cmdwrap($cmd,$data));
	}
	
	/**
    *   send
    *  
	*   send message to specified user only
	*
    *   @param  object  $user    The user object of the recipient
    *   @param  string  $cmd     The command to be broadcasted e.g. chat
    *   @param  string  $data    (Optional) The data to be broadcasted along with the command e.g. hello world
	*/
	public function _send($user,$cmd,$data) {
		$this->send($user, $this->cmdwrap($cmd,$data));
	}
	
	/**
    *  cmdwrap
    *  
	* This is used internally to package the entire information of command, data and sender into a json object
	*
    *  @param  string  $cmd     The command to be broadcasted e.g. chat
    *  @param  string  $data    (Optional) The data to be broadcasted along with the command e.g. hello world
    *  @param  string  $sender   (Optional) the id of the user that is sending the message
	*/
	private function cmdwrap($cmd,$data,$sender=null) {
		$response=array('cmd'=>$cmd,'data'=>$data,'sender'=>$this->user->id);
		return json_encode($response);
	}


	/**
    *  broadcast
	*
	* This is used to send a message to all connected users
	*
    *  @param  string  $cmd     The command to be broadcasted e.g. chat
    *  @param  string  $data    (Optional) The data to be broadcasted along with the command e.g. hello world
    *  @param  boolean  $self   (Optional) true means the message should also be broadcasted to the sender
	*/
	public function broadcast($cmd,$data='',$self=false) {
		$data=$this->cmdwrap($cmd,$data);

		foreach($this->users as $user) {
			if(!$self && $user==$this->user) {continue;}
			$this->send($user, $data);
		}
	}
	
	/**
	* get_all_users
	*
	* Returns an array of all available user IDs
	*/
	public function get_all_users() {
		$users=Array();
		foreach($this->users as $user) {
			$users[]=$user->id;
		}
		return $users;
	}
	
	/**
	* listen
	*
	* This will initiate the websocket server and start waiting for client connections
	*/
	public function listen() {
		try {
			$this->run();
		}
		catch (Exception $e) {
			$this->stdout($e->getMessage());
		}
	}

}
