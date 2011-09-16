var ns4=(document.layers)?true:false;
var ie4=(document.all && !document.getElementById)?true:false;
var now=new Date();
now.hrs='00';
now.min='00';
now.sec='00';
var dclk;

var arrDay = new Array("CN","Th&#7913; 2 -","Th&#7913; 3 -","Th&#7913; 4 -","Th&#7913; 5 -","Th&#7913; 6 -","Th&#7913; 7 -");
if(language=='en')
	var arrDay = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Satuday");

function setclock(){
now=new Date(); now.hrs=now.getHours(); now.min=now.getMinutes(); now.sec=now.getSeconds();
now.hrs=((now.hrs<10)? "0" : "")+now.hrs;
now.min=((now.min<10)? "0" : "")+now.min;
now.sec=((now.sec<10)? "0" : "")+now.sec;
day = now.getDate();
myday = now.getDay();
month = now.getMonth() +1 ;
year = now.getUTCFullYear();

if(ns4){
dclk.document.open();
dclk.document.write('<div style="position:absolute; left:0px; top:0px; font-size:80px; color:white;"><center>'+now.hrs+':'+now.min+':'+now.sec+'</center></div>');
dclk.document.close();
}else dclk.innerHTML= now.hrs+':'+now.min + ' ' + arrDay[myday] + ' Ng&#224;y ' + day + '/' + month + '/' + year;

}

window.onload=function(){
  if(document.getElementById('timedate')==null)
 	return;
  setInterval('setclock()',300);
  dclk=(ns4)?document.layers['dclk']:(ie4)?document.all['dclk']:document.getElementById('timedate');
}

window.onresize=function(){
  if(ns4)setTimeout('history.go(0)',400);
}