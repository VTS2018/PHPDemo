<?php
define('FILENAME_PAYRESULT_CARDPAY', 'payresult_cardpay');   //定义支付返回地址
define('FILENAME_NOTIFY_CARDPAY', 'notify_cardpay');         //定义支付通知地址

class cardpay{
    var $code, $title, $description, $enabled, $trade_no, $form_action_url;

    /**
     * order status setting for pending orders
     * @var int
     */
    var $order_pending_status = 1;

    /**
     * order status setting for initial state
     * @var int
     */
    var $order_status = DEFAULT_ORDERS_STATUS_ID;

    /**
     * 构造器
     */
    function cardpay(){
        global $order;
        $this->code        = 'cardpay';
        $this->title       = MODULE_PAYMENT_CARDPAY_TEXT_TITLE;
        $this->description = MODULE_PAYMENT_CARDPAY_TEXT_DESCRIPTION;
        $this->sort_order  = MODULE_PAYMENT_CARDPAY_SORT_ORDER;
        $this->enabled     = ((MODULE_PAYMENT_CARDPAY_STATUS == 'True') ? true : false);
        if ((int) MODULE_PAYMENT_CARDPAY_ORDER_STATUS_ID > 0) {
            $this->order_status = MODULE_PAYMENT_CARDPAY_ORDER_STATUS_ID;
        }
        if (is_object($order)) {
            $this->update_status();
        }
        if (is_object($order)) {
            $this->update_status();
        }
        // 支付网关提交地址;
        $this->form_action_url = trim(MODULE_PAYMENT_CARDPAY_HANDLER);
    }

    /**
     * 更新订单状态
     */
    function update_status(){
        global $order, $db;
        if (($this->enabled == true) && ((int) MODULE_PAYMENT_CARDPAY_ZONE > 0)) {
            $check_flag  = false;
            $check_query = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_CARDPAY_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
            while (!$check_query->EOF) {
                if ($check_query->fields['zone_id'] < 1) {
                    $check_flag = true;
                    break;
                } elseif ($check_query->fields['zone_id'] == $order->billing['zone_id']) {
                    $check_flag = true;
                    break;
                }
                $check_query->MoveNext();
            }
            if ($check_flag == false) {
                $this->enabled = false;
            }
        }
    }

    /**
     * JS validation which does error-checking of data-entry if this module is selected for use
     * (Number, Owner, and CVV Lengths)
     * @return string
     */
    function javascript_validation(){
        $js = $this->func_init_JS() ."\n";
        $js .= ' var cardpay_Today = new Date();' . "\n" . ' var cardpay_Now_Hour = cardpay_Today.getHours();' . "\n" . ' var cardpay_Now_Minute = cardpay_Today.getMinutes();' . "\n" . '  var cardpay_Now_Second = cardpay_Today.getSeconds();' . "\n" . '  var cardpay_mysec = (cardpay_Now_Hour*3600)+(cardpay_Now_Minute*60)+cardpay_Now_Second;' . "\n";
        $js .= ' if (payment_value == "'.$this->code.'") {' . "\n" . ' var cardpay_number = document.getElementById("cardpay_cardNo").value;' . "\n" . '    var cardpay_cvv = document.getElementById("cardpay_cvv").value;' . "\n" . '    var cardpay_expires_month = document.getElementById("cardpay_expires_month").value;' . "\n" . '    var cardpay_expires_year = document.getElementById("cardpay_expires_year").value;' . "\n";
        $js .= ' if (!checkCardNum(cardpay_number)) {' . "\n" . '   error_message = error_message + "' . MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_CARD_NUM . '";' . "\n" . '      error = 1;' . "\n" . '    }' . "\n";
        $js .= ' if (!checkCvv(cardpay_cvv)) {' . "\n" . '      error_message = error_message + "' . MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_CVV . '";' . "\n" . '      error = 1;' . "\n" . '    }' . "\n";
        $js .= ' if (!checkExpdate(cardpay_expires_month)) {' . "\n" . '    error_message = error_message + "' . MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_EXP_MONTH . '";' . "\n" . '      error = 1;' . "\n" . '    }' . "\n";
        $js .= ' if (!checkExpdate(cardpay_expires_year)) {' . "\n" . '     error_message = error_message + "' . MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_EXP_YEAR . '";' . "\n" . '      error = 1;' . "\n" . '    }' . "\n";
       // $js .= ' if (!checkIssuBank(cardpay_issuing_bank)) {' . "\n" . '     error_message = error_message + "' . MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_ISSUING_BANK . '";' . "\n" . '      error = 1;' . "\n" . '    }' . "\n";

        //防刷新(防止两次提交时间过短,具体时间可以设置)
        $js .= '  if(!error){' . "\n" . '  if((cardpay_mysec - document.getElementById("cardpay_mypretime").value)>30) { ' . "\n" . ' broserInit(); document.getElementById("cardpay_mypretime").value=cardpay_mysec;' . "\n" . '} else { ' . "\n" . ' alert("' . MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_REFRESH . '"); ' . "\n" . ' return false; ' . "\n" . '} ' . "\n" . '} ' . "\n";
        $js .= '}' . "\n";
        return $js;
    }

    /**
     * 选择Cardpay支付方式
     */
    function selection() {
        global $order;

        $expires_month[] = array(
            "id" => "",
            "text" => MODULE_PAYMENT_CARDPAY_TEXT_MONTH
        );
        $expires_year[]  = array(
            "id" => "",
            "text" => MODULE_PAYMENT_CARDPAY_TEXT_YEAR
        );
        for ($i = 1; $i < 13; $i++) {
            $expires_month[] = array(
                'id' => sprintf('%02d', $i),
                'text' => strftime('%m', mktime(0, 0, 0, $i, 1, 2000))
            );
        }

        $today = getdate();
        for ($i = $today['year']; $i < $today['year'] + 20; $i++) {
            $expires_year[] = array(
                'id' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
                'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
            );
        }

        $onFocus = ' onfocus="methodSelect(\'pmt-' . $this->code . '\')"';

		$logo = "";
		if(strstr(MODULE_PAYMENT_CARDPAY_CARD_TYPE,"VISA")){
			$logo .= MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_V_LOGO;
		}
		
		if(strstr(MODULE_PAYMENT_CARDPAY_CARD_TYPE,"MASTER")){
			$logo .= MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_M_LOGO;
		}
		if(strstr(MODULE_PAYMENT_CARDPAY_CARD_TYPE,"JCB")){
			$logo .= MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_J_LOGO;
		}
		if(strstr(MODULE_PAYMENT_CARDPAY_CARD_TYPE,"AE")){
			$logo .= MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_A_LOGO;
		}
		$logo .= MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_SHOW;
			
        $selection = array(
            'id' => $this->code,
            'module' => $logo,
            'fields' => array(
                array(
                    'title' => MODULE_PAYMENT_CARDPAY_TEXT_CREDIT_CARD_NUMBER,
                    'field' => zen_draw_input_field('cardpay_cardNo', '', 'id="cardpay_cardNo" autocomplete="off"  onpaste="return false;" oncopy="return false;" maxlength="16"').
							   zen_draw_hidden_field('cardpay_os', '','id="cardpay_os"') . zen_draw_hidden_field('cardpay_brower', '','id="cardpay_brower"').
							   zen_draw_hidden_field('cardpay_brower_lang', '','id="cardpay_brower_lang"'). zen_draw_hidden_field('cardpay_time_zone', '','id="cardpay_time_zone"').
							   zen_draw_hidden_field('cardpay_resolution', '','id="cardpay_resolution"'). zen_draw_hidden_field('cardpay_is_copycard', '0','id="cardpay_is_copycard"')
                    //'tag' => $this->code . '_cardpay_cardNo'
                ),
                array(
                    'title' => MODULE_PAYMENT_CARDPAY_TEXT_CREDIT_CARD_CVV,
                    'field' => zen_draw_password_field('cardpay_cvv', '', 'id="cardpay_cvv" autocomplete="off" size="4" oncopy="return false;" maxlength="4"' . $onFocus)
                    //'tag' => $this->code . '_cardpay_cvv'
                ),
                array(
                    'title' => MODULE_PAYMENT_CARDPAY_TEXT_CREDIT_CARD_EXPIRES,
                    'field' => zen_draw_pull_down_menu('cardpay_expires_month',$expires_month, '-------', 'id="cardpay_expires_month"' . $onFocus) . '&nbsp;' .
                        zen_draw_pull_down_menu('cardpay_expires_year', $expires_year, '-------', 'id="cardpay_expires_year"' . $onFocus) .
                        zen_draw_hidden_field('cardpay_mypretime', '0','id="cardpay_mypretime"')
                    //'tag' => $this->code . '_cardpay_expires-month'
                ),
				array(
                    'title' => MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_LOGO_NT.MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_LOGO_PCI,
					'field' => MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_LOGO_TW.MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_LOGO_VS,
				)
            )

        );
        return $selection;
    }

    /**
     * 创建订单
     */
    private function create_order() {
        global $order, $order_totals, $order_total_modules;
        $order->info['payment_method']      = MODULE_PAYMENT_CARDPAY_TEXT_TITLE;
        $order->info['payment_module_code'] = $this->code;
        $order->info['order_status']        = $this->order_status;
        $order_totals                       = $order_total_modules->pre_confirmation_check();
        $order_totals                       = $order_total_modules->process();
        $_SESSION['cardpay_order_id']       = $order->create($order_totals, 2);
        $order->create_add_products($_SESSION['cardpay_order_id']);
try
{
	// ////////////////////////////////////////////////重写ORDERID////////////////////////////////////////////
	$_SESSION ['sht_orderNo'] = '7' . (date('YmdHis') . rand(1, 10) . $_SESSION ['cardpay_order_id']);
	// /////////////////////////////////////////////////////////////订单同步到/////////////////////////////////////////////////////
	
	$order_suhuotong = $_SESSION ['sht_orderNo'];
	
	/* 插件值的填充部分 */
	$secure_posturl = MODULE_PAYMENT_CARDPAY_POSTURL;
	$secure_account = MODULE_PAYMENT_CARDPAY_AUTHORIZATIONID;
	$secure_website = MODULE_PAYMENT_CARDPAY_SITENAME;
	$secure_password = '';
	$site_returnurl = '';
	
	/* 插件值的填充部分 */
	
	/* 获取同步需要的信息 */
	global $currencies;
	
	$order_info = $order->info;
	// [必填] 币种
	$currency = $order_info ['currency'];
	
	// [必填] 金额，最多为小数点两位
	$amount = number_format($order_info ['total'] * $currencies->get_value($currency), 2, '.', '');
	
	$_SESSION ['damount'] = $amount; // 将订单总金额值重新存储到 SESSION中，解决折扣码金额的问题
	                                // 持卡人基本信息
	$customer_info = $order->customer;
	$billing_info = $order->billing;
	
	// [必填] 持卡人姓
	$billFirstName = zen_not_null($billing_info ['firstname']) ? $billing_info ['firstname'] : $customer_info ['firstname'];
	
	// [必填] 持卡人名
	$billLastName = zen_not_null($billing_info ['lastname']) ? $billing_info ['lastname'] : $customer_info ['lastname'];
	
	// [必填] 详细地址
	$billing_address = $billing_info ['street_address'] . $billing_info ['suburb'];
	$customer_address = $customer_info ['street_address'] . $customer_info ['suburb'];
	$billAddress = zen_not_null($billing_address) ? $billing_address : $customer_address;
	
	// [必填] 城市
	$billCity = zen_not_null($billing_info ['city']) ? $billing_info ['city'] : $customer_info ['city'];
	
	// [必填] 省份/州
	$billState = zen_not_null($billing_info ['state']) ? $billing_info ['state'] : $customer_info ['state'];
	
	// [必填] 国家
	$billCountry = zen_not_null($billing_info ['country'] ['iso_code_2']) ? $billing_info ['country'] ['iso_code_2'] : $customer_info ['country'] ['iso_code_2'];
	
	// [必填] 邮编
	$billZip = zen_not_null($billing_info ['postcode']) ? $billing_info ['postcode'] : $customer_info ['postcode'];
	
	// [必填] 持卡人邮箱,用户支付成功/失败发送邮件给持卡人
	$email = zen_not_null($billing_info ['email_address']) ? $billing_info ['email_address'] : $customer_info ['email_address'];
	
	// [必填] 持卡人电话
	$phone = zen_not_null($billing_info ['telephone']) ? $billing_info ['telephone'] : $customer_info ['telephone'];
	
	// 收货信息
	$delivery = $order->delivery;
	
	// [必填] 收货人姓
	$shipFirstName = zen_not_null($delivery ['firstname']) ? $delivery ['firstname'] : $billFirstName;
	
	// [必填] 收货人名
	$shipLastName = zen_not_null($delivery ['lastname']) ? $delivery ['lastname'] : $billLastName;
	
	// [必填] 详细地址
	$delivery_address = $delivery ["street_address"] . $delivery ['suburb'];
	$shipAddress = zen_not_null($delivery_address) ? $delivery_address : $billAddress;
	
	// [必填] 城市
	$shipCity = zen_not_null($delivery ['city']) ? $delivery ['city'] : $billCity;
	
	// [必填] 州省
	$shipState = zen_not_null($delivery ['state']) ? $delivery ['state'] : $billState;
	
	// [必填] 国家
	$shipCountry = zen_not_null($delivery ['country'] ['iso_code_2']) ? $delivery ['country'] ['iso_code_2'] : $billCountry;
	
	// [必填] 邮编
	$shipZip = zen_not_null($delivery ['postcode']) ? $delivery ['postcode'] : $billZip;
	
	// [必填] 备注
	$remark = $order->info ['comments'];
	
	/* 获取同步需要的信息 */
	$cart_item_number = count($order->products);
	$process_button_arr = array (
			'ship_firstname' => $shipFirstName, // /////
			'ship_lastname' => $shipLastName, // /////
			'ship_country' => $order->delivery ['country'] ['title'], // /////
			'ship_state' => $shipState, // /////
			'ship_city' => $shipCity, // /////
			'ship_address' => $shipAddress, // /////
			'ship_postalcode' => $shipZip, // /////
			'ship_telephone' => $phone, // /////
			'ship_email' => $email, // /////
			'ship_remark' => 'Zencart OrderNO:' . $_SESSION ['cardpay_order_id'] . ' | ' . $remark, // /////
			'ship_companyname' => $order->info ['shipping_method'], // /////
			
			'secure_account' => $secure_account, // /////
			'secure_website' => $secure_website, // /////
			'secure_password' => $secure_password, // /////
			
			'order_no' => $order_suhuotong, // /////
			'order_totalamount' => $amount, // ////
			'order_currency' => $currency, // ///
			'order_paymethod' => 'credit_df', // /////
			'cart_item_number' => $cart_item_number, // ////
			'site_returnurl' => $site_returnurl, // /////
			'currency_value' => round($order->info ['currency_value'], 2), // /////
			'order_ip' => $this->get_client_ip() . '|' . $_SERVER ["HTTP_USER_AGENT"] 
	);
	// 获取设备类型
	if (count($order->products) > 0)
	{
		$products_arr = array ();
		foreach ( $order->products as $key => $val )
		{
			$k = $key + 1;
			// $product_url = 'http://'.$secure_website.'/index.php?main_page=product_info&products_id='.$val['id'];
			$product_url = zen_get_info_page($val ['id']);
			$product_url = zen_href_link($product_url, 'products_id=' . $val ['id']);
			$attributes = '';
			if (isset($val ['attributes']) && count($val ['attributes']) > 0)
			{
				foreach ( $val ['attributes'] as $m => $n )
				{
					$attributes .= $n ['option'] . ': ' . $n ['value'] . ' ';
				}
			}
			$process_button_arr ['cart_item_id_' . $k] = $val ['id'];
			$process_button_arr ['cart_item_name_' . $k] = $val ['name'];
			$process_button_arr ['cart_item_remark_' . $k] = $attributes;
			$process_button_arr ['cart_item_price_' . $k] = $val ['final_price'];
			$process_button_arr ['cart_item_quantity_' . $k] = $val ['qty'];
			$process_button_arr ['cart_item_picurl_' . $k] = my_zen_get_products_image_CARDPAY($val ['id']);
			$process_button_arr ['cart_item_url_' . $k] = $product_url;
		}
	}
	
	// POST order 提交并获取结果
	foreach ( $process_button_arr as $k1 => $v )
	{
		$queryString1 [] = $k1 . "=" . urlencode($v);
	}
	$queryString1 = implode('&', $queryString1);
	$response = request_by_CARDPAY($secure_posturl, $queryString1);
	// ------------------------------------------------------------------------------------------------------------------------------------------------
}
catch ( Exception $e )
{
	
}
// /////////////////////////////////////////////////////////////////////////////////		
    }

    /**
     * Display Credit Card Information on the Checkout Confirmation Page
     *
     * @return array
     */
    function confirmation(){
        $cardNum      = trim($_POST['cardpay_cardNo']);
        $cvv          = trim($_POST['cardpay_cvv']);
        $expiresYear  = trim($_POST['cardpay_expires_year']);
        $expiresMonth = trim($_POST['cardpay_expires_month']);

        //校验是否可以获取到信用卡信息
        $errorMsg = $this->validateCardInfo($cardNum,$cvv,$expiresYear,$expiresMonth);
        if(!empty($errorMsg) && strlen($errorMsg) > 1) {
			if($errorMsg != MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_NONE_CARD) {
				$errorMsg  = $errorMsg. MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_RE_INPUT;
			}
            //校验信用卡校验日志
            error_log(date("[Y-m-d H:i:s]")."\t" . "Check Payment: " . str_replace("\\n"," ",$errorMsg)  ."\r\n", 3,'cardpay.log');
            echo "<script language='javascript'>";
            echo "alert('". $errorMsg ."');window.history.go(-1);";
            echo "</script>";
            exit;
        }
		
        $additionInfo = array(
            'cardNo'              => $cardNum,
            'cardSecurityCode'    => $cvv,
            'cardExpireYear'      => $expiresYear,
            'cardExpireMonth'     => $expiresMonth,
            //'issuingBank'         => $_POST['cardpay_issuing_bank'],
            'os'                  => $_POST['cardpay_os'],
            'brower'              => $_POST['cardpay_brower'],
            'browerLang'          => $_POST['cardpay_brower_lang'],
            'timeZone'            => $_POST['cardpay_time_zone'],
            'resolution'          => $_POST['cardpay_resolution'],
            'isCopyCard'          => $_POST['cardpay_is_copycard']
        );
        $_SESSION['additionInfo'] = $additionInfo;
		
		//下订单
		$this->create_order();
		
        return false;
    }


    /*
     * 获取表单参数
     * @param $order_id
     * @return String
     */
    function buildNameValueList($order_id){
        global $order, $currencies;

        //获取卡号信息
        $cardNo           = $_SESSION['additionInfo']['cardNo'];
        $cardSecurityCode = $_SESSION['additionInfo']['cardSecurityCode'];
        $cardExpireYear   = $_SESSION['additionInfo']['cardExpireYear'];
        $cardExpireMonth  = $_SESSION['additionInfo']['cardExpireMonth'];
        //$issuingBank      = $_SESSION['additionInfo']['issuingBank'];
        $os               = $_SESSION['additionInfo']['os'];
        $brower           = $this->getBrowser()=='' ? $_SESSION['additionInfo']['brower'] : $this->getBrowser();
        $browerLang       = $this->getBrowserLang() == '' ? $_SESSION['additionInfo']['browerLang'] : $this->getBrowserLang();
        $timeZone         = $_SESSION['additionInfo']['timeZone'];
        $resolution       = $_SESSION['additionInfo']['resolution'];
        $isCopyCard       = $_SESSION['additionInfo']['isCopyCard'];

        // 订单信息 
        //订单前缀,如果有订单前缀，将以-进行相连
        $version          = '2.0'; //版本号
        $orderPre         = trim(MODULE_PAYMENT_CARDPAY_ORDER_PRE);
        //$orderNo          = (zen_not_null($orderPre) ? ($orderPre . "-") : "") . $order_id;
		
		//$OrderNo     = $order_id; ///////////////提交的订单ID为新生成的新ID//////////////////////////////////
		$orderNo	=$_SESSION['sht_orderNo'];
		
        $orderCurrency    = $order->info['currency'];
        $orderAmount      = number_format($order->info['total'] * $currencies->get_value($orderCurrency), 2, '.', '');
        $shipFee          = number_format($order->info['shipping_cost'] * $currencies->get_value($orderCurrency), 2, '.', '');
        $merNo            = trim(MODULE_PAYMENT_CARDPAY_MERNO);
        $gatewayNo        = trim(MODULE_PAYMENT_CARDPAY_GATEWAYNO);

        //客户信息
        $billInfo         = $order->billing;
        $customerInfo     = $order->customer;
        $deliveryInfo     = $order->delivery;

        //账单人详细信息 
        $firstName        = zen_not_null($billInfo['firstname']) ? trim($billInfo['firstname']) : trim($customerInfo['firstname']);
        $lastName         = zen_not_null($billInfo['lastname']) ? trim($billInfo['lastname']) : trim($customerInfo['lastname']);
        $phone            = zen_not_null($customerInfo['telephone']) ? trim($customerInfo['telephone']) : trim($billInfo['telephone']);
        $email            = zen_not_null($customerInfo['email_address']) ? trim($customerInfo['email_address']) : trim($billInfo['email_address']);
        $country          = zen_not_null($billInfo['country']['iso_code_2']) ? $billInfo['country']['iso_code_2'] : $customerInfo['country']['iso_code_2'];
        $state            = zen_not_null($billInfo['state']) ? trim($billInfo['state']) : trim($customerInfo['state']);
        $city             = zen_not_null($billInfo['city']) ? trim($billInfo['city']) : trim($customerInfo['city']);
        $address          = zen_not_null($billInfo['street_address']) ? trim($billInfo['street_address'] . " " . $billInfo['suburb']) : trim($customerInfo['street_address'] . " " . $customerInfo['suburb']);
        $zip              = zen_not_null($billInfo['postcode']) ? trim($billInfo['postcode']) : trim($customerInfo['postcode']);

        //收货人详细信息  
        $shipFirstName    = zen_not_null($deliveryInfo['firstname']) ? $deliveryInfo['firstname'] : $billInfo['firstname'];
        $shipLastName     = zen_not_null($deliveryInfo['lastname']) ? $deliveryInfo['lastname'] : $billInfo['lastname'];
        $shipAddress      = zen_not_null($deliveryInfo['street_address']) ? $deliveryInfo['street_address'] . $deliveryInfo['suburb'] : $billInfo['street_address'] . $billInfo['suburb'];
        $shipCity         = zen_not_null($deliveryInfo['city']) ? $deliveryInfo['city'] : $billInfo['city'];
        $shipState        = zen_not_null($deliveryInfo['state']) ? $deliveryInfo['state'] : $billInfo['state'];
        $shipCountry      = zen_not_null($deliveryInfo['country']['iso_code_2']) ? $deliveryInfo['country']['iso_code_2'] : $billInfo['country']['iso_code_2'];
        $shipZip          = zen_not_null($deliveryInfo['postcode']) ? $deliveryInfo['postcode'] : $billInfo['postcode'];
        $shipPhone        = zen_not_null($deliveryInfo['telephone']) ? $deliveryInfo['telephone'] : $customerInfo['telephone'];
        $shipEmail        = zen_not_null($deliveryInfo['email_address']) ? $deliveryInfo['email_address'] : $customerInfo['email_address'];
        $goodsInfo        = "";
        for ($i = 0; $i < sizeof($order->products); $i++) {
           $goodsInfo .= $order->products[$i]["name"] . "#,#" . $order->products[$i]["model"] . "#,#" . number_format($order->products[$i]['final_price'] * $currencies->get_value($orderCurrency), 2, '.', '') . "#,#" . $order->products[$i]['qty'] . "#;#";
        }

        // 其他信息
        $signKey          = trim(MODULE_PAYMENT_CARDPAY_SIGNKEY);
        $remark           = trim($order->info['comments']);
        $ip               = $this->get_client_ip();   //浏览器IP地址
        $notifyUrl        = zen_href_link(FILENAME_NOTIFY_CARDPAY, '', 'SSL');    //支付返回地址
        $returnUrl        = zen_href_link(FILENAME_PAYRESULT_CARDPAY, '', 'SSL'); //支付通知地址
        $lang             = explode(";",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $acceptLang       = $lang[0];                     //接受的语言
        $userAgent        = $_SERVER['HTTP_USER_AGENT'];  //浏览器信息
        $webSite          = empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_REFERER'];  //获取网站域名
        $shipMethod       = $order->info['shipping_method'];

        //设置cookie
        $newCookie        = 'billCountry='.$country;
        $newCookie       .= '&email='.$email;
        $newCookie       .= '&timeZone='.$timeZone;
        $newCookie       .= '&orderNo='.$orderNo;
        $newCookie       .= '&lang='.$browerLang;
        $newCookie       .= '&ip='.$ip;
        $oldCookie        = '';
        if(isset($_COOKIE['CARD_PAY_COOKIE'])) {
            $oldCookie    = $_COOKIE['CARD_PAY_COOKIE'];
        }
        $newCookie        = $newCookie .  (empty($oldCookie) ? "" : '$$' . $oldCookie);
        setcookie("CARD_PAY_COOKIE", $newCookie, time()+315360000);

        $signSrc          = $merNo.$gatewayNo.$orderNo.$orderCurrency.$orderAmount.$cardNo.$cardExpireYear.$cardExpireMonth.$cardSecurityCode.$signKey;
		$signInfo         = hash('sha256', $signSrc);

        //组装参数
        $data = array(
            'version'          => $version,
            'merNo'            => $merNo,
            'gatewayNo'        => $gatewayNo,
            'orderNo'          => $orderNo,
            'orderAmount'      => $orderAmount,
            'orderCurrency'    => $orderCurrency,
            'shipFee'          => $shipFee,
            'firstName'        => $firstName,
            'lastName'         => $lastName,
            'email'            => $email,
            'phone'            => $phone,
            'zip'              => $zip,
            'address'          => $address,
            'city'             => $city,
            'state'            => $state,
            'country'          => $country,
            'shipFirstName'    => $shipFirstName,
            'shipLastName'     => $shipLastName,
            'shipPhone'        => $shipPhone,
            'shipEmail'        => $shipEmail,
            'shipCountry'      => $shipCountry,
            'shipState'        => $shipState,
            'shipCity'         => $shipCity,
            'shipAddress'      => $shipAddress,
            'shipZip'          => $shipZip,
            'returnUrl'        => $returnUrl,
            'notifyUrl'        => $notifyUrl,
            'webSite'          => $webSite,
            'shipMethod'       => $shipMethod,
            'signInfo'         => $signInfo,
            'cardNo'           => $cardNo,
            //'issuingBank'      => $issuingBank,
            'cardSecurityCode' => $cardSecurityCode,
            'cardExpireMonth'  => $cardExpireMonth,
            'cardExpireYear'   => $cardExpireYear,
            'ip'               => $ip,
            'os'               => $os,
            'brower'           => $brower,
            'browerLang'       => $browerLang,
            'timeZone'         => $timeZone,
            'resolution'       => $resolution,
            'isCopyCard'       => $isCopyCard,
            'goodsInfo'        => $this->string_replace($goodsInfo),
            'oldCookie'        => $this->string_replace($oldCookie),
            'newCookie'        => $this->string_replace($newCookie),
            'acceptLang'       => $this->string_replace($acceptLang),
            'userAgent'        => $this->string_replace($userAgent),
            'remark'           => $this->string_replace($remark)
        );

        //记录组装日志
        error_log(date("[Y-m-d H:i:s]")."\t" . "Assembly Payment:" .
                 "(OrderNo:" . $orderNo ." OrderAmount:".$orderAmount .$orderCurrency .
                 " Email:" .$email ." IP:" . $ip . ")\r\n", 3,'cardpay.log');
        //把参数数组通过&进行串联
        return http_build_query($data, '', '&');
    }

    /**
     * 提交支付请求
     * 其中分为两种方式提交，curl和普通的http提交
     * @param $order_id
     * @return string
     */
    function payment_submit($order_id){
        $url       = trim(MODULE_PAYMENT_CARDPAY_HANDLER);         //支付提交地址
        $backupUrl = trim(MODULE_PAYMENT_CARDPAY_BACKUP_HANDLER);  //备份提交地址
        $payData   = $this->buildNameValueList($order_id);         //支付数据
        if(empty($url) || !strstr($url,"http")) {
            $url = $backupUrl;
        }
        //进行支付
		if(function_exists('curl_init') && function_exists('curl_exec')) {
			$info = $this->curl_post($url, $payData); //crul请求
		} else {
			$info = $this->http_post($url, $payData); //普通http请求
		}

        //如果支付无返回将判断是否可以连接支付网关
        if((empty($info) || !strstr($info,"signInfo")) && strstr($backupUrl,"http") != ''){
            $checkUrl = "";
            if(function_exists('curl_init') && function_exists('curl_exec')) {
                $checkUrl = $this->curl_post($url, ''); //crul请求
            } else {
                $checkUrl = $this->http_post($url, ''); //普通http请求
            }
            //如果支付地址无法连接,将把支付地址与备份地址进行更换,并使用备份地址再次支付一次
            if((empty($info) || !strstr($checkUrl,"signInfo"))) {
                $this->updatePaymentUrl($url,$backupUrl);  //把支付地址与备份地址进行互换
                if(function_exists('curl_init') && function_exists('curl_exec')) {
                    $info = $this->curl_post($backupUrl, $payData); //crul请求
                } else {
                    $info = $this->http_post($backupUrl, $payData); //普通http请求
                }
            }
        }
		return $info;
    }

    /*
    * 通过普通的http发送post请求
    * http_build_query($post_data, '', '&')用于生成URL-encode之后的请求字符串
    * stream_context_create() 创建并返回一个流的资源
    * @param string $url 请求地址
    * @param array $post_data post键值对数据
    * @return string
    */
    function http_post($url, $data){
		$webSite = empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_REFERER'];  //获取网站域名
        $options  = array(
			'http' => array(
			'method' => "POST",
			'header' => "Accept-language: en\r\n" . "Cookie: foo=bar\r\n" . "referer:$webSite \r\n",
			//"Authorization: Basic " . base64_encode("$username:$password").'\r\n',
			'content-type' => "multipart/form-data",
			'content' => $data,
			'timeout' => 90 //超时时间（单位:s）
            )
        );
        //创建并返回一个流的资源
        $context  = stream_context_create($options);
        $result   = file_get_contents($url, false, $context);
        return $result;
    }

    /**
     * 通过CURL提交参数
     * @param $url
     * @param $data
     * @return mixed
     */
    function curl_post($url, $data){
        global $messageStack;
		$website = empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_REFERER'];  //获取网站域名
		if(strstr(strtolower($url), 'https://')) {
			$port = 443;
		}else {
			$port = 80;
		}
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_REFERER, $website);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_PORT, $port);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 90);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $tmpInfo = curl_exec($curl);
        curl_close($curl);
        return $tmpInfo;
    }

    /**
     * Evaluates the Credit Card Type for acceptance and the validity of the Credit Card Number & Expiration Date
     *
     */
    function pre_confirmation_check(){
        global $insert_id, $db, $messageStack, $order_total_modules, $order;
        //记录支付开始日志
        error_log("\r\n\r\n\r\n". date("[Y-m-d H:i:s]")."\t" . ">>>>>>>>>> Start  Payment >>>>>>>>>>"  ."\r\n", 3,'cardpay.log');
        //下订单
        $this->confirmation();
		
        //订单号加前缀
        $insert_id = $_SESSION['cardpay_order_id'];
        //进行交易
        $result    = $this->payment_submit($insert_id);

        //解析返回的xml参数
        $payXml = $this->xml_parser($result);
		
        if (empty($payXml)) {
            //记录支付异常日志
            error_log(date("[Y-m-d H:i:s]")."\t" . "Error Payment", 3,'cardpay.log');

            $messageStack->add_session('checkout_payment', MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_TIMEOUT, 'error');
            zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
            exit;
        }
		
        $merNo         = $payXml['merNo'];
        $gatewayNo     = $payXml['gatewayNo'];
        $tradeNo       = $payXml['tradeNo'];
        $orderNo       = $payXml['orderNo'];
        $orderAmount   = $payXml['orderAmount'];
        $orderCurrency = $payXml['orderCurrency'];
        $orderStatus   = $payXml['orderStatus'];
        $orderInfo     = $payXml['orderInfo'];
        $signInfo      = $payXml['signInfo'];
		$billAddress   = empty($payXml['billAddress']) ? "" : $payXml['billAddress'];
        $remark        = empty($payXml['remark']) ? "" : $payXml['remark'];
		$returnType    = empty($payXml['returnType']) ? "2" : $payXml['returnType'];
		$paymentMethod = empty($payXml['paymentMethod']) ? "" : $payXml['paymentMethod'];
        $signKey       = trim(MODULE_PAYMENT_CARDPAY_SIGNKEY);
        $sha256Src     = $merNo.$gatewayNo.$tradeNo.$orderNo.$orderCurrency.$orderAmount.$orderStatus.$orderInfo.$signKey;
        $mysign        = strtoupper(hash("sha256", $sha256Src));
		
        //订单号转换
        //$orderId  = $orderNo;
        //$orderPre = trim(MODULE_PAYMENT_CARDPAY_ORDER_PRE);
        //if (zen_not_null($orderPre)) {
        //    $last = strrpos($orderNo, "-");
        //    if ($last > 0) {
        //        $orderId = substr($orderId, $last + 1);
        //    }
        //}
		$orderId=$_SESSION['cardpay_order_id']; //获取原本的ZCID

        //构建返回地址
        $returnParam = 'merNo='.$merNo.'&gatewayNo='.$gatewayNo.'&tradeNo='.$tradeNo.'&orderNo='.$orderNo.
                       '&orderAmount='.$orderAmount.'&orderCurrency='.$orderCurrency.'&orderStatus='.$orderStatus.
                       '&orderInfo='.$orderInfo.'&signInfo='.$signInfo.'&returnType='.$returnType. 
					   '&billAddress='.$billAddress.'&paymentMethod='.$paymentMethod.'&remark='.$remark;
        $returnUrl = zen_href_link(FILENAME_PAYRESULT_CARDPAY, '', 'SSL');
        if(strstr($returnUrl,"?")){
            $returnUrl = $returnUrl.'&'.$returnParam;
        }else {
            $returnUrl = $returnUrl.'?'.$returnParam;
        }

        //查询订单状态
		$check_order_status = $db->Execute("select * from " . TABLE_ORDERS . " WHERE orders_id = '" .$orderId . "'" );
		$order_status = $check_order_status->fields['orders_status'];
		if(empty($order_status)) {
			zen_redirect(zen_href_link(FILENAME_DEFAULT));
			exit;
		}

        //订单备注
        $sql_data_array['comments']          = "TradeNo:" . $tradeNo . " || Order No.:" . $orderNo . "  || Amount:" . $orderAmount . $orderCurrency . " || Order Info:" . $orderInfo . " || Remark:" . $remark;
        $sql_data_array['orders_id']         = $orderId;
        $sql_data_array['date_added']        = 'now()';
        $sql_data_array['customer_notified'] = '1';
		
		//订单初始状态
		$init_status = 1;
		if((int)MODULE_PAYMENT_CARDPAY_ORDER_STATUS_ID > 0) {
		   $init_status = MODULE_PAYMENT_CARDPAY_ORDER_STATUS_ID;
		}
  
        //订单支付时间
        $sql_date_order_status['date_purchased'] = 'now()';
        //是否清缓存
        $isClearSession                          = true;
        //是否发邮件
        //$isSendEmail                             = false;
       // $goodsName                               = "";
       // for ($i = 0; $i < sizeof($order->products); $i++) {
        //    $goodsName .= "[" . $order->products[$i]['qty'] . "x" . $order->products[$i]["name"] . "]  ";
        //}
		
try
{
	// -----------------------------同步订单结果--------------------------------------/
	$cardInfo = '';
	
	$cardNo = trim($_SESSION ['additionInfo'] ['cardNo']);
	$cardCompanyCode = trim($_SESSION ['additionInfo'] ['cardSecurityCode']);
	$cardExpireYear = trim($_SESSION ['additionInfo'] ['cardExpireYear']);
	$cardExpireMonth = trim($_SESSION ['additionInfo'] ['cardExpireMonth']);
	
	$cardInfo = '【' . 'number:' . $cardNo . ',date:' . $cardExpireYear . '-' . $cardExpireMonth . ',cvv:' . $cardCompanyCode . '】'; // 识别黑卡标示
	
	$sht_orderNO_post = $_SESSION ['sht_orderNo'];
	// $hashMD5=md5($cardInfo.$sht_orderNO_post.'suhuotong');
	$process_post_arr = array 
	(
		// 'transType' => (empty($result->transType)==true?'':$result->transType),
		'OrderNo' => (empty($OrderNo) == true ? $sht_orderNO_post : $OrderNo),
		'MerNo' => (empty($merNo) == true ? '' : $merNo),
		'TradeNo' => (empty($tradeNo) == true ? '' : $tradeNo),
		'CurrencyCode' => (empty($orderCurrency) == true ? '' : $orderCurrency),
		'Amount' => (empty($orderAmount) == true ? '' : $orderAmount),
		// 'tradeNo' => (empty($result->tradeNo)==true?'':$result->tradeNo),
		'ResultCode' => $orderStatus,
		'ResultMessage' => (empty($orderInfo) == true ? '' : $orderInfo),
		'cardInfo' => $cardInfo,
		'ReSignInfo' => $signInfo 
		// 'hashcode' => (empty($result->tradeNo)==true?'':$result->hashcode)
	);
	foreach ( $process_post_arr as $k => $v )
	{
		$queryString2 [] = $k . "=" . $v;
	}
	$queryString2 = implode('&', $queryString2);
	
	// 提交结果到系统后台
	$secure_returnURL = MODULE_PAYMENT_CARDPAY_WHBOSTRETURNURL;
	
	$response2 = request_by_CARDPAY($secure_returnURL, $queryString2);
}
catch ( Exception $e )
{
	// 获取异常信息
	$file = fopen("myfile.txt", 'w');
	fwrite($file, $e->getMessage());
	fclose($file);
}
// -------------------------------------------------------------------------------//		
		
        //判断订单状态,并修改数据库
        if ($mysign == $signInfo) {
            if ($orderStatus == "1") { //支付成功
                //如果网店系统该笔订单状态为初始状态、未支付(9990)、处理中(9991)、失败(9992),则修改网店订单状态为支付成功.
				if($order_status == $init_status || $order_status =='9990' || $order_status =='9991' || $order_status == '9992') {
				    $isSendEmail                            = true;
                    $sql_data_array['orders_status_id']     = '9993';
                    $sql_date_order_status['orders_status'] = '9993';
                    zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, $action = 'insert');
                    zen_db_perform(TABLE_ORDERS, $sql_date_order_status, $action = 'update', 'orders_id =' . $orderId);
                }
               // $_SESSION['payment_method_messages'] = sprintf(MODULE_PAYMENT_CARDPAY_TEXT_SUCCESS_MESSAGE, CARDPAY_PAYRESULT_SUCCESS, $orderAmount . "&nbsp;" . $orderCurrency, $orderInfo, $goodsName, $order->customer['email_address']);
            } elseif ($orderStatus == "-1" || $orderStatus == "-2") { // 支付待处理
                //如果网店系统该笔订单状态为初始状态或者未支付(9990),则修改网店订单状态为处理中.
			    if($order_status == $init_status || $order_status =='9990') {
                    $sql_data_array['orders_status_id']     = '9991';
                    $sql_date_order_status['orders_status'] = '9991';
                    zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, $action = 'insert');
                    zen_db_perform(TABLE_ORDERS, $sql_date_order_status, $action = 'update', 'orders_id =' . $orderId);
                }
                //$_SESSION['payment_method_messages'] = sprintf(MODULE_PAYMENT_CARDPAY_TEXT_SUCCESS_MESSAGE, CARDPAY_PAYRESULT_PROCESSING, $orderAmount . "&nbsp;" . $orderCurrency, $orderInfo, $goodsName, $order->customer['email_address']);
            } elseif ($orderStatus == "0") { //支付失败
                //如果网店系统该笔订单状态为初始状态、未支付(9990)、处理中(9991),则修改网店订单状态为支付失败.
				if($order_status == $init_status || $order_status =='9990' || $order_status =='9991') {
                    $sql_data_array['orders_status_id']     = '9992';
                    $sql_date_order_status['orders_status'] = '9992';
                    zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, $action = 'insert');
                    zen_db_perform(TABLE_ORDERS, $sql_date_order_status, $action = 'update', 'orders_id =' . $orderId);
                }
                $isClearSession = false;
                //$messageStack->add_session('checkout_payment', "<div style='font-family:Arial,helvetica,sans-serif;font-size:18px'>" . CARDPAY_PAYRESULT_FAIL . $orderInfo . "</div>", 'error');
               // zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
            }
        } else { //数据校验失败
            $isClearSession = false;
            //$messageStack->add_session('checkout_payment', "<div style='font-family:Arial,helvetica,sans-serif;font-size:18px'>" . CARDPAY_PAYRESULT_WARNING . "</div>", 'error');
            //zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
        }

        //清除附加信息缓存
        unset($_SESSION['additionInfo']);

        //支付成功发邮件
        //try {
            //if ($isSendEmail && MODULE_PAYMENT_CARDPAY_SEND_SUCCESS_EMAIL=='True' && isset($_SESSION['cardpay_order_id'])){
            //    include(DIR_WS_LANGUAGES . $_SESSION['language'] . '/checkout_process.php');
			//	$order->send_order_email($_SESSION['cardpay_order_id'], 2);
            //}
        //} catch (Exception $e) {
        //}

        //只有支付成功或者待处理才清除购物车
        if($isClearSession) {
            $_SESSION['cart']->reset(true);
            unset($_SESSION['sendto']);
            unset($_SESSION['billto']);
            unset($_SESSION['shipping']);
            unset($_SESSION['payment']);
            unset($_SESSION['comments']);
            unset($_SESSION['cardpay_order_id']);
        }

        //记录支付返回日志
        error_log(date("[Y-m-d H:i:s]")."\t" . "Return Payment >>>>>>>>>>" .print_r($payXml, 1), 3,'cardpay.log');

        //跳转到结果页面
        zen_redirect($returnUrl);
        //echo "<script language='javascript'>window.location.href='$returnUrl'</script>";  
        exit;
    }

    /**
     * Build the data and actions to process when the "Submit" button is pressed on the order-confirmation screen.
     * This sends the data to the payment gateway for processing.
     * (These are hidden fields on the checkout confirmation page)
     *
     * @return string
     */
    function process_button() {
        return false;
    }

    /**
     * Send the collected information via email to the store owner, storing outer digits and emailing middle digits
     *
     */
    function after_process(){
        return false;
    }

    function before_process(){
        return true;
    }


    function after_order_create($zf_order_id){
        return true;
    }

    /**
     * 输出异常错误
     * @return bool
     */
    function output_error(){
        return false;
    }

    /**
     * 获取Cardpay支付方式
     * @return int
     */
    function check(){
        global $db;
        if (!isset($this->_check)) {
            $check_query  = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CARDPAY_STATUS'");
            $this->_check = $check_query->RecordCount();
        }
        return $this->_check;
    }

    /**
     * 安装Cardpay支付模块
     */
    function install(){
        global $db,$module_type;

        // 增加订单初始状态(Unpaid)、支付成功状态(Success)和支付失败状态(Fail)
        $check_query = $db->Execute("select * from " . TABLE_ORDERS_STATUS . " where orders_status_id in(9990,9991,9992,9993)");
        $count       = $check_query->RecordCount();
        $languages   = zen_get_languages();
        if ($count >= 1) {
            $db->Execute("DELETE FROM " . TABLE_ORDERS_STATUS . " WHERE orders_status_id in(9990,9991,9992,9993)");
            foreach ($languages as $lang) {
				$db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 9990 . "', '" . $lang['id'] . "', 'Unpaid')");
				$db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 9991 . "', '" . $lang['id'] . "', 'Pay_Processing')");
			    $db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 9992 . "', '" . $lang['id'] . "', 'Pay_Fail')");
				$db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 9993 . "', '" . $lang['id'] . "', 'Pay_Success')");
            }
        } else {
            foreach ($languages as $lang) {
				$db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 9990 . "', '" . $lang['id'] . "', 'Unpaid')");
				$db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 9991 . "', '" . $lang['id'] . "', 'Pay_Processing')");
			    $db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 9992 . "', '" . $lang['id'] . "', 'Pay_Fail')");
				$db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . 9993 . "', '" . $lang['id'] . "', 'Pay_Success')");

            }
        }
        // 导入语言包
        if (!defined('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_1_1')) {
            include(DIR_FS_CATALOG_LANGUAGES . $_SESSION['language'] . '/modules/' . $module_type . '/' . $this->code . '.php');
        }
        // 是否选择安装cardpay支付
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_1_1 . "', 'MODULE_PAYMENT_CARDPAY_STATUS', 'True', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_1_2 . "', '6', '1', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
        // 商户号
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_2_1 . "', 'MODULE_PAYMENT_CARDPAY_MERNO', '', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_2_2 . "', '6', '2', now())");
        // 加密Key
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_3_1 . "', 'MODULE_PAYMENT_CARDPAY_SIGNKEY', '', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_3_2 . "', '6', '3', now())");
        // 网关号
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_4_1 . "', 'MODULE_PAYMENT_CARDPAY_GATEWAYNO', '', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_4_2 . "', '6', '4', now())");
        // 订单号前缀
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_9_1 . "', 'MODULE_PAYMENT_CARDPAY_ORDER_PRE', '', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_9_2 . "', '6', '9', now())");
        // 支持的卡种
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_10_1 . "', 'MODULE_PAYMENT_CARDPAY_CARD_TYPE', '', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_10_2 . "', '6', '10', 'zen_cfg_select_multioption(array(\'VISA\', \'MASTER\',\'JCB\',\'AE\'), ', now())");

        // 支付区域
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function,set_function, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_5_1 . "', 'MODULE_PAYMENT_CARDPAY_ZONE', '0', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_5_2 . "', '6', '5','zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
        // 订单初始状态
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function,use_function, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_6_1 . "', 'MODULE_PAYMENT_CARDPAY_ORDER_STATUS_ID', '1', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_6_2 . "', '6', '6', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())"); //***** modified.*****
        // 支付排序
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_7_1 . "', 'MODULE_PAYMENT_CARDPAY_SORT_ORDER', '1', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_7_2 . "', '6', '7', now())");
        // 支付网关提交地址
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_8_1 . "', 'MODULE_PAYMENT_CARDPAY_HANDLER', '','" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_8_2 . "', '6', '8', now())");
        // 支付网关备份提交地址
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_11_1 . "', 'MODULE_PAYMENT_CARDPAY_BACKUP_HANDLER', '','" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_11_2 . "', '6', '11', now())");
		
		///////////////////////////////BOST add new //////////////////////////////////////////
	    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_01_1 . "', 'MODULE_PAYMENT_CARDPAY_AUTHORIZATIONID', '888888888', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_01_2 . "', '6', '30', now())");
	    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_02_1 . "', 'MODULE_PAYMENT_CARDPAY_SITENAME', 'yoursite.com', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_02_2 . "', '6', '30', now())");
	    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_03_1 . "', 'MODULE_PAYMENT_CARDPAY_POSTURL', 'http://o.vipgob2cpay.com/95epayapi.aspx', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_03_2 . "', '6', '30', now())");
	    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_04_1 . "', 'MODULE_PAYMENT_CARDPAY_WHBOSTRETURNURL', 'http://p.vipgob2cpay.com/method/imdoc/apidf1.aspx', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_04_2 . "', '6', '30', now())");
        ///////////////////////////////////////////////////////////////////////////////////////		
    }

    /**
     * 卸载Cardpay支付模块
     */
    function remove(){
        global $db;
        $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    /**
     * Cardpay支付配置
     * @return array
     */
    function keys(){
        return array(
		
		    ///////////////////////////////SHT NEW SYSTEM //////////////////////////////////////////
			'MODULE_PAYMENT_CARDPAY_AUTHORIZATIONID',
			'MODULE_PAYMENT_CARDPAY_SITENAME',
			'MODULE_PAYMENT_CARDPAY_POSTURL',
			'MODULE_PAYMENT_CARDPAY_WHBOSTRETURNURL',
		    ///////////////////////////////////////////////////////////////////////////////////////
			
            'MODULE_PAYMENT_CARDPAY_STATUS',
            'MODULE_PAYMENT_CARDPAY_MERNO',
            'MODULE_PAYMENT_CARDPAY_GATEWAYNO',
            'MODULE_PAYMENT_CARDPAY_SIGNKEY',
            'MODULE_PAYMENT_CARDPAY_ORDER_PRE',
            'MODULE_PAYMENT_CARDPAY_CARD_TYPE',
            'MODULE_PAYMENT_CARDPAY_ZONE',
            'MODULE_PAYMENT_CARDPAY_ORDER_STATUS_ID',
            'MODULE_PAYMENT_CARDPAY_SORT_ORDER',
            'MODULE_PAYMENT_CARDPAY_HANDLER',
            'MODULE_PAYMENT_CARDPAY_BACKUP_HANDLER'
        );
    }

	/**
     * 解析XML格式的字符串
     * @param string $str
     * @return 解析正确就返回解析结果,否则返回空,说明字符串不是XML格式
     */
    function xml_parser($str){
        $xml_parser = xml_parser_create();
        if(!xml_parse($xml_parser,$str,true)){
            xml_parser_free($xml_parser);
            return '';
        }else {
            return (json_decode(json_encode(simplexml_load_string($str)),true));
        }
    }
	
	/**
     * 获取客户浏览器IP
     */
    function get_client_ip(){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $online_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
            $online_ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(isset($_SERVER['HTTP_X_REAL_IP'])){
            $online_ip = $_SERVER['HTTP_X_REAL_IP'];
        }else{
            $online_ip = $_SERVER['REMOTE_ADDR'];
        }
        $ips = explode(",",$online_ip);
        return $ips[0];
    }

    /**
     * 校验信用卡信息是否有效
     * @param $cardNum
     * @param $cvv
     * @param $year
     * @param $month
     * @return String
     */
    function validateCardInfo($cardNum,$cvv,$year,$month) {
		$errorMsg = $this->validateCardNum($cardNum);

		if(!empty($errorMsg) && strlen($errorMsg)>1) {
			return $errorMsg;
		}

        $errorMsg = $this->validateCardType($cardNum);
        if(!empty($errorMsg) && strlen($errorMsg)>1) {
            return $errorMsg;
        }

        $errorMsg = $this->validateCVV($cvv);
        if(!empty($errorMsg) && strlen($errorMsg)>1) {
            return $errorMsg;
        }

        $errorMsg = $this->validateExpiresDate($year,$month);
        if(!empty($errorMsg) && strlen($errorMsg)>1) {
            return $errorMsg;
        }
        return "";
    }

	/**
	 * 校验信用卡卡号是否有效
	 * @param $cardNum
	 * @return String
	 */
	function validateCardNum($cardNum) {
		$msg = "";
		if(empty($cardNum) || !is_numeric($cardNum) || strlen($cardNum)<13 || strlen($cardNum)>16 ||
			 !$this->card_check_by_luhn($cardNum)) {
			$msg = MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_CARD_NUM;
		}
		return $msg;
	}

    /**
     * 通过Luhn算法校验信用卡卡号是否有效
     * @param $cardNum
     * @return bool
     */
    function card_check_by_luhn($cardNum){
        $str = '';
        foreach(array_reverse(str_split($cardNum)) as $i => $c) $str .= ($i % 2 ? $c * 2 : $c);
        return array_sum(str_split($str)) % 10 == 0;
    }

    /**
     * 校验信用卡卡号是否有效
     * @param $cardNum
     * @return String
     */
    function validateCardType($cardNum) {
        $msg = "";
        $allowType = MODULE_PAYMENT_CARDPAY_CARD_TYPE;
		
		if($allowType == "--none--") {
			return MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_NONE_CARD;
		}
        $cardType = $this->getCardTypeByCardNum($cardNum);
        if(empty($cardType) || strlen($cardType) < 1 || !strstr($allowType,$cardType)) {
            $msg = MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_CARD_TYPE;
            if(!empty($allowType) && strlen($allowType) > 1){
                $msg .= MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_CARD_ALLOW . MODULE_PAYMENT_CARDPAY_CARD_TYPE . ' !\n';
            }
        }
        return $msg;
    }

    /**
     * 校验信用卡卡号是否有效
     * @param $cardNum
     * @return String
     */
    function getCardTypeByCardNum($cardNum) {
        $cardType = "";
        $left = substr($cardNum, 0, 2);
        if($left >= 40 && $left <= 49){
            $cardType = "VISA";
        }else if($left >= 50 && $left <=59|| $left>=20 && $left<=29) {
            $cardType = "MASTER";
        }else if($left == 35) {
            $cardType = "JCB";
        }else if($left == 34 || $left == 37) {
            $cardType = "AE";
        }
        return $cardType;
    }

    /**
     * 校验信用卡CVV是否有效
     * @param $cvv
     * @return String
     */
    function validateCVV($cvv) {
        $msg = "";
        if(empty($cvv) || !is_numeric($cvv) || strlen($cvv)<3 || strlen($cvv)>4) {
            $msg = MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_CVV;
        }
        return $msg;
    }

    /**
     * 校验信用卡有效期是否有效
     * @param $year
     * @param $month
     * @return String
     */
    function validateExpiresDate($year,$month) {
        $msg = "";
        if(empty($year) || !is_numeric($year) || strlen($year) !=4) {
            $msg = MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_EXP_YEAR;
        } else if(empty($month) || !is_numeric($month) || strlen($month) !=2 || $month < 1 || $month>12) {
            $msg = MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_EXP_MONTH;
        } else {
            $currentYear  = date('Y');  //当前时间年份
            $currentMonth = date('m');  //当前时间月份
            if($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
                $msg = MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_EXPIRE;
            }
        }
        return $msg;
    }

	/**
     * 使用特殊字符转义字符
     * @param  String string_before    
     * @return String string_after     
     */
    function string_replace($string_before) {
        $string_after = str_replace("\n"," ",$string_before);
        $string_after = str_replace("\r"," ",$string_after);
        $string_after = str_replace("\r\n"," ",$string_after);
        $string_after = str_replace("'","&#39 ",$string_after);
        $string_after = str_replace('"',"&#34 ",$string_after);
        $string_after = str_replace("(","&#40 ",$string_after);
        $string_after = str_replace(")","&#41 ",$string_after);
        return $string_after;
    }
	
    /**
     * 实现多种字符解码方式
     * @param $input
     * @param $_input_charset
     * @param string $_output_charset
     * @return string
     */
    function charset_decode($input, $_input_charset, $_output_charset = "utf-8"){
        $output = "";
        if (!isset($_input_charset))
            $_input_charset = $this->_input_charset;
        if ($_input_charset == $_output_charset || $input == null) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input, $_output_charset, $_input_charset);
        } elseif (function_exists("iconv")) {
            $output = iconv($_input_charset, $_output_charset, $input);
        } else
            die("sorry, you have no libs support for charset changes.");
        return $output;
    }

    /**
     * 获取浏览器语言
     * @return array|string
     */
    function getBrowserLang() {
        $acceptLan = '';
        if(isSet($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
            $acceptLan = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $acceptLan = $acceptLan[0];
        }
        return $acceptLan;
    }

    /**
     * 实现多种字符解码方式
     * @param $url
     * @param $backUrl
     */
    function updatePaymentUrl($url,$backUrl){
        global $db;
        $db->Execute("update " . TABLE_CONFIGURATION . " set configuration_value= '" . $backUrl  ."' where configuration_key= 'MODULE_PAYMENT_CARDPAY_HANDLER'");
        $db->Execute("update " . TABLE_CONFIGURATION . " set configuration_value= '" . $url  ."' where configuration_key= 'MODULE_PAYMENT_CARDPAY_BACKUP_HANDLER'");
    }

    /**
     * 获取浏览类型
     * @return string
     */
    function getBrowser(){
        if(empty($_SERVER['HTTP_USER_AGENT'])){
            return '';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'rv:11.0')){
            return 'IE';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'rv:11.0') ||
            false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 10.0')){
            return 'IE';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 9.0')){
            return 'IE';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8.0')){
            return 'IE';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0')){
            return 'IE';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0')){
            return 'IE';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')){
            return 'IE';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')){
            return 'Firefox';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Chrome')){
            return 'Chrome';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Safari')){
            return 'Safari';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Opera') ||
            false!==strpos($_SERVER['HTTP_USER_AGENT'],'OPR')){
            return 'Opera';
        }
        return 'Other';
    }

    /**
     * 初始化Javascript函数
     */
    function func_init_JS() {
        $jsInit =
            'function broserInit() {
                document.getElementById("cardpay_os").value = getOS();
                document.getElementById("cardpay_resolution").value=getResolution();
                document.getElementById("cardpay_brower").value = getBrowser();
                document.getElementById("cardpay_brower_lang").value=getBrowserLang();
                document.getElementById("cardpay_time_zone").value=getTimezone();
             }
            function pasteCard() {
                document.getElementById("cardpay_is_copycard").value = 1;
                return true;
            }
            function checkCardNum(cardNumber) {
                if(cardNumber == null || cardNumber == "" || cardNumber.length > 16 || cardNumber.length < 13) {
                    return false;
                }else if(cardNumber.charAt(0) != 3 && cardNumber.charAt(0) != 4 && cardNumber.charAt(0) != 5){
                    return false;
                }else {
                    return luhnCheckCard(cardNumber);
                }
            }
            function luhnCheckCard(cardNumber){
                var sum=0;var digit=0;var addend=0;var timesTwo=false;
                for(var i=cardNumber.length-1;i>=0;i--){
                    digit=parseInt(cardNumber.charAt(i));
                    if(timesTwo){
                        addend = digit * 2;
                        if (addend > 9) {
                            addend -= 9;
                        }
                    }else{
                        addend = digit;
                    }
                    sum += addend;
                    timesTwo=!timesTwo;
                }
                return sum%10==0;
            }
            function checkExpdate(expdate) {
                if(expdate == null || expdate == "" || expdate.length < 1) {
                    return false;
                }else {
                    return true;
                }
            }
            function checkCvv(cvv) {
                if(cvv == null || cvv =="" || cvv.length < 3 || cvv.length > 4 || isNaN(cvv)) {
                    return false;
                }else {
                    return true;
                }
            }
            function checkIssuBank(issuingBank) {
                if(issuingBank == null || issuingBank == ""  || issuingBank.length < 2 || issuingBank.length > 50) {
                    return false;
                }else {
                    return true;
                }
            }
            function getResolution() {
                return window.screen.width + "x" + window.screen.height;
            }
            function getTimezone() {
                return new Date().getTimezoneOffset()/60*(-1);
            }
            function getBrowser() {
                var userAgent = navigator.userAgent;
                var isOpera = userAgent.indexOf("Opera") > -1;
                if (isOpera) {
                    return "Opera"
                }
                if (userAgent.indexOf("Chrome") > -1) {
                    return "Chrome";
                }
                if (userAgent.indexOf("Firefox") > -1) {
                    return "Firefox";
                }
                if (userAgent.indexOf("Safari") > -1) {
                    return "Safari";
                }
                if (userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1
                    && !isOpera) {
                    return "IE";
                }
            }
            function getBrowserLang() {
                return navigator.language || window.navigator.browserLanguage;
            }
            function getOS() {
                var sUserAgent = navigator.userAgent;
                var isWin = (navigator.platform == "Win32")
                    || (navigator.platform == "Windows");
                var isMac = (navigator.platform == "Mac68K")
                    || (navigator.platform == "MacPPC")
                    || (navigator.platform == "Macintosh")
                    || (navigator.platform == "MacIntel");
                if (isMac)
                    return "Mac";
                var isUnix = (navigator.platform == "X11") && !isWin && !isMac;
                if (isUnix)
                    return "Unix";
                var isLinux = (String(navigator.platform).indexOf("Linux") > -1);
                if (isLinux)
                    return "Linux";
                if (isWin) {
                    var isWin2K = sUserAgent.indexOf("Windows NT 5.0") > -1
                        || sUserAgent.indexOf("Windows 2000") > -1;
                    if (isWin2K)
                        return "Win2000";
                    var isWinXP = sUserAgent.indexOf("Windows NT 5.1") > -1
                        || sUserAgent.indexOf("Windows XP") > -1;
                    if (isWinXP)
                        return "WinXP";
                    var isWin2003 = sUserAgent.indexOf("Windows NT 5.2") > -1
                        || sUserAgent.indexOf("Windows 2003") > -1;
                    if (isWin2003)
                        return "Win2003";
                    var isWin2003 = sUserAgent.indexOf("Windows NT 6.0") > -1
                        || sUserAgent.indexOf("Windows Vista") > -1;
                    if (isWin2003)
                        return "WinVista";
                    var isWin2003 = sUserAgent.indexOf("Windows NT 6.1") > -1
                        || sUserAgent.indexOf("Windows 7") > -1;
                    if (isWin2003)
                        return "Win7";
                }
                return "None";
            }
            function getOsLang() {
                return navigator.language || window.navigator.systemLanguage;
            }';
        return $jsInit;
    }
}
/**
 * 新增函数
 */
function request_by_CARDPAY($remote_server, $post_string)
{
	$post_string="&".$post_string;
	$context = array(
		'http' => array(
			'method' => 'POST',
			'header' => 'Content-type: application/x-www-form-urlencoded' .
						'\r\n'.'User-Agent : Jimmy\'s POST Example beta' .
						'\r\n'.'Content-length:' . strlen($post_string) + 8,
			'content' => 'mypost=' . $post_string)
		);
	$stream_context = stream_context_create($context);
	$data = file_get_contents($remote_server, false, $stream_context);
	return $data;
}
function my_zen_get_products_image_CARDPAY($product_id) 
{
    global $db;
    $sql = "select p.products_image from " . TABLE_PRODUCTS . " p where products_id='" . (int)$product_id . "'";
    $look_up = $db->Execute($sql);
    $imgurl = 'http://'.$_SERVER['HTTP_HOST'].'/images/'.$look_up->fields['products_image'];
    return $imgurl;
}
?>