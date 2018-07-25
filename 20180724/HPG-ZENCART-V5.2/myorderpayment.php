<?php
    function _post($url, $data){
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch ,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch ,CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
    }

    $response = _post(file_get_contents('./paymenturl/payment.xml'),$_POST);
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    $url = $http_type . $_SERVER['HTTP_HOST'];//提交地址
    $aaa = explode('&',$response);
    $Par1 = explode('Par1=', $aaa[0]);
    $Par2 = explode('Par2=', $aaa[1]);
    $Par3 = explode('Par3=', $aaa[2]);
    $Par4 = explode('Par4=', $aaa[3]);
    $Par5 = explode('Par5=', $aaa[4]);
    $Par6 = explode('Par6=', $aaa[5]);
    $HashValue = explode('HashValue=', $aaa[6]);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>myorderpayment</title>
</head>
<body>
    <div style="font-size:24px;font-weight: bold;" widht="100%" height="50px;" align="center">Please wait. . .</div>
    <form action="<?php echo $url;?>/index.php?main_page=checkout_process" id="MyOder_payment_checkout" method="POST">
        <input name="Par1" value="<?php echo $Par1[1];?>" type="hidden"/>
        <input name="Par2" value="<?php echo $Par2[1];?>" type="hidden"/>
        <input name="Par3" value="<?php echo $Par3[1];?>" type="hidden"/>
        <input name="Par4" value="<?php echo $Par4[1];?>" type="hidden"/>
        <input name="Par5" value="<?php echo $Par5[1];?>" type="hidden"/>
        <input name="Par6" value="<?php echo $Par6[1];?>" type="hidden"/>
        <input name="HashValue" value="<?php echo $HashValue[1];?>" type="hidden"/>
    </form>
    <script type="text/javascript">
        document.getElementById("MyOder_payment_checkout").submit();
    </script>
</body>
</html>
