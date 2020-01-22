<?php

require_once('websockets.php');

class echoServer extends WebSocketServer {

  function __construct($addr, $port, $bufferLength) {
    parent::__construct($addr, $port, $bufferLength);
//    $this->userClass = 'MyUser';
  }

  //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
  
  protected function process ($user, $message) {
    if($message == 'help')
    {
      $reply = 'Following commands are available - date, hi';
    }
    else if($message == 'date')
    {
      $reply = "Current date is " . date('Y-m-d H:i:s');
    }
    else if($message == 'hi')
    {
      $reply = "Hello user. This is a websocket server.";
    }
    else
    {
      $reply = "Thank you for the message : $message";
    }
    echo "Requested resource : " . $user->requestedResource . "\n";
    
    //$this->send($user,$reply);
    $this->transmitir($message,$user);
  }
  
  protected function connected ($user) {
    // Do nothing: This is just an echo server, there's no need to track the user.
    // However, if we did care about the users, we would probably have a cookie to
    // parse at this step, would be looking them up in permanent storage, etc.
    //$welcome_message = 'Bienvenido al servidor.';
    //$this->send($user, $welcome_message);
  }
  
  protected function closed ($user) {
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.
  }
}


//$echo = new echoServer("10.32.8.3","9005",1024);
$echo = new echoServer("10.32.9.8","9006",1024);

try {
  $echo->run();
}
catch (Exception $e) {
  $echo->stdout($e->getMessage());
}
