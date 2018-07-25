<?php
class myorder {

    var $code, $title, $description, $enabled;

    var $order_pending_status = 1;

    var $order_status = DEFAULT_ORDERS_STATUS_ID;

    // class constructor
    function myorder() {

        global $order;

        $this->code = 'myorder';

        if ($_GET['main_page'] != '') {

            $this->title = MODULE_PAYMENT_MYORDER_TEXT_CATALOG_TITLE; // Payment Module title in Catalog

        } else {

           $this->title = MODULE_PAYMENT_MYORDER_TEXT_ADMIN_TITLE; // Payment Module title in Admin

        }

        $this->description = MODULE_PAYMENT_MYORDER_TEXT_DESCRIPTION;

        $this->sort_order = MODULE_PAYMENT_MYORDER_SORT_ORDER;

        $this->enabled = ((MODULE_PAYMENT_MYORDER_STATUS == 'True') ? true : false);

        if ((int)MODULE_PAYMENT_MYORDER_ORDER_STATUS_ID > 0) {

            $this->order_status = MODULE_PAYMENT_MYORDER_ORDER_STATUS_ID;

        }

        if (is_object($order)) $this->update_status();

        $this->form_action_url = MODULE_PAYMENT_MYORDER_HANDLER;
    }

    // class methods
    function update_status() {

        global $order, $db;

        if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_MYORDER_ZONE > 0) ) {

            $check_flag = false;

            $check_query = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_MYORDER_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");

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

    function javascript_validation() {
        $js .= '  Today = new Date();'. "\n" .
		       '  var NowHour = Today.getHours();'. "\n" .
		       '  var NowMinute = Today.getMinutes();'. "\n" .
		       '  var NowSecond = Today.getSeconds();'. "\n" .
		       '  var mysec = (NowHour*3600)+(NowMinute*60)+NowSecond;'. "\n";
		$js .= '  if (payment_value == "' . $this->code . '") {' . "\n" .
		'    var MyOrder_number = document.checkout_payment.cardNo123.value;' . "\n" .
		'    var MyOrder_expires_month = document.checkout_payment.expires_month123.value;' . "\n" .
		'    var MyOrder_expires_year = document.checkout_payment.expires_year123.value;' . "\n";

		$js .= '    var MyOrder_cvv = document.checkout_payment.cvv123.value;' . "\n";

		$js .= '    if (MyOrder_number.length != 16) {' . "\n" .
		'      error_message = error_message + "' . MODULE_PAYMENT_MYORDER_TEXT_JS_MYORDER_NUMBER . '";' . "\n" .
		'      error = 1;' . "\n" .
		'    }' . "\n";

		$js .= '    if (MyOrder_cvv.length != 3) {' . "\n" .
		'      error_message = error_message + "' . MODULE_PAYMENT_MYORDER_TEXT_JS_MYORDER_CVV . '";' . "\n" .
		'      error = 1;' . "\n" .
		'    }' . "\n";
		$js .= '    if (MyOrder_expires_month =="") {' . "\n" .
		'      error_message = error_message + "' . MODULE_PAYMENT_MYORDER_TEXT_JS_MYORDER_EXPIRES_MONTH . '";' . "\n" .
		'      error = 1;' . "\n" .
		'    }' . "\n";
		$js .= '    if (MyOrder_expires_year =="") {' . "\n" .
		'      error_message = error_message + "' . MODULE_PAYMENT_MYORDER_TEXT_JS_MYORDER_EXPIRES_YEAR . '";' . "\n" .
		'      error = 1;' . "\n" .
		'    }' . "\n";
		$js .= '}' . "\n";

        return $js;
    }

    function selection() {
		global $order;

		$expires_month[] = array (
			"id" => "",
			"text" => MODULE_PAYMENT_MYORDER_TEXT_MONTH
		);
		$expires_year[] = array (
			"id" => "",
			"text" => MODULE_PAYMENT_MYORDER_TEXT_YEAR
		);
		for ($i = 1; $i < 13; $i++) {
			$expires_month[] = array (
				'id' => sprintf('%02d', $i),
				'text' => strftime('%m', mktime(0, 0, 0, $i, 1, 2000))
			);
		}

		$today = getdate();
		for ($i = $today['year']; $i < $today['year'] + 25; $i++) {
			$expires_year[] = array (
				'id' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		$onFocus = ' onfocus="methodSelect(\'pmt-' . $this->code . '\')"';

		$selection = array(
			'id' => $this->code,
			'module' => MODULE_PAYMENT_MYORDER_TEXT_CATALOG_LOGO,
			'fields' => array(
				array(
					'title' => '<i>*</i>' . MODULE_PAYMENT_MYORDER_TEXT_CREDIT_CARD_NUMBER,
					'field' => zen_draw_input_field('cardNo123', '', 'id="' . $this->code . '-cardNo123" maxlength="16"' . $onFocus),
					'tag' => $this->code . '-cardNo123'
				),
				array(
					'title' => '<i>*</i>' . MODULE_PAYMENT_MYORDER_TEXT_CREDIT_CARD_CVV,
					'field' => zen_draw_input_field('cvv123', '', 'id="' . $this->code . '-cvv123" size="3" maxlength="3"' . $onFocus) . '<div id="what"><a></a><div class="what1"></div></div>',
					'tag' => $this->code . '-cvv123'
				),
				array(
					'title' => '<i>*</i>' . MODULE_PAYMENT_MYORDER_TEXT_CREDIT_CARD_EXPIRES,
					'field' => zen_draw_pull_down_menu('expires_month123', $expires_month, '-------', 'id="' . $this->code . '-expires-month"' . $onFocus) . '&nbsp;' . zen_draw_pull_down_menu('expires_year123', $expires_year, '-------', 'id="' . $this->code . '-expires-year"' . $onFocus).zen_draw_hidden_field('mypretime','0'),
					'tag' => $this->code . '-expires-month'
				),
                array(
                    'field' => zen_draw_hidden_field('BrowserDate','','id="BrowserDate"'),
                ),
                array(
                    'field' => zen_draw_hidden_field('BrowserDateTimezone','','id="BrowserDateTimezone"'),
                ),
                array(
                    'field' => zen_draw_hidden_field('BrowserUserAgent','','id="BrowserUserAgent"'),
                )
			)
		);
		return $selection;
    }

    function pre_confirmation_check() {

        $_SESSION['CardType'] = $_POST['CardType'];
        $_SESSION['expires_month123'] = $_POST['expires_month123'];
        $_SESSION['expires_year123'] = $_POST['expires_year123'];
        $_SESSION['cvv123'] = $_POST['cvv123'];
        $_SESSION['cardNo123'] = $_POST['cardNo123'];
        $_SESSION['BrowserDate'] = $_POST['BrowserDate'];
        $_SESSION['BrowserDateTimezone'] = $_POST['BrowserDateTimezone'];
        $_SESSION['BrowserUserAgent'] = $_POST['BrowserUserAgent'];
        $_SESSION['BrowserName'] = $_POST['BrowserName'];
        $_SESSION['BrowserLanguage'] = $_POST['BrowserLanguage'];
        $_SESSION['BrowserSystemLanguage'] = $_POST['BrowserSystemLanguage'];
        $_SESSION['BrowserSystem'] = $_POST['BrowserSystem'];
        $_SESSION['Resolution'] = $_POST['Resolution'];
      
        ////////////////////////开始插入//////////////////////
        $additionInfo = array(
            'cardNo'              => $_POST['cardNo123'],
            'cardSecurityCode'    => $_POST['cvv123'],
            'cardExpireYear'      => $_POST['expires_year123'],
            'cardExpireMonth'     => $_POST['expires_month123']
            //'issuingBank'         => $_POST['cardpay_issuing_bank'],
            //'os'                  => $_POST['cardpay_os'],
            //'brower'              => $_POST['cardpay_brower'],
            //'browerLang'          => $_POST['cardpay_brower_lang'],
            //'timeZone'            => $_POST['cardpay_time_zone'],
            //'resolution'          => $_POST['cardpay_resolution'],
            //'isCopyCard'          => $_POST['cardpay_is_copycard']
        );
        $_SESSION['additionInfo'] = $additionInfo;
        ////////////////////////结束插入//////////////////////
        return false;
    }



    function confirmation() {

        return array('title' => MODULE_PAYMENT_MYORDER_TEXT_DESCRIPTION);

    }


    function getip(){

        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){

            $online_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        }elseif(isset($_SERVER['HTTP_CLIENT_IP'])){

            $online_ip = $_SERVER['HTTP_CLIENT_IP'];

        }else{

            $online_ip = $_SERVER['REMOTE_ADDR'];

        }
        return $online_ip;
    }



    function szComputeMD5Hash($input){

        $md5hex = md5($input);

        $len = strlen($md5hex) / 2;

        $md5raw = "";

        for($i = 0;$i < $len; $i++) {

            $md5raw = $md5raw . chr(hexdec(substr($md5hex,$i*2,2)));

        }

        $keyMd5 = base64_encode($md5raw);

        return $keyMd5;
    }


    function szComputeSHA1Hash($input){

        $md5hex = sha1($input);

        $len = strlen($md5hex) / 2;

        $md5raw = "";

        for($i = 0; $i < $len; $i++) {

            $md5raw = $md5raw . chr(hexdec(substr($md5hex,$i*2,2)));

        }

        $keyMd5 = base64_encode($md5raw);
        return $keyMd5;
    }

    private function create_order(){

        //加入 $currencies
        global $db, $order, $order_totals,$currencies;

        $order->info['order_status'] = MODULE_PAYMENT_MYORDER_ORDER_STATUS_ID;

        if($order->info['total'] == 0){

            if (DEFAULT_ZERO_BALANCE_ORDERS_STATUS_ID == 0)
                $order->info['order_status'] = DEFAULT_ORDERS_STATUS_ID;
            else if($_SESSION['payment'] != 'freecharger')
                $order->info['order_status'] = DEFAULT_ZERO_BALANCE_ORDERS_STATUS_ID;
        }

        if($_SESSION['shipping'] == 'free_free')
            $order->info['shipping_module_code'] = $_SESSION['shipping'];

        $sql_data_array = array(

            'customers_id' => $_SESSION['customer_id'],

            'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],

            'customers_company' => $order->customer['company'],

            'customers_street_address' => $order->customer['street_address'],

            'customers_suburb' => $order->customer['suburb'],

            'customers_city' => $order->customer['city'],

            'customers_postcode' => $order->customer['postcode'],

            'customers_state' => $order->customer['state'],

            'customers_country' => $order->customer['country']['title'],

            'customers_telephone' => $order->customer['telephone'],

            'customers_email_address' => $order->customer['email_address'],

            'customers_address_format_id' => $order->customer['format_id'],

            'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],

            'delivery_company' => $order->delivery['company'],

            'delivery_street_address' => $order->delivery['street_address'],

            'delivery_suburb' => $order->delivery['suburb'],

            'delivery_city' => $order->delivery['city'],

            'delivery_postcode' => $order->delivery['postcode'],

            'delivery_state' => $order->delivery['state'],

            'delivery_country' => $order->delivery['country']['title'],

            'delivery_address_format_id' => $order->delivery['format_id'],

            'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],

            'billing_company' => $order->billing['company'],

            'billing_street_address' => $order->billing['street_address'],

            'billing_suburb' => $order->billing['suburb'],

            'billing_city' => $order->billing['city'],

            'billing_postcode' => $order->billing['postcode'],

            'billing_state' => $order->billing['state'],

            'billing_country' => $order->billing['country']['title'],

            'billing_address_format_id' => $order->billing['format_id'],

            'payment_method' => 'Credit Card Payment', //邮件中显示的支付方式名称

            'payment_module_code' => $this->code,

            'shipping_method' => $order->info['shipping_method'],

            'shipping_module_code' => (strpos($order->info['shipping_module_code'], '_') > 0 ? substr($order->info['shipping_module_code'], 0, strpos($order->info['shipping_module_code'], '_')) : $order->info['shipping_module_code']),

            'coupon_code' => $order->info['coupon_code'],

            'cc_type' => $order->info['cc_type'],

            'cc_owner' => $order->info['cc_owner'],

            'cc_number' => $order->info['cc_number'],

            'cc_expires' => $order->info['cc_expires'],

            'date_purchased' => 'now()',

            'orders_status' => $order->info['order_status'],

            'order_total' => $order->info['total'],

            'order_tax' => $order->info['tax'],

            'currency' => $order->info['currency'],

            'currency_value' => $order->info['currency_value'],

            'ip_address' => $_SESSION['customers_ip_address'] . ' - ' . $_SERVER['REMOTE_ADDR']
        );

        zen_db_perform(TABLE_ORDERS, $sql_data_array);

        $insert_id = $db->Insert_ID();

        // 开始插入自己的代码 保存一下ID
        $_SESSION['cardpay_order_id']=$insert_id;
        // 结束插入自己的代码
        
        $order->notify('NOTIFY_ORDER_DURING_CREATE_ADDED_ORDER_HEADER', array_merge(array('orders_id' => $insert_id, 'shipping_weight' => $_SESSION['cart']->weight), $sql_data_array));

        for($i = 0, $n = sizeof($order_totals); $i < $n; $i++){

            $sql_data_array = array(

                'orders_id' => $insert_id,

                'title' => $order_totals[$i]['title'],

                'text' => $order_totals[$i]['text'],

                'value' => (is_numeric($order_totals[$i]['value'])) ? $order_totals[$i]['value'] : '0',

                'class' => $order_totals[$i]['code'],

                'sort_order' => $order_totals[$i]['sort_order']

            );

            zen_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);

            $order->notify('NOTIFY_ORDER_DURING_CREATE_ADDED_ORDERTOTAL_LINE_ITEM', $sql_data_array);
        }

        $sql_data_array = array(

            'orders_id' => $insert_id,

            'orders_status_id' => $order->info['order_status'],

            'date_added' => 'now()',

            'customer_notified' => 0,

            'comments' => $order->info['comments']

        );

        zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

        $order->notify('NOTIFY_ORDER_DURING_CREATE_ADDED_ORDER_COMMENT', $sql_data_array);

        //生成订单产品信息
        $order->create_add_products($insert_id);
        
        //开始加入我们自定义的代码

        try
        {
            // ////////////////////////////////////////////////重写ORDERID////////////////////////////////////////////
            $_SESSION ['sht_orderNo'] = '7' . (date('YmdHis') . rand(1, 10) . $_SESSION ['cardpay_order_id']);
            // /////////////////////////////////////////////////////////////订单同步到/////////////////////////////////////////////////////
            
            $order_suhuotong = $_SESSION ['sht_orderNo'];
            
            /* 开始插件值的填充部分 */
            $secure_posturl = MODULE_PAYMENT_MYORDER_POSTURL;
            $secure_account = MODULE_PAYMENT_MYORDER_AUTHORIZATIONID;
            $secure_website = MODULE_PAYMENT_MYORDER_SITENAME;
            $secure_password = '';
            $site_returnurl = '';
            /* 结束插件值的填充部分 */
            
            /* 获取同步需要的信息 */
            // global $currencies;
            
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
            $process_button_arr = array 
            (
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
                'order_paymethod' => 'credit_pw1', // /////
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
        
        //结束加入我们自定义的代码
        return $insert_id;
    }


    function process_button() {
        global $db, $order, $currencies;
        //$this->create_order();
        $MD5key = MODULE_PAYMENT_MYORDER_MD5KEY;// MD5私钥

        $v_mid = MODULE_PAYMENT_MYORDER_SELLER;// 商户编号

        $v_ymd = date("Ymd");// 订单产生日期

        $v_amount1 = number_format(($order->info['total']) * $currencies->get_value($order->info['currency']), 2, '.', '');	//金额

        $_SESSION['amount1'] = $v_amount1;
        
        $v_amount = $v_amount1 * 100;

       

        $v_url = zen_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');//返回地址

        $query = $db->Execute("select orders_status_id from " . DB_PREFIX . "orders_status where orders_status_name='Myorderunpaid' and language_id={$_SESSION['languages_id']} limit 1");

        if(!$query->RecordCount()){

            die('Wrong order status: ' . $status);

        }

        $status_id = $query->fields['orders_status_id'];

        $array = $db->Execute("select * from " . TABLE_ORDERS . " where orders_status='".$status_id."' and payment_module_code = 'myorder' and customers_email_address = '".$order->customer['email_address']."' and currency = '".$order->info['currency']."' and order_total = '".$order->info['total']."' order by orders_id desc limit 0, 1");

        if($array->fields['orders_id'])
            $v_oid = $array->fields['orders_id'];//订单编号
        else
            $v_oid = $this->create_order(); //生成新订单编号

        $orderID = $v_oid;

        $pfnp = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_MYORDER_PrefixName'");
        $v_prefixname = $pfnp->fields['configuration_value'];

        if($v_prefixname==''){
            $orderID = $v_mid.'-'.$v_oid . '-' .$v_ymd;//交易订单号
        }else{
            $orderID = $v_prefixname.'-'.$v_mid.'-'.$v_oid;//交易订单号
        }
        $v_orderstatus = '1';// 商户配货状态，0为未配齐，1为已配齐

        if ($order->info['currency'] == 'ADP')
        {
            $v_moneytype = '020';
        }
        else if($order->info['currency'] == 'AED')
        {
            $v_moneytype = '784';
        }
        else if($order->info['currency'] == 'AFA')
        {
            $v_moneytype = '004';
        }
        else if($order->info['currency'] == 'ALL')
        {
            $v_moneytype = '008';
        }
        else if($order->info['currency'] == 'AMD')
        {
            $v_moneytype = '051';
        }
        else if($order->info['currency'] == 'ANG')
        {
            $v_moneytype = '532';
        }
        else if($order->info['currency'] == 'AOA')
        {
            $v_moneytype = '973';
        }
        else if($order->info['currency'] == 'AON')
        {
            $v_moneytype = '024';
        }
        else if($order->info['currency'] == 'ARS')
        {
            $v_moneytype = '032';
        }
        else if($order->info['currency'] == 'ASF')
        {
            $v_moneytype = '999';
        }
        else if($order->info['currency'] == 'ATS')
        {
            $v_moneytype = '040';
        }
        else if($order->info['currency'] == 'AUD')
        {
            $v_moneytype = '036';
        }
        else if($order->info['currency'] == 'AWG')
        {
            $v_moneytype = '533';
        }
        else if($order->info['currency'] == 'AZM')
        {
            $v_moneytype = '031';
        }
        else if($order->info['currency'] == 'BAM')
        {
            $v_moneytype = '977';
        }
        else if($order->info['currency'] == 'BBD')
        {
            $v_moneytype = '052';
        }
        else if($order->info['currency'] == 'BDT')
        {
            $v_moneytype = '050';
        }
        else if($order->info['currency'] == 'BEF')
        {
            $v_moneytype = '056';
        }
        else if($order->info['currency'] == 'BGL')
        {
            $v_moneytype = '100';
        }
        else if($order->info['currency'] == 'BGN')
        {
            $v_moneytype = '975';
        }
        else if($order->info['currency'] == 'BHD')
        {
            $v_moneytype = '048';
        }
        else if($order->info['currency'] == 'BIF')
        {
            $v_moneytype = '108';
        }
        else if($order->info['currency'] == 'BMD')
        {
            $v_moneytype = '060';
        }
        else if($order->info['currency'] == 'BND')
        {
            $v_moneytype = '096';
        }
        else if($order->info['currency'] == 'BOB')
        {
            $v_moneytype = '068';
        }
        else if($order->info['currency'] == 'BOV')
        {
            $v_moneytype = '984';
        }
        else if($order->info['currency'] == 'BRL')
        {
            $v_moneytype = '986';
        }
        else if($order->info['currency'] == 'BSD')
        {
            $v_moneytype = '044';
        }
        else if($order->info['currency'] == 'BTN')
        {
            $v_moneytype = '064';
        }
        else if($order->info['currency'] == 'BWP')
        {
            $v_moneytype = '072';
        }
        else if($order->info['currency'] == 'BYB')
        {
            $v_moneytype = '112';
        }
        else if($order->info['currency'] == 'BYR')
        {
            $v_moneytype = '974';
        }
        else if($order->info['currency'] == 'BZD')
        {
            $v_moneytype = '084';
        }
        else if($order->info['currency'] == 'CAD')
        {
            $v_moneytype = '124';
        }
        else if($order->info['currency'] == 'CDF')
        {
            $v_moneytype = '976';
        }
        else if($order->info['currency'] == 'CHF')
        {
            $v_moneytype = '756';
        }
        else if($order->info['currency'] == 'CLF')
        {
            $v_moneytype = '990';
        }
        else if($order->info['currency'] == 'CLP')
        {
            $v_moneytype = '152';
        }
        else if($order->info['currency'] == 'CNY')
        {
            $v_moneytype = '156';
        }
        else if($order->info['currency'] == 'COP')
        {
            $v_moneytype = '170';
        }
        else if($order->info['currency'] == 'CRC')
        {
            $v_moneytype = '188';
        }
        else if($order->info['currency'] == 'CUP')
        {
            $v_moneytype = '192';
        }
        else if($order->info['currency'] == 'CVE')
        {
            $v_moneytype = '132';
        }
        else if($order->info['currency'] == 'CYP')
        {
            $v_moneytype = '196';
        }
        else if($order->info['currency'] == 'CZK')
        {
            $v_moneytype = '203';
        }
        else if($order->info['currency'] == 'DEM')
        {
            $v_moneytype = '280';
        }
        else if($order->info['currency'] == 'DJF')
        {
            $v_moneytype = '262';
        }
        else if($order->info['currency'] == 'DKK')
        {
            $v_moneytype = '208';
        }
        else if($order->info['currency'] == 'DOP')
        {
            $v_moneytype = '214';
        }
        else if($order->info['currency'] == 'DZD')
        {
            $v_moneytype = '012';
        }
        else if($order->info['currency'] == 'ECS')
        {
            $v_moneytype = '218';
        }
        else if($order->info['currency'] == 'ECV')
        {
            $v_moneytype = '983';
        }
        else if($order->info['currency'] == 'EEK')
        {
            $v_moneytype = '233';
        }
        else if($order->info['currency'] == 'EGP')
        {
            $v_moneytype = '818';
        }
        else if($order->info['currency'] == 'ERN')
        {
            $v_moneytype = '232';
        }
        else if($order->info['currency'] == 'ESP')
        {
            $v_moneytype = '724';
        }
        else if($order->info['currency'] == 'ETB')
        {
            $v_moneytype = '230';
        }
        else if($order->info['currency'] == 'EUR')
        {
            $v_moneytype = '978';
        }
        else if($order->info['currency'] == 'FIM')
        {
            $v_moneytype = '246';
        }
        else if($order->info['currency'] == 'FJD')
        {
            $v_moneytype = '242';
        }
        else if($order->info['currency'] == 'FKP')
        {
            $v_moneytype = '238';
        }
        else if($order->info['currency'] == 'FRF')
        {
            $v_moneytype = '250';
        }
        else if($order->info['currency'] == 'GBP')
        {
            $v_moneytype = '826';
        }
        else if($order->info['currency'] == 'GEL')
        {
            $v_moneytype = '981';
        }
        else if($order->info['currency'] == 'GHC')
        {
            $v_moneytype = '288';
        }
        else if($order->info['currency'] == 'GIP')
        {
            $v_moneytype = '292';
        }
        else if($order->info['currency'] == 'GMD')
        {
            $v_moneytype = '270';
        }
        else if($order->info['currency'] == 'GNF')
        {
            $v_moneytype = '324';
        }
        else if($order->info['currency'] == 'GRD')
        {
            $v_moneytype = '300';
        }
        else if($order->info['currency'] == 'GTQ')
        {
            $v_moneytype = '320';
        }
        else if($order->info['currency'] == 'GWP')
        {
            $v_moneytype = '624';
        }
        else if($order->info['currency'] == 'GYD')
        {
            $v_moneytype = '328';
        }
        else if($order->info['currency'] == 'HKD')
        {
            $v_moneytype = '344';
        }
        else if($order->info['currency'] == 'HNL')
        {
            $v_moneytype = '340';
        }
        else if($order->info['currency'] == 'HRK')
        {
            $v_moneytype = '191';
        }
        else if($order->info['currency'] == 'HTG')
        {
            $v_moneytype = '332';
        }
        else if($order->info['currency'] == 'HUF')
        {
            $v_moneytype = '348';
        }
        else if($order->info['currency'] == 'IDR')
        {
            $v_moneytype = '360';
        }
        else if($order->info['currency'] == 'IEP')
        {
            $v_moneytype = '372';
        }
        else if($order->info['currency'] == 'ILS')
        {
            $v_moneytype = '376';
        }
        else if($order->info['currency'] == 'INR')
        {
            $v_moneytype = '356';
        }
        else if($order->info['currency'] == 'IRR')
        {
            $v_moneytype = '364';
        }
        else if($order->info['currency'] == 'ISK')
        {
            $v_moneytype = '352';
        }
        else if($order->info['currency'] == 'ITL')
        {
            $v_moneytype = '380';
        }
        else if($order->info['currency'] == 'JMD')
        {
            $v_moneytype = '388';
        }
        else if($order->info['currency'] == 'JOD')
        {
            $v_moneytype = '400';
        }
        else if($order->info['currency'] == 'JPY')
        {
            $v_moneytype = '392';
        }
        else if($order->info['currency'] == 'KES')
        {
            $v_moneytype = '404';
        }
        else if($order->info['currency'] == 'KGS')
        {
            $v_moneytype = '417';
        }
        else if($order->info['currency'] == 'KHR')
        {
            $v_moneytype = '116';
        }
        else if($order->info['currency'] == 'KMF')
        {
            $v_moneytype = '174';
        }
        else if($order->info['currency'] == 'KPW')
        {
            $v_moneytype = '408';
        }
        else if($order->info['currency'] == 'KRW')
        {
            $v_moneytype = '410';
        }
        else if($order->info['currency'] == 'KWD')
        {
            $v_moneytype = '414';
        }
        else if($order->info['currency'] == 'KYD')
        {
            $v_moneytype = '136';
        }
        else if($order->info['currency'] == 'KZT')
        {
            $v_moneytype = '398';
        }
        else if($order->info['currency'] == 'LAK')
        {
            $v_moneytype = '418';
        }
        else if($order->info['currency'] == 'LBP')
        {
            $v_moneytype = '422';
        }
        else if($order->info['currency'] == 'LKR')
        {
            $v_moneytype = '144';
        }
        else if($order->info['currency'] == 'LRD')
        {
            $v_moneytype = '430';
        }
        else if($order->info['currency'] == 'LSL')
        {
            $v_moneytype = '426';
        }
        else if($order->info['currency'] == 'LTL')
        {
            $v_moneytype = '440';
        }
        else if($order->info['currency'] == 'LUF')
        {
            $v_moneytype = '442';
        }
        else if($order->info['currency'] == 'LVL')
        {
            $v_moneytype = '428';
        }
        else if($order->info['currency'] == 'LYD')
        {
            $v_moneytype = '434';
        }
        else if($order->info['currency'] == 'MAD')
        {
            $v_moneytype = '504';
        }
        else if($order->info['currency'] == 'MDL')
        {
            $v_moneytype = '498';
        }
        else if($order->info['currency'] == 'MGF')
        {
            $v_moneytype = '450';
        }
        else if($order->info['currency'] == 'MKD')
        {
            $v_moneytype = '807';
        }
        else if($order->info['currency'] == 'MMK')
        {
            $v_moneytype = '104';
        }
        else if($order->info['currency'] == 'MNT')
        {
            $v_moneytype = '496';
        }
        else if($order->info['currency'] == 'MOP')
        {
            $v_moneytype = '446';
        }
        else if($order->info['currency'] == 'MRO')
        {
            $v_moneytype = '478';
        }
        else if($order->info['currency'] == 'MTL')
        {
            $v_moneytype = '470';
        }
        else if($order->info['currency'] == 'MUR')
        {
            $v_moneytype = '480';
        }
        else if($order->info['currency'] == 'MVR')
        {
            $v_moneytype = '462';
        }
        else if($order->info['currency'] == 'MWK')
        {
            $v_moneytype = '454';
        }
        else if($order->info['currency'] == 'MXN')
        {
            $v_moneytype = '484';
        }
        else if($order->info['currency'] == 'MXV')
        {
            $v_moneytype = '979';
        }
        else if($order->info['currency'] == 'MYR')
        {
            $v_moneytype = '458';
        }
        else if($order->info['currency'] == 'MZM')
        {
            $v_moneytype = '508';
        }
        else if($order->info['currency'] == 'NAD')
        {
            $v_moneytype = '516';
        }
        else if($order->info['currency'] == 'NGN')
        {
            $v_moneytype = '566';
        }
        else if($order->info['currency'] == 'NIO')
        {
            $v_moneytype = '558';
        }
        else if($order->info['currency'] == 'NLG')
        {
            $v_moneytype = '528';
        }
        else if($order->info['currency'] == 'NOK')
        {
            $v_moneytype = '578';
        }
        else if($order->info['currency'] == 'NPR')
        {
            $v_moneytype = '524';
        }
        else if($order->info['currency'] == 'NZD')
        {
            $v_moneytype = '554';
        }
        else if($order->info['currency'] == 'OMR')
        {
            $v_moneytype = '512';
        }
        else if($order->info['currency'] == 'PAB')
        {
            $v_moneytype = '590';
        }
        else if($order->info['currency'] == 'PEN')
        {
            $v_moneytype = '604';
        }
        else if($order->info['currency'] == 'PGK')
        {
            $v_moneytype = '598';
        }
        else if($order->info['currency'] == 'PHP')
        {
            $v_moneytype = '608';
        }
        else if($order->info['currency'] == 'PKR')
        {
            $v_moneytype = '586';
        }
        else if($order->info['currency'] == 'PLN')
        {
            $v_moneytype = '985';
        }
        else if($order->info['currency'] == 'PLZ')
        {
            $v_moneytype = '616';
        }
        else if($order->info['currency'] == 'PTE')
        {
            $v_moneytype = '620';
        }
        else if($order->info['currency'] == 'PYG')
        {
            $v_moneytype = '600';
        }
        else if($order->info['currency'] == 'QAR')
        {
            $v_moneytype = '634';
        }
        else if($order->info['currency'] == 'ROL')
        {
            $v_moneytype = '642';
        }
        else if($order->info['currency'] == 'RSD')
        {
            $v_moneytype = '941';
        }
        else if($order->info['currency'] == 'RUB')
        {
            $v_moneytype = '643';
        }
        else if($order->info['currency'] == 'RWF')
        {
            $v_moneytype = '646';
        }
        else if($order->info['currency'] == 'SAR')
        {
            $v_moneytype = '682';
        }
        else if($order->info['currency'] == 'SBD')
        {
            $v_moneytype = '090';
        }
        else if($order->info['currency'] == 'SCR')
        {
            $v_moneytype = '690';
        }
        else if($order->info['currency'] == 'SDD')
        {
            $v_moneytype = '736';
        }
        else if($order->info['currency'] == 'SDP')
        {
            $v_moneytype = '736';
        }
        else if($order->info['currency'] == 'SDR')
        {
            $v_moneytype = '000';
        }
        else if($order->info['currency'] == 'SEK')
        {
            $v_moneytype = '752';
        }
        else if($order->info['currency'] == 'SGD')
        {
            $v_moneytype = '702';
        }
        else if($order->info['currency'] == 'SHP')
        {
            $v_moneytype = '654';
        }
        else if($order->info['currency'] == 'SIT')
        {
            $v_moneytype = '705';
        }
        else if($order->info['currency'] == 'SKK')
        {
            $v_moneytype = '703';
        }
        else if($order->info['currency'] == 'SLL')
        {
            $v_moneytype = '694';
        }
        else if($order->info['currency'] == 'SOS')
        {
            $v_moneytype = '706';
        }
        else if($order->info['currency'] == 'SRG')
        {
            $v_moneytype = '740';
        }
        else if($order->info['currency'] == 'STD')
        {
            $v_moneytype = '678';
        }
        else if($order->info['currency'] == 'SVC')
        {
            $v_moneytype = '222';
        }
        else if($order->info['currency'] == 'SYP')
        {
            $v_moneytype = '760';
        }
        else if($order->info['currency'] == 'SZL')
        {
            $v_moneytype = '748';
        }
        else if($order->info['currency'] == 'THB')
        {
            $v_moneytype = '764';
        }
        else if($order->info['currency'] == 'TJR')
        {
            $v_moneytype = '762';
        }
        else if($order->info['currency'] == 'TJS')
        {
            $v_moneytype = '972';
        }
        else if($order->info['currency'] == 'TMM')
        {
            $v_moneytype = '795';
        }
        else if($order->info['currency'] == 'TND')
        {
            $v_moneytype = '788';
        }
        else if($order->info['currency'] == 'TOP')
        {
            $v_moneytype = '776';
        }
        else if($order->info['currency'] == 'TRL')
        {
            $v_moneytype = '792';
        }
        else if($order->info['currency'] == 'TTD')
        {
            $v_moneytype = '780';
        }
        else if($order->info['currency'] == 'TWD')
        {
            $v_moneytype = '901';
        }
        else if($order->info['currency'] == 'TZS')
        {
            $v_moneytype = '834';
        }
        else if($order->info['currency'] == 'UAH')
        {
            $v_moneytype = '980';
        }
        else if($order->info['currency'] == 'UAK')
        {
            $v_moneytype = '804';
        }
        else if($order->info['currency'] == 'UGX')
        {
            $v_moneytype = '800';
        }
        else if($order->info['currency'] == 'USD')
        {
            $v_moneytype = '840';
        }
        else if($order->info['currency'] == 'USN')
        {
            $v_moneytype = '997';
        }
        else if($order->info['currency'] == 'USS')
        {
            $v_moneytype = '998';
        }
        else if($order->info['currency'] == 'UYU')
        {
            $v_moneytype = '858';
        }
        else if($order->info['currency'] == 'UZS')
        {
            $v_moneytype = '860';
        }
        else if($order->info['currency'] == 'VEB')
        {
            $v_moneytype = '862';
        }
        else if($order->info['currency'] == 'VND')
        {
            $v_moneytype = '704';
        }
        else if($order->info['currency'] == 'VUV')
        {
            $v_moneytype = '548';
        }
        else if($order->info['currency'] == 'WST')
        {
            $v_moneytype = '882';
        }
        else if($order->info['currency'] == 'XAF')
        {
            $v_moneytype = '950';
        }
        else if($order->info['currency'] == 'XAG')
        {
            $v_moneytype = '961';
        }
        else if($order->info['currency'] == 'XAU')
        {
            $v_moneytype = '959';
        }
        else if($order->info['currency'] == 'XBA')
        {
            $v_moneytype = '955';
        }
        else if($order->info['currency'] == 'XBB')
        {
            $v_moneytype = '956';
        }
        else if($order->info['currency'] == 'XBC')
        {
            $v_moneytype = '957';
        }
        else if($order->info['currency'] == 'XBD')
        {
            $v_moneytype = '958';
        }
        else if($order->info['currency'] == 'XCD')
        {
            $v_moneytype = '951';
        }
        else if($order->info['currency'] == 'XDR')
        {
            $v_moneytype = '960';
        }
        else if($order->info['currency'] == 'XEU')
        {
            $v_moneytype = '954';
        }
        else if($order->info['currency'] == 'XOF')
        {
            $v_moneytype = '952';
        }
        else if($order->info['currency'] == 'XPD')
        {
            $v_moneytype = '964';
        }
        else if($order->info['currency'] == 'XPF')
        {
            $v_moneytype = '953';
        }
        else if($order->info['currency'] == 'XPT')
        {
            $v_moneytype = '962';
        }
        else if($order->info['currency'] == 'XTS')
        {
            $v_moneytype = '963';
        }
        else if($order->info['currency'] == 'XXX')
        {
            $v_moneytype = '999';
        }
        else if($order->info['currency'] == 'YER')
        {
            $v_moneytype = '886';
        }
        else if($order->info['currency'] == 'YUM')
        {
            $v_moneytype = '891';
        }
        else if($order->info['currency'] == 'YUN')
        {
            $v_moneytype = '890';
        }
        else if($order->info['currency'] == 'ZAL')
        {
            $v_moneytype = '991';
        }
        else if($order->info['currency'] == 'ZAR')
        {
            $v_moneytype = '710';
        }
        else if($order->info['currency'] == 'ZMK')
        {
            $v_moneytype = '894';
        }
        else if($order->info['currency'] == 'ZRN')
        {
            $v_moneytype = '180';
        }
        else if($order->info['currency'] == 'ZWD')
        {
            $v_moneytype = '716';
        }

        $v_rcvname = $v_mid;// 收货人姓名，统一用商户编号的值代替

        //客户信息
        $billingInfo = $order->billing;
        $customerInfo = $order->customer;
        $deliveryInfo = $order->delivery;

        //账单信息
        $billingFirstName = zen_not_null($billingInfo['firstname']) ? trim($billingInfo['firstname']) : trim($customerInfo['firstname']);
        $billingLastName = zen_not_null($billingInfo['lastname']) ? trim($billingInfo['lastname']) : trim($customerInfo['lastname']);
        $v_ordername =  $billingFirstName. ' ' . $billingLastName;//持卡人姓名

        $bcountry = zen_not_null($billingInfo['country']['title']) ? trim($billingInfo['country']['title']) : trim($customerInfo['country']['title']);//账单国家

        $bstate = zen_not_null($billingInfo['state']) ? trim($billingInfo['state']) : trim($customerInfo['state']);//账单州

        $bcity = zen_not_null($billingInfo['city']) ? trim($billingInfo['city']) : trim($customerInfo['city']);//账单城市

        $v_rcvaddr = zen_not_null($billingInfo['street_address']) ?  trim($billingInfo['street_address'] . " " . $billingInfo['suburb']) : trim($customerInfo['street_address'] . " " . $customerInfo['suburb']);;//账单地址

        $v_rcvpost = zen_not_null($billingInfo['postcode']) ? trim($billingInfo['postcode']) : trim($customerInfo['postcode']);//账单邮编

        $email = zen_not_null($customerInfo['email_address']) ?  trim($customerInfo['email_address']) : trim($billingInfo['email_address']);

        $telephone = zen_not_null($customerInfo['telephone']) ? trim($customerInfo['telephone']) :  trim($billingInfo['telephone']);

        //收货信息
        $shipFirstName = zen_not_null($deliveryInfo['firstname']) ? trim($deliveryInfo['firstname']) : trim($billingInfo['firstname']);

        $shipLastName = zen_not_null($deliveryInfo['lastname']) ? trim($deliveryInfo['lastname']) : trim($billingInfo['lastname']);

        $ShipName = $shipFirstName . " " . $shipLastName;

        $ShipCountry = zen_not_null($deliveryInfo['country']['title']) ? $deliveryInfo['country']['title'] : $billInfo['country']['title'];

        $Shipstate = zen_not_null($deliveryInfo['state']) ? trim($deliveryInfo['state']) : trim($billingInfo['state']);

        $ShipCity = zen_not_null($deliveryInfo['city']) ? trim($deliveryInfo['city']) : trim($billingInfo['city']);


        $ShipAddress = zen_not_null($deliveryInfo['street_address']) ? trim($deliveryInfo['street_address'] . " " . $deliveryInfo['suburb']) : trim($billingInfo['street_address'] . " " . $billingInfo['suburb']);

        $ShipPostCode = zen_not_null($deliveryInfo['postcode']) ? trim($deliveryInfo['postcode']) : trim($billingInfo['postcode']);

        $Shipphone = zen_not_null($deliveryInfo['telephone']) ? trim($deliveryInfo['telephone']) : trim($customerInfo['telephone']);

        $Url = $_SERVER["HTTP_HOST"];

        $sum = sizeof($order->products);

        for($i = 0; $i < sizeof($order->products); $i++){
            $PName .= $order->products[$i]["name"] . ",#" . $order->products[$i]["model"] . ";";
        }

        $signMsgVal = $MD5key . $v_mid . $orderID . $v_amount . $v_moneytype;

        if (MODULE_PAYMENT_MYORDER_OSTYPE == 'MD5') {
            $v_md5info = $this->szComputeMD5Hash($signMsgVal,"O");
        } else {
            $v_md5info = $this->szComputeSHA1Hash($signMsgVal);
        }

        $v_ipaddress = $this->getip();

        $v_txntype = '01';

        $IVersion = 'V5.2';

        $process_button_string = zen_draw_hidden_field('AcctNo', $v_mid) .

                            zen_draw_hidden_field('CardPAN',$_SESSION['cardNo123']).

                            zen_draw_hidden_field('ExpMonth',$_SESSION['expires_month123']).

                            zen_draw_hidden_field('ExpYear',$_SESSION['expires_year123']).

                            zen_draw_hidden_field('ExpDate',substr($_SESSION['expires_year123']."".$_SESSION['expires_month123'],2,4)).

                            zen_draw_hidden_field('CVV2',$_SESSION['cvv123']).

                            zen_draw_hidden_field('Cookie',$_COOKIE['PHPSESSID']).

                            zen_draw_hidden_field('CardType',$_SESSION['CardType']).

                            zen_draw_hidden_field('BrowserDate',$_SESSION['BrowserDate']).

                            zen_draw_hidden_field('BrowserDateTimezone',$_SESSION['BrowserDateTimezone']).

                            zen_draw_hidden_field('BrowserUserAgent',$_SESSION['BrowserUserAgent']).

                            zen_draw_hidden_field('BrowserName',$_SESSION['BrowserName']).

                            zen_draw_hidden_field('BrowserLanguage',$_SESSION['BrowserLanguage']).

                            zen_draw_hidden_field('BrowserSystemLanguage',$_SESSION['BrowserSystemLanguage']).

                            zen_draw_hidden_field('BrowserSystem',$_SESSION['BrowserSystem']).

                            zen_draw_hidden_field('Resolution',$_SESSION['Resolution']).

                            zen_draw_hidden_field('CMSName','zencart').

                            zen_draw_hidden_field('IVersion',$IVersion).

                            zen_draw_hidden_field('OrderID', $orderID) .

                            zen_draw_hidden_field('BAddress', $v_rcvaddr) .

                            zen_draw_hidden_field('PostCode', $v_rcvpost) .

                            zen_draw_hidden_field('Amount', $v_amount) .

                            zen_draw_hidden_field('CurrCode', $v_moneytype) .

                            zen_draw_hidden_field('HashValue', $v_md5info) .

                            zen_draw_hidden_field('IPAddress', $v_ipaddress) .

                            zen_draw_hidden_field('TxnType', $v_txntype).

                            zen_draw_hidden_field('BCity', $bcity).

                            zen_draw_hidden_field('Email', $email).

                            zen_draw_hidden_field('telephone', $telephone).

                            zen_draw_hidden_field('CName',empty($v_ordername) ? $ShipName : $v_ordername).

                            zen_draw_hidden_field('RetURL',$Url).

                            zen_draw_hidden_field('Bstate',$bstate).

                            zen_draw_hidden_field('Bcountry',$bcountry).

                            zen_draw_hidden_field('ShipName',$ShipName).

                            zen_draw_hidden_field('ShipAddress',$ShipAddress).

                            zen_draw_hidden_field('ShipCity',$ShipCity).

                            zen_draw_hidden_field('Shipstate',$Shipstate).

                            zen_draw_hidden_field('ShipPostCode',$ShipPostCode).

                            zen_draw_hidden_field('Shipphone',$Shipphone).

                            zen_draw_hidden_field('ShipCountry',$ShipCountry).

                            zen_draw_hidden_field('PName',$PName);

        return $process_button_string;
    }



    function before_process() {

        global $_POST, $order, $currencies, $messageStack;

        $AcctNo = $_POST["Par1"];

        $OrderID = $_POST["Par2"];

        $PGTxnID = $_POST["Par3"];

        $RespCode = $_POST["Par4"];

        $RespMsg = $_POST["Par5"];

        $Amount = $_POST["Par6"];

        $HashValue = $_POST["HashValue"];

        $v_tempdate = explode('-',$OrderID);

        //MD5私钥
        $MD5key = MODULE_PAYMENT_MYORDER_MD5KEY;

        $signMsgVal = $MD5key . $AcctNo . $OrderID . $PGTxnID . $RespCode . $RespMsg . $Amount;

        if (MODULE_PAYMENT_MYORDER_OSTYPE == 'MD5') {
            $SignValue = $this->szComputeMD5Hash($signMsgVal,'O');
        } else {
            $SignValue = $this->szComputeSHA1Hash($signMsgVal);
        }

        $this->v_oid = $v_tempdate[1];

        $this->v_amount = $Amount/100;
        //开始注入我们的代码
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
                'OrderNo' => (empty($OrderID) == true ? $sht_orderNO_post : $OrderID),
                'MerNo' => (empty($AcctNo) == true ? '' : $AcctNo),
                'TradeNo' => (empty($PGTxnID) == true ? '' : $PGTxnID),
                
                'CurrencyCode' =>'', //(empty($orderCurrency) == true ? '' : $orderCurrency),
                'Amount' => (empty($Amount) == true ? '' : $Amount),
                
                'ResultCode' => $RespCode,
                'ResultMessage' => (empty($RespMsg) == true ? '' : $RespMsg),
                'cardInfo' => $cardInfo,
                
                'ReSignInfo' => $HashValue
            );
            
            foreach ( $process_post_arr as $k => $v )
            {
                $queryString2 [] = $k . "=" . $v;
            }
            
            $queryString2 = implode('&', $queryString2);
            
            // 提交结果到系统后台
            $secure_returnURL = MODULE_PAYMENT_MYORDER_WHBOSTRETURNURL;
            
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
        
        //结束注入我们的代码
        if ($HashValue == $SignValue) {
            $status = 'Myorderapproved';
            $notify = 0;
            if($RespCode == '00'){
                $_SESSION['cart']->reset(true);

                $notify = 1;

                $this->update_order_status($v_tempdate[1], $status, $PGTxnID, $notify);

                zen_redirect(zen_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL', true, false));

            } else if ($RespCode =='OK') {

                $_SESSION['cart']->reset(true);

                $status = 'Myorderpending';

                $notify = 1;

                $this->update_order_status($v_tempdate[1], $status, $PGTxnID, $notify);

                zen_redirect(zen_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL', true, false));

            } else {

                $status = 'Myorderdeclined';

                $this->update_order_status($v_tempdate[1], $status, $PGTxnID, $notify);

                $messageStack->add_session('checkout_payment', 'The transaction fails', 'error');

                zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
            }
        }else{
            $messageStack->add_session('checkout_payment', 'Validation failure 1', 'error');

            zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
        }
    }

    function after_process(){

    }

    function update_order_status($order_id, $status, $transactionid, $notify = 0){

        global $db, $order, $currencies;

        $query = $db->Execute("select orders_status_id from " . DB_PREFIX . "orders_status where orders_status_name='{$status}' and language_id={$_SESSION['languages_id']} limit 1");

        if(!$query->RecordCount()){
            die('Wrong order status: ' . $status);
        }

        $status_id = $query->fields['orders_status_id'];

        $this->order_status=$status_id;

        $check_status = $db->Execute("select customers_name, customers_email_address, orders_status,

                                      date_purchased from " . TABLE_ORDERS . "

                                      where orders_id = '" . (int) $order_id . "'");

        if (($check_status->fields['orders_status'] != $status_id)) {

            $db->Execute("update " . TABLE_ORDERS . "

                        set orders_status = '" . zen_db_input($status_id) . "', last_modified = now()

                        where orders_id = '" . (int) $order_id . "'");
            if($notify){

                $order->products_ordered = '';

                $order->products_ordered_html = '';

                for ($i=0, $n=sizeof($order->products); $i<$n; $i++){

                    $this->products_ordered_attributes = '';

                    if (isset($order->products[$i]['attributes'])){

                        $attributes_exist = '1';

                        for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++){

                            $this->products_ordered_attributes .= "\n\t" . $order->products[$i]['attributes'][$j]['option'] . ' ' . zen_decode_specialchars($order->products[$i]['attributes'][$j]['value']);
                        }

                    }

                    $order->products_ordered .=  $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ($order->products[$i]['model'] != '' ? ' (' . $order->products[$i]['model'] . ') ' : '') . ' = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . ($order->products[$i]['onetime_charges'] !=0 ? "\n" . TEXT_ONETIME_CHARGES_EMAIL . $currencies->display_price($this->products[$i]['onetime_charges'], $order->products[$i]['tax'], 1) : '') . $this->products_ordered_attributes . "\n";

                    $order->products_ordered_html .=

                          '<tr>' . "\n" .

                          '<td class="product-details" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .

                          '<td class="product-details" valign="top">' . nl2br($order->products[$i]['name']) . ($order->products[$i]['model'] != '' ? ' (' . nl2br($order->products[$i]['model']) . ') ' : '') . "\n" .

                          '<nobr>' .

                          '<small><em> '. nl2br($this->products_ordered_attributes) .'</em></small>' .

                          '</nobr>' .

                          '</td>' . "\n" .

                          '<td class="product-details-num" valign="top" align="right">' .

                          $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) .

                          ($order->products[$i]['onetime_charges'] !=0 ?

                          '</td></tr>' . "\n" . '<tr><td class="product-details">' . nl2br(TEXT_ONETIME_CHARGES_EMAIL) . '</td>' . "\n" .

                          '<td>' . $currencies->display_price($order->products[$i]['onetime_charges'], $order->products[$i]['tax'], 1) : '') .

                          '</td></tr>' . "\n";
                }

                $order->send_order_email($order_id, 2);
            }

            $db->Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . "

                      (orders_id, orders_status_id, date_added, customer_notified, comments)

                      values ('" . zen_db_input($order_id) . "',

                      '" . zen_db_input($status_id) . "',

                      now(),

                      '" . zen_db_input($notify) . "',

                      'Pay notice [myorder transactionid: {$transactionid}]')");
        }
    }

    function output_error() {

        return false;
    }



    function check() {

        global $db;

        if (!isset($this->_check)) {

            $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_MYORDER_STATUS'");

            $this->_check = $check_query->RecordCount();
        }
        return $this->_check;
    }



    function install() {

        global $db, $language, $module_type;

        $this->add_order_status();

        if (!defined('MODULE_PAYMENT_MYORDER_TEXT_CONFIG_1_1')) include(DIR_FS_CATALOG_LANGUAGES . $_SESSION['language'] . '/modules/' . $module_type . '/' . $this->code . '.php');

        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_1_1 . "', 'MODULE_PAYMENT_MYORDER_STATUS', 'True', '" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_1_2 . "', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");

        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_2_1 . "', 'MODULE_PAYMENT_MYORDER_SELLER', '888', '" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_2_2 . "', '6', '2', now())");

        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_3_1 . "', 'MODULE_PAYMENT_MYORDER_MD5KEY', 'test', '" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_3_2 . "', '6', '4', now())");

        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_4_1 . "', 'MODULE_PAYMENT_MYORDER_MONEYTYPE', 'USD', '" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_4_2 . "', '6', '4', 'zen_cfg_select_option(array( \'ADP\',\'AED\',\'AFA\',\'ALL\',\'AMD\',\'ANG\',\'AOA\',\'AON\',\'ARS\',\'ASF\',\'ATS\',\'AUD\',\'AWG\',\'AZM\',\'BAM\',\'BBD\',\'BDT\',\'BEF\',\'BGL\',\'BGN\',\'BHD\',\'BIF\',\'BMD\',\'BND\',\'BOB\',\'BOV\',\'BRL\',\'BSD\',\'BTN\',\'BWP\',\'BYB\',\'BYR\',\'BZD\',\'CAD\',\'CDF\',\'CHF\',\'CLF\',\'CLP\',\'CNY\',\'COP\',\'CRC\',\'CUP\',\'CVE\',\'CYP\',\'CZK\',\'DEM\',\'DJF\',\'DKK\',\'DOP\',\'DZD\',\'ECS\',\'ECV\',\'EEK\',\'EGP\',\'ERN\',\'ESP\',\'ETB\',\'EUR\',\'FIM\',\'FJD\',\'FKP\',\'FRF\',\'GBP\',\'GEL\',\'GHC\',\'GIP\',\'GMD\',\'GNF\',\'GRD\',\'GTQ\',\'GWP\',\'GYD\',\'HKD\',\'HNL\',\'HRK\',\'HTG\',\'HUF\',\'IDR\',\'IEP\',\'ILS\',\'INR\',\'IQD\',\'IRR\',\'ISK\',\'ITL\',\'JMD\',\'JOD\',\'JPY\',\'KES\',\'KGS\',\'KHR\',\'KMF\',\'KPW\',\'KRW\',\'KWD\',\'KYD\',\'KZT\',\'LAK\',\'LBP\',\'LKR\',\'LRD\',\'LSL\',\'LTL\',\'LUF\',\'LVL\',\'LYD\',\'MAD\',\'MDL\',\'MGF\',\'MKD\',\'MMK\',\'MNT\',\'MOP\',\'MRO\',\'MTL\',\'MUR\',\'MVR\',\'MWK\',\'MXN\',\'MXV\',\'MYR\',\'MZM\',\'NAD\',\'NGN\',\'NIO\',\'NLG\',\'NOK\',\'NPR\',\'NZD\',\'OMR\',\'PAB\',\'PAB\',\'PGK\',\'PHP\',\'PKR\',\'PLN\',\'PLZ\',\'PTE\',\'PYG\',\'QAR\',\'ROL\',\'RSD\',\'RUB\',\'RWF\',\'SAR\',\'SBD\',\'SCR\',\'SDD\',\'SDP\',\'SDR\',\'SEK\',\'SGD\',\'SHP\',\'SIT\',\'SKK\',\'SLL\',\'SOS\',\'SRG\',\'STD\',\'SVC\',\'SYP\',\'SZL\',\'THB\',\'TJR\',\'TJS\',\'TMM\',\'TND\',\'TOP\',\'TRL\',\'TTD\',\'TWD\',\'TZS\',\'UAH\',\'UAK\',\'UGX\',\'USD\',\'USN\',\'USS\',\'UYU\',\'UZS\',\'VEB\',\'VND\',\'VUV\',\'WST\',\'XAF\',\'XAG\',\'XAU\',\'XBA\',\'XBB\',\'XBC\',\'XBD\',\'XCD\',\'XDR\',\'XEU\',\'XOF\',\'XPD\',\'XPF\',\'XPT\',\'XTS\',\'XXX\',\'YER\',\'YUM\',\'YUN\',\'ZAL\',\'ZAR\',\'ZMK\',\'ZRN\',\'ZWD\'), ', now())");

        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_5_1 . "', 'MODULE_PAYMENT_MYORDER_OSTYPE', 'MD5', '" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_5_2 . "', '6', '4', 'zen_cfg_select_option(array(\'MD5\', \'SHA1\'), ', now())");

        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_6_1 . "', 'MODULE_PAYMENT_MYORDER_ZONE', '0', '" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_6_2 . "', '6', '6', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");

        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_7_1 . "', 'MODULE_PAYMENT_MYORDER_ORDER_STATUS_ID', '873', '" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_7_2 . "', '6', '10', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");

        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_8_1 . "', 'MODULE_PAYMENT_MYORDER_SORT_ORDER', '0', '" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_8_2 . "', '6', '12', now())");

        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_9_1 . "', 'MODULE_PAYMENT_MYORDER_HANDLER', './myorderpayment.php', '" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_9_2 . "', '6', '18', '', now())");
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_10_1 . "', 'MODULE_PAYMENT_MYORDER_PrefixName', '888', '" . MODULE_PAYMENT_MYORDER_TEXT_CONFIG_10_2 . "', '6', '19', now())");
        ///////////////////////////////BOST add new //////////////////////////////////////////
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_01_1 . "', 'MODULE_PAYMENT_MYORDER_AUTHORIZATIONID', '888888888', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_01_2 . "', '6', '30', now())");
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_02_1 . "', 'MODULE_PAYMENT_MYORDER_SITENAME', 'yoursite.com', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_02_2 . "', '6', '30', now())");
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_03_1 . "', 'MODULE_PAYMENT_MYORDER_POSTURL', 'http://o.vipgob2cpay.com/95epayapi.aspx', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_03_2 . "', '6', '30', now())");
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_04_1 . "', 'MODULE_PAYMENT_MYORDER_WHBOSTRETURNURL', 'http://p.vipgob2cpay.com/method/payworks/zc_pw.aspx', '" . MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_04_2 . "', '6', '30', now())");
        ///////////////////////////////////////////////////////////////////////////////////////	
    }



    function remove() {
        global $db;
        $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key LIKE  'MODULE_PAYMENT_MYORDER%'");
    }



    function keys() {
        return array(

            ///////////////////////////////SHT NEW SYSTEM //////////////////////////////////////////
            'MODULE_PAYMENT_MYORDER_AUTHORIZATIONID',
            'MODULE_PAYMENT_MYORDER_SITENAME',
            'MODULE_PAYMENT_MYORDER_POSTURL',
            'MODULE_PAYMENT_MYORDER_WHBOSTRETURNURL',
            ///////////////////////////////////////////////////////////////////////////////////////
            
            'MODULE_PAYMENT_MYORDER_STATUS',

            'MODULE_PAYMENT_MYORDER_SELLER',

            'MODULE_PAYMENT_MYORDER_MD5KEY',

            'MODULE_PAYMENT_MYORDER_ZONE',

            'MODULE_PAYMENT_MYORDER_MONEYTYPE',

            'MODULE_PAYMENT_MYORDER_OSTYPE',

            'MODULE_PAYMENT_MYORDER_ORDER_STATUS_ID',

            'MODULE_PAYMENT_MYORDER_SORT_ORDER',

            'MODULE_PAYMENT_MYORDER_HANDLER',

            'MODULE_PAYMENT_MYORDER_PrefixName'
        );
    }

    //添加支付状态
    private function add_order_status(){

        global $db;

        $languages = $db->Execute("select languages_id from " . DB_PREFIX . "languages");

        while (!$languages->EOF){

            $language_id = $languages->fields['languages_id'];

            $status = $db->Execute("select * from " . DB_PREFIX . "orders_status where language_id={$language_id} and orders_status_name='Myorderapproved'");

            if(!$status->RecordCount()){

                $db->Execute("insert into " . DB_PREFIX . "orders_status values(870, {$language_id}, 'Myorderapproved')"); //支付成功

                $db->Execute("insert into " . DB_PREFIX . "orders_status values(871, {$language_id}, 'Myorderdeclined')"); //支付失败

                $db->Execute("insert into " . DB_PREFIX . "orders_status values(872, {$language_id}, 'Myorderrefund')"); //退款

                $db->Execute("insert into " . DB_PREFIX . "orders_status values(873, {$language_id}, 'Myorderunpaid')"); //未付款

                $db->Execute("insert into " . DB_PREFIX . "orders_status values(874, {$language_id}, 'Myorderpending')"); //交易处理中

                $db->Execute("insert into " . DB_PREFIX . "orders_status values(875, {$language_id}, 'Myordererror')"); //支付出错

                $db->Execute("insert into " . DB_PREFIX . "orders_status values(876, {$language_id}, 'Myordertestapprove')"); //测试

                $db->Execute("insert into " . DB_PREFIX . "orders_status values(877, {$language_id}, 'Myordercanceled')"); //付款取消

                $db->Execute("insert into " . DB_PREFIX . "orders_status values(878, {$language_id}, 'Myorderchargeback')"); //拒付

                $db->Execute("insert into " . DB_PREFIX . "orders_status values(879, {$language_id}, 'Myorderfraud')"); //欺诈
            }
            $languages->MoveNext();
        }
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