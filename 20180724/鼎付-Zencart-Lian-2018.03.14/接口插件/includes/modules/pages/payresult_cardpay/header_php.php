<?php

  if (isset ($zco_notifie))
  $zco_notifier->notify('NOTIFY_HEADER_START_PAYMENT_RESULT');
  $messageStack->reset();
  require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);
    
  //如果订单为空,直接跳转到网店首页  
  if(!isset($_REQUEST["orderNo"]) || empty($_REQUEST["orderNo"])) {
	  zen_redirect(zen_href_link(FILENAME_DEFAULT));
  }

  //获取返回信息
  $merNo         = $_REQUEST['merNo'];
  $signKey       = trim(MODULE_PAYMENT_CARDPAY_SIGNKEY);
  $gatewayNo     = $_REQUEST['gatewayNo'];
  $tradeNo       = $_REQUEST['tradeNo'];
  $orderNo       = $_REQUEST['orderNo'];
  $orderCurrency = $_REQUEST['orderCurrency'];
  $orderAmount   = $_REQUEST['orderAmount'];
  $orderInfo     = $_REQUEST['orderInfo'];
  $orderStatus   = $_REQUEST["orderStatus"];
  $returnType    = (isset($_REQUEST["returnType"]) && !empty($_REQUEST["returnType"])) ? $_REQUEST["returnType"] : '-1';
  $signInfo      = strtoupper($_REQUEST["signInfo"]);
  $sha256Src     = $merNo.$gatewayNo.$tradeNo.$orderNo.$orderCurrency.$orderAmount.$orderStatus.$orderInfo.$signKey;
  $mysign        = strtoupper(hash("sha256",$sha256Src));

  //订单号转换
  //$orderId  = $orderNo;
  //$orderPre = trim(MODULE_PAYMENT_CARDPAY_ORDER_PRE);
  //if (zen_not_null($orderPre)) {
  //    $last = strrpos($orderNo, "-");
  //      if ($last > 0) {
  //          $orderNo = substr($orderId, $last + 1);
  //     }
  //}

  //查询订单状态
  //$check_order_status = $db->Execute("select * from " . TABLE_ORDERS . " WHERE orders_id = '" .$orderId . "'" );
  //$order_status = $check_order_status->fields['orders_status'];
  //if(empty($order_status)) {
  //    zen_redirect(zen_href_link(FILENAME_DEFAULT));
  //}

  //订单记录信息
  //$sql_date_order_status['date_purchased'] = 'now()';
  //$sql_data_array['orders_id']  = $orderNo;
  //$sql_data_array['date_added'] = 'now()';
  //$sql_data_array['customer_notified'] = '1';
  //$sql_data_array['comments']   = "TradeNo:" . $tradeNo . " || Order No.:" . $orderNo ."  || Amount:" . $orderAmount . $orderCurrency.
  //                                " || Order Info:" . $orderInfo;
  //订单初始状态
  //$init_status = 1;
  //if((int)MODULE_PAYMENT_CARDPAY_ORDER_STATUS_ID > 0) {
  //    $init_status = MODULE_PAYMENT_CARDPAY_ORDER_STATUS_ID;
  //}

  //是否已经清除缓存(支付成功或者待处理将清除缓存)
  //$isClearSession = true;

  //判断订单状态,并修改数据库
  if($mysign == $signInfo){
      if($orderStatus == '1') {
          //如果网店系统该笔订单状态为初始状态、未支付(9990)、处理中(9991)、失败(9992),则修改网店订单状态为支付成功.
		  //if($order_status == $init_status || $order_status =='9990' || $order_status =='9991' || $order_status == '9992') {
		   // $sql_data_array['orders_status_id'] = '9992';
		   // $sql_date_order_status['orders_status'] = '9992';
		    //zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array,$action = 'insert');
           // zen_db_perform(TABLE_ORDERS, $sql_date_order_status,$action = 'update','orders_id ='. $orderId);
		  //}
          $messageStack->add_session('payment_result', CARDPAY_PAYRESULT_SUCCESS , 'success');
	   }elseif($orderStatus == '-1' || $orderStatus == '-2'){
          //如果网店系统该笔订单状态为初始状态或者未支付(9990),则修改网店订单状态为处理中.
	   	  //if($order_status == $init_status || $order_status =='9990') {
			//$sql_data_array['orders_status_id'] = '9991';
			//$sql_date_order_status['orders_status'] = '9991';
			//zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array,$action = 'insert');
			//zen_db_perform(TABLE_ORDERS, $sql_date_order_status,$action = 'update','orders_id ='. $orderId);
		  //}
          $messageStack->add_session('payment_result', CARDPAY_PAYRESULT_PROCESSING, 'success');
       }elseif($orderStatus == '0'){
          //如果网店系统该笔订单状态为初始状态、未支付(9990)、处理中(9991),则修改网店订单状态为支付失败.
	   	  //if($order_status == $init_status || $order_status =='9990' || $order_status =='9991') {
          //  $sql_data_array['orders_status_id'] = '9992';
			//$sql_date_order_status['orders_status'] = '9992';
			//zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array,$action = 'insert');
			//zen_db_perform(TABLE_ORDERS, $sql_date_order_status,$action = 'update','orders_id ='. $orderId);
		  //}
		  //$isClearSession = false;
          $messageStack->add_session('payment_result', CARDPAY_PAYRESULT_FAIL, 'error');
	   }
	} else { //数据校验失败
        // $isClearSession = false;
         $messageStack->add_session('payment_result', CARDPAY_PAYRESULT_WARNING, 'error');
    }

    //如果是异步返回,输入"ok"表示已经获取到支付结果.
    //if($returnType == '3') {
    //    echo 'ok';
    //    exit;
    //}

    // 释放session对象(只有支付成功或者待处理才清空session值)
    //if($isClearSession) {
      //  $_SESSION['cart']->reset(true);
      //  unset($_SESSION['order_number_created']);
     //   unset($_SESSION['CARDPAY_order_id']);
     //   unset($_SESSION['sendto']);
     //   unset($_SESSION['billto']);
     //   unset($_SESSION['payment']);
    //   unset($_SESSION['shipping']);
    //}
    //记录支付结束日志
    error_log(date("[Y-m-d H:i:s]")."\t" . ">>>>>>>>>> End Cardpay Payment >>>>>>>>>>"  ."\r\n", 3,'cardpay.log');
?>