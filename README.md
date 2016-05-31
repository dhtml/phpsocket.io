## Introduction
PHPSocket.IO is a library written in PHP/JavaScript that allows real-time communication between a client and server via web sockets.

It was partly inspired by the NODE.JS version http://socket.io/ and works in a similar manner.

The PHP aspect of this library also make use of https://github.com/ghedipunk/PHP-Websockets as a base class.


It's created and developed by Anthony Ogundipe, CEO of [DHTMLExtreme](http://www.africoders.com).

## Features
* Ultra-fast communication between client and server.
* Simple to implement and configure especially into existing libraries.
* It can be integrated fairly easily with existing libraries.
* It can be used with mobile application development e.g. an android/ios client


## Quick Start
* Download the [zip master](https://github.com/dhtml/phpsocket.io/archive/master.zip)
* Extract the zip master into your web directory
* Open the examples folder to check out the basic and advanced functionalities.


Simple Usage:

```
You need to start the server from the commandline and not from inside the browser.

$: php server/socketio.php

From linux, you may want to use: nohup php server/socketio.php 

The server is meant to be running continuously so that it waits for connection, if you close the server, then the functionality will stop.

After you have successfully started the server, you can run the client from the browser examples/basic/index.html or examples/advanced/index.html

```

### Support
[Visit the project page](http://dhtml.github.io/phpsocket.io/) for documentation, configuration, and more advanced usage examples. 

### Android Integration
If you are an android user, you can find out how to integrate this utility into your android project by checking out [the project page](http://dhtml.github.io/phpsocket.io#android).

### Author

**Anthony Ogundipe** a.k.a dhtml

Special thanks to <a href="https://www.facebook.com/wasconet">Adewale Wilson</a> (wasconet) for his contributions to this library.

## Community
You can chat with us on facebook http://facebook.com/dhtml5 


## License

`phpsocket.io`'s code in this repo uses the MIT license, see our `LICENSE` file.
