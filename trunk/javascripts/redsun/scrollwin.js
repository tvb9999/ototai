var ScrollWin = {
  w3c : document.getElementById,
  iex : document.all,
  scrollLoop : false,
  scrollInterval : null, // setInterval id
  currentBlock : null,   // object reference
  getWindowHeight : function(){
    if(this.iex) return (document.documentElement.clientHeight) ? document.documentElement.clientHeight : document.body.clientHeight;
    else return window.innerHeight;
  },
  getScrollLeft : function(){
    if(this.iex) return (document.documentElement.scrollLeft) ? document.documentElement.scrollLeft : document.body.scrollLeft;
    else return window.pageXOffset;
  },
  getScrollTop : function(){
    if(this.iex) return (document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.body.scrollTop;
    else return window.pageYOffset;
  },
  getElementYpos : function(el){
    var y = 0;
    while(el.offsetParent){
      y += el.offsetTop
      el = el.offsetParent;
    }
    return y;
  },
  scroll : function(num){
    if(!this.w3c){
      location.href = "#"+this.anchorName+num;
      return;
    }
    if(this.scrollLoop){
      clearInterval(this.scrollInterval);
      this.scrollLoop = false;
      this.scrollInterval = null;
    }
    if(this.currentBlock != null) this.currentBlock.className = this.offClassName;
    this.currentBlock = document.getElementById(this.blockName+num);
    this.currentBlock.className = this.onClassName;
    var doc = document.getElementById(this.containerName);
    var documentHeight = this.getElementYpos(doc) + doc.offsetHeight;
    var windowHeight = this.getWindowHeight();
    var ypos = this.getElementYpos(this.currentBlock);
    if(ypos > documentHeight - windowHeight) ypos = documentHeight - windowHeight;
    this.scrollTo(0,ypos);
  },
  scrollTo : function(x,y){
    if(this.scrollLoop){
      var left = this.getScrollLeft();
      var top = this.getScrollTop();
      if(Math.abs(left-x) <= 1 && Math.abs(top-y) <= 1){
        window.scrollTo(x,y);
        clearInterval(this.scrollInterval);
        this.scrollLoop = false;
        this.scrollInterval = null;
      }else{
        window.scrollTo(left+(x-left)/2, top+(y-top)/2);
      }
    }else{
      this.scrollInterval = setInterval("ScrollWin.scrollTo("+x+","+y+")",100);
      this.scrollLoop = true;
    }
  }
};

// ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
/*
using the following line, IE/PC returns an incorrect number when getting the document height.
var document_height = document.all ? document.body.offsetHeight : window.document.height;
To fix this problem, a container div is wrapped around the content so the correct height
can be determined.
*/

// Edit these variables

ScrollWin.containerName = "container"; // The id name of the div containing the content
ScrollWin.anchorName    = "anchor";    // The alpha portion of the anchor names
ScrollWin.blockName     = "block";     // The alpha portion of the content blocks
ScrollWin.onClassName   = "active";    // The CSS class name for the 'on' state
ScrollWin.offClassName  = "visited";   // The CSS class name for the 'off' state



<!-- Paste this code into an external JavaScript file named: popUp.js  -->

/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Travis Beckham :: http://www.squidfingers.com | http://www.podlob.com */

/* Created by Jeremy Keith
  http://domscripting.com */
function doPopups() {
  if (!document.getElementsByTagName) return false;
  var links=document.getElementsByTagName("a");
  for (var i=0; i < links.length; i++) {
    if (links[i].className.match("popup")) {
      links[i].onclick=function() {
        // Below - to open a full-sized window, just use: window.open(this.href);
        window.open(this.href, "", "top=40,left=40,width=550,height=450");
        return false;
      }
    }
  }
}
window.onload=doPopups;

