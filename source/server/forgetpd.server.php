<?php

require("../inc/smtp.class.php");
require_once("../inc/user.class.php");


$user = new User();
$mail = $_POST['mail'];
$tag = 'mail';
$result = $user->getinfo_x($mail,$tag);
if($result == 'nothing' || $result==false){
	$com =array("status"=>false,"data"=>"该邮箱没注册!");
	$seq = json_encode($com);
	echo $seq;
	exit;
}

//$userinfo = mysql_fetch_array($result);
$userinfo = $result[0];
$getpasstime = time()+24*3600;
$str_time = (string)$getpasstime;
$str = $userinfo['uid'].$userinfo['password'].$str_time;
$token = md5($str);
$url = "http://121.40.74.70/imooc/reset_key.php?uid=".$userinfo['uid'] . "&token=".$token."&time=".$str_time;
$time = date('Y-m-d H:i');

$smtpserver = "smtp.exmail.qq.com";
$smtpserverport = 25;
$smtpusermail = "fatty_liao@xiyoulinux.org";
$smtpemailto = $userinfo['mail'];
$smtpuser = "fatty_liao@xiyoulinux.org";
$smtppass = "Niangnan1022";

$mailsubject = "找回密码";
$mailbody =  "亲爱的".$name."：\n\n您在".$time."提交了找回密码请求。请点击下面的链接重置密码
（链接24小时内有效）。\n".$url."\n如果您确认您没有进行此操作，请忽略此邮件内容。\n本邮件为系统自动发送,请勿直接回复。"; 
$mailtype = "html";
$smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass);
//$smtp->debug = TRUE;
$info = $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);

if($info == 1){
	$conseq = array("status"=>"true","data"=>'发送成功');
	$com = json_encode($conseq);
	print $com;
} else {
	$com =array("status"=>false,"data"=>"邮箱信息不正确!");
	$seq = json_encode($com);
	echo $seq;
}

?>
