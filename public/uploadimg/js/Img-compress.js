/**
 *
 * @param input_id input_file_id
 * @param img_id 需要显示出来的id
 * @param widths 需要的图片高度
 * @param callback 回调函数
 * @constructor
 */
window.ImgCompress=function(input_id,img_id,widths,callback){
    var _width=widths;
    var _input = document.getElementById(input_id);
    $("#"+input_id).unbind('click');
    if (typeof(FileReader) === 'undefined') {
        alert('该浏览器不支持此操作上传！请更换浏览器或者其他解决方案！');
    } else {
        _input.addEventListener('change', _read_file, false);
    }
    function _read_file() {
        var _file = this.files[0];
        if (!/image\/\w+/.test(_file.type)) {
            alert("请上传正确的文件类型!");
            return false;
        }
        var _reader = new FileReader();
        _reader.readAsDataURL(_file);
        _reader.onload = function (e) {
            var _img = new Image,//_width图片resize宽度
                _quality = 0.8,//图像质量
                _canvas = document.createElement("canvas"),
                _drawer = _canvas.getContext("2d");
            _img.src = this.result;
            _canvas.width = _width;
            _canvas.height = _width * (_img.height / _img.width);
            _drawer.drawImage(_img, 0, 0, _canvas.width, _canvas.height);
            _img.src = _canvas.toDataURL("image/png", _quality);
            $("#"+img_id).attr('src',_img.src);
            if(callback){
                callback(_img.src);
            }
        };
    }
};
/**
 *
 * @param input_id input_file_id
 * @param img_id 需要显示出来的id
 * @param widths 需要的图片高度
 * @constructor
 */
window.ImgCompressNoCall=function(input_id,img_id,widths){
    var _width=widths;
    var _input = document.getElementById(input_id);
    $("#"+input_id).unbind('click');
    if (typeof(FileReader) === 'undefined') {
        alert('该浏览器不支持此操作上传！请更换浏览器或者其他解决方案！');
    } else {
        _input.addEventListener('change', _read_file, false);
    }
    function _read_file() {
        var _file = this.files[0];
        if (!/image\/\w+/.test(_file.type)) {
            alert("请上传正确的文件类型!");
            return false;
        }
        var _reader = new FileReader();
        _reader.readAsDataURL(_file);
        _reader.onload = function (e) {
            var _img = new Image,//_width图片resize宽度
                _quality = 0.8,//图像质量
                _canvas = document.createElement("canvas"),
                _drawer = _canvas.getContext("2d");
            _img.src = this.result;
            _canvas.width = _width;
            _canvas.height = _width * (_img.height / _img.width);
            _drawer.drawImage(_img, 0, 0, _canvas.width, _canvas.height);
            _img.src = _canvas.toDataURL("image/png", _quality);
            $("#"+img_id).attr('src',_img.src);
        };
    }
};
function getBase64Images(img) {
    var canvas = document.createElement("canvas");
    canvas.width = img.width;
    canvas.height = img.height;
    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0, img.width, img.height);
    var ext = img.src.substring(img.src.lastIndexOf(".")+1).toLowerCase();
    var dataURLs = canvas.toDataURL("image/"+ext);
    return dataURLs;
}