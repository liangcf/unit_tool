/**
 * Created by AF on 2016/9/29.
 */
var get_url_parameter=function(){
    var strs;
    var url = location.search; //获取url中"?"符后的字串
    var theRequest = {};
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for(var i = 0; i < strs.length; i ++) {
            theRequest[strs[i].split("=")[0]]=decodeURI(strs[i].split("=")[1]);
        }
    }
    return theRequest;
};
var get_url_parameter_str=function(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null){
        return  unescape(decodeURI(r[2]));
    }else{
        return null;
    }
};
var p=function(v){
    console.log(v);
};