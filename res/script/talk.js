$(document).ready(function(){
    startChatUpdate();
});

function startChatUpdate(){
    updateChat();
    setTimeout(startChatUpdate, 5000);
}

function sendMessage(){
    var message = $("#message").val();
    $.ajax({
        method: "POST",
        url: "talk.php",
        data: { message: message}
    }).done(function(html){
        updateChat();
        $("#message").val('');
    });
}

function updateChat(){
    $.post("talkbox.php", function(data){
        $('#chatList').html(data);
        if($('#chatList').scrollTop() === 0)
             $("#chatList").scrollTop(0);
    });
   
}

