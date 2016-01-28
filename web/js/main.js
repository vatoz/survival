var conn;
var NextAction;
$(function(){
	conn = new WebSocket('ws://localhost:8081');
	conn.onopen = function(e) {
    console.log("Connection established!");
	conn.send("ahoj");
};

conn.onmessage = function(e) {
//    console.log(e.data);
    var result=e.data.split(",")
    jQuery("#vote_1").text(result[0]);
    jQuery("#form_vote_1").attr("value",text(result[0]));
    jQuery("#vote_2").text(result[1]);
    jQuery("#form_vote_2").attr("value",text(result[1]));
    jQuery("#vote_3").text(result[2]);
    jQuery("#form_vote_3").attr("value",text(result[2]));
    jQuery("#vote_4").text(result[3]);
    jQuery("#form_vote_4").attr("value",text(result[3]));
    jQuery("#vote_5").text(result[4]);
    jQuery("#form_vote_5").attr("value",text(result[4]));
    conn.send("ahoj");
};
	
	});


function Save(){
	document.forms['survival'].submit();
}
