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


(function($){
    $.websocket = function(socketurl,settings){
        // settings
		var commands={};
		var socket;
        var config = {
			open: function(){},
			close: function(){},
			message: function(){},
			options: {},
			events: {}
        };

        if ( settings ){$.extend(config, settings);}
		
		this.on=function(cmd,cb) {
			commands[cmd]=cb;
		};
		
		this.trigger=function(cmd,params,sender) {
		 if(commands.hasOwnProperty(cmd)) {
			commands[cmd](params,sender);
		 }
		};
		
		this.log=function(data) {
		 console.log(data);
		};
		
		this.quit=function() {
			if (socket != null) {
				socket.close();
				socket=null;
				}
		};

		this.reconnect=function() {
			this.quit();
			this.listen();
		};

		this.cmdwrap=function(cmd,data,broadcast) {
			response={'cmd':cmd,'data':data,'sender':socket.user,'broadcast':broadcast};
			response=JSON.stringify(response);
			return response;
		};
		
		this.emit=function(cmd,data) {
			if (socket == null) {return;}
			message=this.cmdwrap(cmd,data,false);
			
			try { 
				socket.send(message); 
			} catch(ex) { 
				this.log(ex); 
			}

		};
		
		this.push=function(cmd,data,to) {
			if (socket == null) {return;}
			message=this.cmdwrap(cmd,{to:to,data:data},false);
			
			try { 
				socket.send(message); 
			} catch(ex) { 
				this.log(ex); 
			}

		};
		
		this.broadcast=function(cmd,data) {
			if (socket == null) {return;}
			message=this.cmdwrap(cmd,data,true);
			
			try { 
				socket.send(message); 
			} catch(ex) { 
				this.log(ex); 
			}

		};


		this.listen=function () {
			try {
				socket = new WebSocket(socketurl);
				socket.log=this.log; //pass function inside `class`
				socket.trigger=this.trigger;
				
				socket.onopen    = function(msg) {
					this.trigger('open',msg);
				};
				socket.onmessage = function(msg) {
					var obj = jQuery.parseJSON(msg.data);
					switch(obj.cmd) {
					case "connect":
					socket.user=obj.data;
					//this.log("Connected");
					break;
					case "disconnect":
					socket.user=null;
					//this.log("Disconnected");
					break;
					case "close":
					socket.user=null;
					break;
					}
					this.trigger(obj.cmd,obj.data,obj.sender);
				};
				socket.onclose   = function(msg) { 
					this.trigger('close',msg);
				};
			}
			catch(ex){ 
				this.log(ex); 
			}
			
			
		};
 
        return this;
    };
})(jQuery);
