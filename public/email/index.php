<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/10/11
 * Time: 14:19
 */

require_once('class.phpmailer.php');
require_once("class.smtp.php");
$mail  = new PHPMailer();
$mail->SMTPDebug = 1;
$mail->CharSet    ="UTF-8";                 //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
$mail->IsSMTP();                            // 设定使用SMTP服务
$mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
$mail->SMTPSecure = "ssl";                  // SMTP 安全协议
$mail->Host       = "smtp.163.com";       // SMTP 服务器
$mail->Port       = 465;                    // SMTP服务器的端口号
$mail->Username   = "ownziji@163.com";  // SMTP服务器用户名
$mail->Password   = "izvtcrzilqfqxyej";        // SMTP服务器密码
$mail->SetFrom('ownziji@163.com', 'ownziji');    // 设置发件人地址和名称
$mail->AddReplyTo("邮件回复人地址","邮件回复人名称");
// 设置邮件回复人地址和名称
$mail->Subject    = '这是测试的邮件2';                     // 设置邮件标题
$mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
// 可选项，向下兼容考虑
$mail->MsgHTML('这是测试的邮件2');                         // 设置邮件内容
$mail->AddAddress('2271176865@qq.com', "2271176865");
$mail->AddAttachment("close.png"); // 附件
if(!$mail->Send()) {
    echo "发送失败：" . $mail->ErrorInfo;
} else {
    echo "恭喜，邮件发送成功！";
}