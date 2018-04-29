$(document).ready(function(){
    SignalR.Chat.Start();
});

var SignalR = {
    Chat:{

        Start: function(){

            $.connection.hub.url = 'http://localhost:8080/signalr';


            // Start the connection.
            $.connection.hub.start({defaultUrl:false,transport: 'serverSentEvents'}).done(function(){
                SignalR.Chat.Init();
            });
        },
        Init: function(){
            // Reference the auto-generated proxy for the hub.
            var chat = $.connection.chatHub;
            chat.server.joinChat(1,"");

            // Create a function that the hub can call back to display messages
            chat.on('addNewMessageToPage', function(message,name){
                // Add the message to the page.
                $('#discussion').append('<li><strong>' + htmlEncode(name)
                    + '</strong>: ' + htmlEncode(message) + '</li>');
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

                // Call the Send method on the hub.
                chat.server.send($('#message').val(),$('#displayname').val(),$('#groupid').val());
            });
        }
    }
}

// This optional function html-encodes messages for display in the page.
function htmlEncode(value) {
    var encodedValue = $('<div />').text(value).html();
    return encodedValue;
}
