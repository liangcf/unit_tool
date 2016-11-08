<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/10/11
 * Time: 15:10
 */
require 'mial2.php';
$mail = new MySendMail();
$mail->setServer("smtp.163.com", "ownziji@163.com", "izvtcrzilqfqxyej");
$mail->setFrom("ownziji@163.com");
$mail->setReceiver("2271176865@qq.com");
//$mail->setReceiver("XXXXX@XXXXX");
//$mail->setCc("XXXXX@XXXXX");
//$mail->setCc("XXXXX@XXXXX");
//$mail->setBcc("XXXXX@XXXXX");
//$mail->setBcc("XXXXX@XXXXX");
//$mail->setBcc("XXXXX@XXXXX");
$mail->setMailInfo("test12345", "<b>jkfdsagfjksjklfsajklasjkl</b>");//, "sms.zip");
$mail->sendMail();
