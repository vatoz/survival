var conn;
var NextAction;
function chcidata(){
	
	conn.send("+++");
	}

$(function(){
	conn = new WebSocket('ws://192.168.1.100:8081');
	conn.onopen = function(e) {
    console.log("Connection established!");
	chcidata();
	//setInterval(chcidata,50);
};

conn.onclose=function (e){
	
	}

var stav=0;
var cnt=0;
conn.onmessage = function(e) {
//    console.log(e.data);
    var result=e.data.split(",");
    cnt++;
  //  console.log(cnt);
	
	   if(  stav==1){
		if(result[5]==0) Save();
}

stav=result[5];
  
    jQuery("#vote_1").text(result[0]);
    jQuery("#vote_2").text(result[1]);
    jQuery("#vote_3").text(result[2]);
    jQuery("#vote_4").text(result[3]);
    jQuery("#vote_5").text(result[4]);
     
  
    
    jQuery("#form_vote_4").val(result[3]);
    jQuery("#form_vote_5").val(result[4]);
    jQuery("#form_vote_1").val(result[0]);
    jQuery("#form_vote_2").val(result[1]);
    jQuery("#form_vote_3").val(result[2]);
    chcidata();
    
};
	
	});


function Save(){
	document.forms['survival'].submit();
}


