/**
 * @author David Lindkvist
 */

if (typeof(demoNamespace) === 'undefined') {
	demoNamespace = {};
}

/**
 * Constructs a DemoChat object
 * @constructor
 * @return {DemoChat} A new DemoChat instance
 */
demoNamespace.DemoChat = function () { 
	
	this.latestMessageID = null;
	
	/**
	 * Add text to chat window
	 * @private
	 * @param {String} message Test message to add to chat window 
	 */
	function addMsg(message)
	{
		var li = $('<li>' + message + '</li>').hide().fadeIn();
		$('#messages').append(li);
	}
	
	/**
	 * Wire up events using specified WebSocket
	 * @param {WebSocket} ws
	 */
	this.connect = function (ws) {
		
		var instance = this;
			
		ws.onopen = function (e) {

			if (ws.send('client joined')) {
				$('#inputMsg').change(function (e) {
					ws.send($(this).val());
					$(this).val('');
				});
			}
			else {
				addMsg('connection error');
			}
		};
		
		/**
		 * @param {MessageEvent} messageEvent
		 */
		ws.onmessage = function (messageEvent) {
	
			var msgs = $.parseJSON(messageEvent.data);
			if ($.isArray(msgs)) {
				for (var i in msgs) {
					addMsg(msgs[i].text);
					instance.latestMessageID = msgs[i].messageID;
				}
				
			}
			else if (msgs !== null) {
				addMsg(msgs.text);
				instance.latestMessageID = msgs.messageID;
			}
		};
		
		ws.onclose = function (e) {
			addMsg('socket closed by server');
		};

		ws.onerror = function (e) {
			addMsg('unknown error - from event bind listener');
		};	
	}
	
	// disable form submit
	$('#chat').submit(function (e) {e.preventDefault();});	
	
};

demoNamespace.DemoChat.prototype.connect = function (ws) {
	//this.wireEvents(ws);
	this.addMsg("TJAA");
};
