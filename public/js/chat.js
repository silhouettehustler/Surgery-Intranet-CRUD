$(document).ready(function(){
    $(document).on("change","#groupid",function(){
        //show chat window
        $('#chat-window-container').fadeIn("slow");
    });

    window.onbeforeunload = function () {
        SignalR.Chat.LeaveChat();
    };
});

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

                    // Add the message to the page.
                    $('#chat-window').append('<div class="row msg_container base_receive">\n' +
                        '                        <div class="col-xs-10 col-md-10">\n' +
                        '                            <div class="messages msg_receive">\n' +
                        '                                '+'<p>Please wait, someone will be with you shortly</p>\n' +
                        '                                <time datetime="2009-11-13T20:00">System</time>\n' +
                        '                            </div>\n' +
                        '                        </div>\n' +
                        '                    </div>');

                }
            });
        },
        LeaveChat: function(){
            // Reference the auto-generated proxy for the hub.
            var chat = $.connection.chatHub;

            // Call the leave method on the hub.
            chat.server.leaveChat();

            $('#chat-window-container').fadeOut("slow",function(){
                $('#chat-window').html("");
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

            chat.on('groupTerminated',function(){

                // Add the message to the page.
                $('#chat-window').append('<div class="row msg_container base_receive">\n' +
                    '                        <div class="col-xs-10 col-md-10">\n' +
                    '                            <div class="messages msg_receive">\n' +
                    '                                '+'<p>Chat ended...</p>\n' +
                    '                                <time datetime="2009-11-13T20:00">System</time>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>');
            });

            chat.on('groupEstablished',function(id){

                $.ajax({
                    url: '/home/getUser/'+id,
                    type:"GET"
                }).done(function(name){

                    // Add the message to the page.
                    $('#chat-window').append('<div class="row msg_container base_receive">\n' +
                        '                        <div class="col-xs-10 col-md-10">\n' +
                        '                            <div class="messages msg_receive">\n' +
                        '                                '+'<p>You are now chatting with '+name+'</p>\n' +
                        '                                <time datetime="2009-11-13T20:00">System</time>\n' +
                        '                            </div>\n' +
                        '                        </div>\n' +
                        '                    </div>');
                });

            });

            // Create a function that the hub can call back to display messages
            chat.on('addNewMessageToPage', function(message,name){

                //show chat window
                $('#chat-window-container').fadeIn("slow");

                var chatwindow = $('#chat-window')[0];
                chatwindow.scrollTop = chatwindow.scrollHeight;

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
                var groupholder = $('#groupid');
                groupholder.val(group);
                groupholder.change();
            });


            //get user name
            $('#displayname').val($('#user-name').text().trim());

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
                var group = $('#groupid').val();

                //clear message box
                $('#message').val('');

                if (group === ""){
                    $().toastmessage('showWarningToast', "All receptionists are busy at the moment, please give it a couple of seconds");
                    return false;
                }

                if (message === "")
                {
                    return false;
                }

                // Call the Send method on the hub.
                chat.server.send(message,name,group);

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

                var chatwindow = $('#chat-window')[0];
                chatwindow.scrollTop = chatwindow.scrollHeight;

            });


        }
    }
}
