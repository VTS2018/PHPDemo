<?php
include("./../includes/configure.php");

header("content-type:text/html;charset=utf-8");

$conn = mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD);

$sql = mysql_select_db(DB_DATABASE,$conn);

$qkey = "select * from ".DB_PREFIX."configuration where configuration_key='MODULE_PAYMENT_MYORDER_MD5KEY'";

$qacctno = "select * from ".DB_PREFIX."configuration where configuration_key='MODULE_PAYMENT_MYORDER_SELLER'";

$keyquery = mysql_query($qkey);

$acctnoquery = mysql_query($qacctno);

$key = mysql_fetch_array($keyquery);

$acctno = mysql_fetch_array($acctnoquery);

$md5key=  md5($key[configuration_value]."-".$acctno[configuration_value]);

if($_POST["ParAccNoSecKey"]==$md5key){
    file_put_contents("./payment.xml", $_POST["ParSplaceUrl"]);
    $array = array(
        "md5key"=>$md5key,
        "ParPGWID"=>$_POST["ParPGWID"],
        "status"=>"00",
    );
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $_POST["ParGetUrl"]); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $array); 
    curl_exec($ch); 
    curl_close($ch); 
}else{
    $array = array(
        "md5key"=>$md5key,
        "ParPGWID"=>$_POST["ParPGWID"],
        "status"=>"01",
    );
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $_POST["ParGetUrl"]); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $array); 
    curl_exec($ch); 
    curl_close($ch); 
}
?>