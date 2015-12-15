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

require_once(__DIR__.'/websockets.php');

$socket=new PHPWebSockets("0.0.0.0","9000");

$socket->on("connect",function($socket,$uid) {
	$socket->emit('connect',$uid);
});

$socket->on("add user",function($socket,$username,$userid) {
	$socket->username=$username;
	
	//add the client's username to the global list
    $socket->usernames["$username"] = $username;
    $socket->socketID["$userid"] = $username;
    $socket->numUsers++;
	
	//inform me that my login was successful
    $socket->emit('login', array(
      'numUsers'=>$socket->numUsers
    ));

	//broadcast to others that i have joined
	$socket->broadcast('user joined', array(
      'username'=>$socket->username,
      'numUsers'=>$socket->numUsers
    ));
	
	$socket->addedUser=true;

});


  // when the client emits 'typing', we broadcast it to others
  $socket->on('typing', function ($socket,$data) {

	$socket->broadcast('typing', array(
      'username'=>$socket->socketID[$socket->user->id],
    ));
	
  });

  // when the client emits 'stop typing', we broadcast it to others
  $socket->on('stop typing', function ($socket,$data) {

	$socket->broadcast('stop typing', array(
      'username'=>$socket->socketID[$socket->user->id],
    ));
	
  });


  // when the client emits 'new message', this listens and executes
  $socket->on('new message', function ($socket,$data,$sender) {
    // we tell the client to execute 'new message'
    $socket->broadcast('new message', array(
      'username'=>$socket->socketID[$socket->user->id],
      'message'=>$data
    ));
	
  });


$socket->on("disconnect",function($socket,$data) {
  // remove the username from global usernames list
    if ($socket->addedUser) {
		unset($socket->usernames[$socket->username]);
		unset($socket->socketID[$socket->user->id]);
		$socket->numUsers--;

      // echo globally that this client has left
      $socket->broadcast('user left', array(
        'username'=> $socket->username,
        'numUsers'=> $socket->numUsers
      ));
	  
    }
 
});


$socket->listen();
?>