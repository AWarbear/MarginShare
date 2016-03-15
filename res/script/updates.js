$(document).ready(function() {
var updates = '{ "updates" : [' +
'{ "title":"Update 26.01" , "text":"Fixed chat scrolling up when reading old messages, tweaked the margins display, added a group button to margin report dialog" },' +
'{ "title":"First fixes" , "text":"Fixed an issue with viewing margins on a small screen and scrollbars showing when unnecessery" },' +
'{ "title":"Initial release" , "text":"Initial features include groups, margin sharing and chatting" }' +
']}';

var updateObj = JSON.parse(updates);
var html = "";
for(var i = 0; i < updateObj.updates.length; i++){
    html+='<a class="list-group-item">'+
            '<h4 class="list-group-item-heading">'+updateObj.updates[i]['title']+'</h4>'+
            '<p class="list-group-item-text">'+updateObj.updates[i]['text']+'</p>'+
        '</a>';
}
$('.update-area').html(html);
});