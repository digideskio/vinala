function fade0(){$("#content").fadeTo("slow",1),$({blurRadius:0}).animate({blurRadius:5},{duration:600,easing:"linear",step:function(){console.log(this.blurRadius),$("#bg").css({"-webkit-filter":"blur("+this.blurRadius+"px)",filter:"blur("+this.blurRadius+"px)"})}})}function fade1(){$("#welcom").fadeTo("slow",1),clearInterval(Timer1)}function fade2(){$("#bottom_panel").fadeTo("slow",1),$("#bottom_panel_2").fadeTo("slow",1),$("#bottom_owner").fadeTo("slow",1),clearInterval(Timer2)}function fade3(){$("#hello_logo").fadeTo("slow",1),clearInterval(Timer3)}$(document).ready(function(){function e(){$("#content").fadeTo("slow",1),clearInterval(n)}var n;Timer0=setInterval(function(){},200),n=setInterval(function(){e()},400)}),fade0();var Timer3=setInterval(function(){fade3()},500),Timer1=setInterval(function(){fade1()},600),Timer2=setInterval(function(){fade2()},1e3);