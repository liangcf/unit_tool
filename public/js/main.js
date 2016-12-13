/**
 * Created by AF on 2016/11/5.
 */

/* ajax请求 */
function _ajax_post(url, data, cb) {
    $.ajax({
        url: url,
        type: 'post',
        async: true,
        cache: false,
        data: data,
        dataType: 'json',
        success: function (ret) {
            if(cb){
                cb(ret);
            }
        },
        error: function (e) {
            p('ajax_post请求错误:', e);
        }
    });
}
/* 跳转请求*/
function _my_href(url) {
    window.location.href=url;
}

function p(ret) {
    console.log(ret);
}