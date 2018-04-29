var SignalR = {
    Chat:{

        Start: function(open){

            // Start the connection.
            $.connection.hub.url = 'http://localhost:8080/signalr';
            $.connection.hub.start({defaultUrl:false,transport: 'serverSentEvents'}).done(function(){
                SignalR.Chat.Init();
                if (open !== undefined){
                    //show chat window
                    $('#chat-window-container').fadeIn("slow");
                }
            });
        },
        Init: function(){
            // Reference the auto-generated proxy for the hub.
            var chat = $.connection.chatHub;

            //join chat

            $.ajax({
                url:$('#chat-user-deets').data('url'),
                type:"GET"
            }).done(function(data){
                chat.server.joinChat(data[0],data[1]);
            }).fail(function(){
                console.log("failed to retrieve user id")
            });

            // Create a function that the hub can call back to display messages
            chat.on('addNewMessageToPage', function(message,name){

                //show chat window
                $('#chat-window-container').fadeIn("slow");

                // Add the message to the page.
                $('#chat-window').append('<div class="row msg_container base_receive">\n' +
                    '                        <div class="col-md-2 col-xs-2 avatar">\n' +
                    '                            <img src="http://www.bitrebels.com/wp-content/uploads/2011/02/Original-Facebook-Geek-Profile-Avatar-1.jpg" class=" img-responsive ">\n' +
                    '                        </div>\n' +
                    '                        <div class="col-xs-10 col-md-10">\n' +
                    '                            <div class="messages msg_receive">\n' +
                    '                                '+'<p>'+message+'</p>\n' +
                    '                                <time datetime="2009-11-13T20:00">'+name+'</time>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>');
            });

            chat.on('updateClientGroup', function(group) {
                $('#groupid').val(group);
            });


            //make an ajax call to get username
            $('#displayname').val("Mateja");

            // Set initial focus to message input box.
            $('#message').focus();
            $('#message').keypress(function (e) {
                if (e.which == 13) {//Enter key pressed
                    $('#sendmessage').trigger('click');//Trigger search button click event
                }
            });

            $('#sendmessage').click(function () {


                //declare locals
                var message = $('#message').val();
                var name = $('#displayname').val();

                //clear message box
                $('#message').val('');

                // Call the Send method on the hub.
                chat.server.send(message,name,$('#groupid').val());

                //update self
                $('#chat-window').append('<div class="row msg_container base_sent">\n' +
                    '                        <div class="col-md-10 col-xs-10 ">\n' +
                    '                            <div class="messages msg_sent">\n' +
                    '                                <p>'+message+'</p>\n' +
                    '                                <time datetime="2009-11-13T20:00">'+name+'</time>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                        <div class="col-md-2 col-xs-2 avatar">\n' +
                    '                            <img src="http://www.bitrebels.com/wp-content/uploads/2011/02/Original-Facebook-Geek-Profile-Avatar-1.jpg" class=" img-responsive ">\n' +
                    '                        </div>\n' +
                    '                    </div>');
            });
        }
    }
}
