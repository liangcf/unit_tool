/**
 * Created by AF on 2016/11/11.
 */

var count_down='2016-12-11 10:00:00';
/* 倒计时时间函数 */
function get_r_time(){
    var EndTime= new Date(count_down); //截止时间 前端路上 http://www.51xuediannao.com/qd63/
    var NowTime = new Date();
    var t =EndTime.getTime() - NowTime.getTime();
    /*var d=Math.floor(t/1000/60/60/24);
     t-=d*(1000*60*60*24);
     var h=Math.floor(t/1000/60/60);
     t-=h*60*60*1000;
     var m=Math.floor(t/1000/60);
     t-=m*60*1000;
     var s=Math.floor(t/1000);*/

    var d=Math.floor(t/1000/60/60/24);
    var h=Math.floor(t/1000/60/60%24);
    var minute=Math.floor(t/1000/60%60);
    var second=Math.floor(t/1000%60);

    var hour=h+d*24;
    if(10<=hour&&hour<100){
        hour='0'+hour.toString();
    }
    if(1<=hour&&hour<10){
        hour='00'+hour.toString();
    }
    if(hour==0){
        hour='000';
    }
    if(1<=minute&&minute<10){
        minute='0'+minute.toString();
    }
    if(minute==0){
        minute='00';
    }
    if(second<10&&second>=1){
        second='0'+second.toString();
    }
    if(second==0){
        second='00';
    }
    $("#hour_").html(hour);
    $("#minute_").html(minute);
    $("#sec_").html(second);
}
$(function(){
    setInterval(get_r_time,1000);
});
