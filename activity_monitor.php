<?php
set_time_limit(0);
require_once("libraries/TeamSpeak3/TeamSpeak3.php");
$ts3_VirtualServer = TeamSpeak3::factory("serverquery://username:password@IPADDRESS:10011/?server_port=9987");
$ts3_VirtualServer->selfUpdate(array('client_nickname'=>"CustomBot | Activity monitor by Nicer"));
checker:
try{
$conn = mysqli_connect("DBIP","DBLOGIN","DBPASSWORD","DBNAME");
$ts3_VirtualServer->clientListReset();
foreach($ts3_VirtualServer->clientList() as $client)
{
if($client['client_type']) continue;
if($client['client_unique_identifier'] == "cFqen1JSR3613Q0pIfMzdj99g/Y=" || $client['client_unique_identifier'] == "fBJaE91cwpst+Z/HVpyX9FAROSg=") {continue;}
if(count($client->getClones()) >= 2) {continue;}
$oldonline = mysqli_query($conn,"SELECT * FROM `activity` WHERE `uid` = '".$client['client_unique_identifier']."'");
$row = mysqli_fetch_assoc($oldonline);
if($row['online'] == "")
{
$nickname = strip_tags($client['client_nickname']);
$uid = $client['client_unique_identifier'];
$online = 1 ;
mysqli_query($conn,"INSERT INTO `activity` (`id`,`nickname`, `uid`, `online`) VALUES (NULL,'".$nickname."', '".$uid."', '".$online."')");
}
else
{
$rowid = $row['id'];
$rownick = $row['nickname'];
$rowuid = $row['uid'];
$rowonline = $row['online'] + 1;
mysqli_query($conn,"UPDATE `activity` SET `online`='".$rowonline."',`nickname`='".strip_tags($client['client_nickname'])."' WHERE id=".$rowid."");
}
}
mysqli_close($conn);
}
catch(exception $e){
	sleep(60);
	goto checker;
}
sleep(60);
goto checker;
?>