window.ImgCompress=function(input_id,img_id,widths,callback){var _width=widths;var _input=document.getElementById(input_id);$("#"+input_id).unbind('click');if(typeof(FileReader)==='undefined'){alert('该浏览器不支持此操作上传！请更换浏览器或者其他解决方案！')}else{_input.addEventListener('change',_read_file,false)}function _read_file(){var _file=this.files[0];if(!/image\/\w+/.test(_file.type)){alert("请上传正确的文件类型!");return false}var _reader=new FileReader();_reader.readAsDataURL(_file);_reader.onload=function(e){var _img=new Image,_quality=0.8,_canvas=document.createElement("canvas"),_drawer=_canvas.getContext("2d");_img.src=this.result;_canvas.width=_width;_canvas.height=_width*(_img.height/_img.width);_drawer.drawImage(_img,0,0,_canvas.width,_canvas.height);_img.src=_canvas.toDataURL("image/png",_quality);$("#"+img_id).attr('src',_img.src);if(callback){callback(_img.src)}}}};window.ImgCompressNoCall=function(input_id,img_id,widths){var _width=widths;var _input=document.getElementById(input_id);$("#"+input_id).unbind('click');if(typeof(FileReader)==='undefined'){alert('该浏览器不支持此操作上传！请更换浏览器或者其他解决方案！')}else{_input.addEventListener('change',_read_file,false)}function _read_file(){var _file=this.files[0];if(!/image\/\w+/.test(_file.type)){alert("请上传正确的文件类型!");return false}var _reader=new FileReader();_reader.readAsDataURL(_file);_reader.onload=function(e){var _img=new Image,_quality=0.8,_canvas=document.createElement("canvas"),_drawer=_canvas.getContext("2d");_img.src=this.result;_canvas.width=_width;_canvas.height=_width*(_img.height/_img.width);_drawer.drawImage(_img,0,0,_canvas.width,_canvas.height);_img.src=_canvas.toDataURL("image/png",_quality);$("#"+img_id).attr('src',_img.src)}}};