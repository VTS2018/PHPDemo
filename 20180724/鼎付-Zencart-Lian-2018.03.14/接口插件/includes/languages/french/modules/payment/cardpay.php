<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// | Simplified Chinese version   http://www.zen-cart.cn                  |
// +----------------------------------------------------------------------+
//  $Id: cardpay.php v1.0 2008-03-20 Jack $
  
  // 支付显示
  define('MODULE_PAYMENT_CARDPAY_TEXT_TITLE', "<span style='font-weight:bold;color: red' color='red'>Paiement par carte de crédit</span>"); //支付方式名称
  define('MODULE_PAYMENT_CARDPAY_TEXT_MARK', 'Paiement par carte de crédit'); //支付方式简介
  define('MODULE_PAYMENT_CARDPAY_TEXT_DESCRIPTION', 'Paiement par carte de crédit'); //支付方式描述

  //支付logo
  define('MODULE_PAYMENT_CARDPAY_MARK_BUTTON_V_IMG', DIR_WS_MODULES . '/payment/cardpay/visa.gif');
  define('MODULE_PAYMENT_CARDPAY_MARK_BUTTON_M_IMG', DIR_WS_MODULES . '/payment/cardpay/master.gif');
  define('MODULE_PAYMENT_CARDPAY_MARK_BUTTON_J_IMG', DIR_WS_MODULES . '/payment/cardpay/jcb.gif');
  define('MODULE_PAYMENT_CARDPAY_MARK_BUTTON_A_IMG', DIR_WS_MODULES . '/payment/cardpay/ae.gif');
  define('MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_V_LOGO', '<img src="' . MODULE_PAYMENT_CARDPAY_MARK_BUTTON_V_IMG . '" alt="' . MODULE_PAYMENT_CARDPAY_TEXT_MARK . '" title="' . MODULE_PAYMENT_CARDPAY_TEXT_MARK . '" /> &nbsp;' );
  define('MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_M_LOGO', '<img src="' . MODULE_PAYMENT_CARDPAY_MARK_BUTTON_M_IMG . '" alt="' . MODULE_PAYMENT_CARDPAY_TEXT_MARK . '" title="' . MODULE_PAYMENT_CARDPAY_TEXT_MARK . '" /> &nbsp;' );
  define('MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_J_LOGO', '<img src="' . MODULE_PAYMENT_CARDPAY_MARK_BUTTON_J_IMG . '" alt="' . MODULE_PAYMENT_CARDPAY_TEXT_MARK . '" title="' . MODULE_PAYMENT_CARDPAY_TEXT_MARK . '" /> &nbsp;' );
  define('MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_A_LOGO', '<img src="' . MODULE_PAYMENT_CARDPAY_MARK_BUTTON_A_IMG . '" alt="' . MODULE_PAYMENT_CARDPAY_TEXT_MARK . '" title="' . MODULE_PAYMENT_CARDPAY_TEXT_MARK . '" /> &nbsp;' );
  define('MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_SHOW',   '<span class="smallText">' . MODULE_PAYMENT_CARDPAY_TEXT_DESCRIPTION . '</span>');
  //define('MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_LOGO',   '<img src="' . MODULE_PAYMENT_CARDPAY_MARK_BUTTON_IMG . '" alt="' . MODULE_PAYMENT_CARDPAY_TEXT_MARK . '" title="' . MODULE_PAYMENT_CARDPAY_TEXT_MARK . '" /> &nbsp;' .  '<span class="smallText">' . MODULE_PAYMENT_CARDPAY_TEXT_DESCRIPTION . '</span>');
  
  //安全图片logo
  define('MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_LOGO_NT',  '<img width="65px" src="' . DIR_WS_MODULES . 'payment/cardpay/nt.gif' . '" />&nbsp;&nbsp;');
  define('MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_LOGO_PCI', '<img width="80px" src="' . DIR_WS_MODULES . 'payment/cardpay/pci.gif'. '" />');
  define('MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_LOGO_TW',  '<img width="60px" src="' . DIR_WS_MODULES . 'payment/cardpay/tw.gif' . '" />');
  define('MODULE_PAYMENT_CARDPAY_TEXT_CATALOG_LOGO_VS',  '<img width="60px" src="' . DIR_WS_MODULES . 'payment/cardpay/vs.gif' . '" />');

  //网店后台支付配置提示
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_1_1', 'Is it open?');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_1_2', 'Do you want to accept Gleepay payments?');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_2_1', 'Merchant number');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_2_2', 'MerNo which is provided by Gleepay.');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_3_1', 'Secret key');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_3_2', 'SignKey which is used to encrypted and provided by Gleepay.');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_4_1', 'Gateway number');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_4_2', 'GateWayNo which is provided by Gleepay.');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_5_1', 'Allowable payment area');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_5_2', 'If None is selected,it can be used in anywhere.');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_6_1', 'The initial state of the order [that is, the state of the order when the order is just under, please choose Unpaid[9990]] by default');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_6_2', 'Set the status of unpaid orders made with this payment module to this value, default:Unpaid[9990].');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_7_1', 'Payment sorting');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_7_2', 'The display order of payment.');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_8_1', 'Formal payment gateway address');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_8_2', 'Please write the Payment URL.');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_9_1', 'Order number prefix');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_9_2', 'The order number prefix.');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_10_1','Allowable payment card');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_10_2','Allow payment card type.');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_11_1','Payment gateway backup address');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_11_2','Please write the Payment Backup URL.');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_12_1','Send mail or not');
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_12_2','Payment commend will be sent to merchant if it\'s paid successfully.');
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_01_1', 'SHT ID');  
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_01_2', 'Contact us'); 
  
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_02_1', 'Your SiteName');  
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_02_2', 'Format:yoursite.com'); 
  
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_03_1', 'SHT Order Url');  
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_03_2', 'Defaul:http://o.vipgob2cpay.com/95epayapi.aspx'); 

define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_04_1', 'SHT Return Url');  
define('MODULE_PAYMENT_CARDPAY_TEXT_CONFIG_04_2', 'Default:http://p.vipgob2cpay.com/method/imdoc/apidf1.aspx'); 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  //信用卡信息
  define('MODULE_PAYMENT_CARDPAY_TEXT_CREDIT_CARD_NUMBER', 'Numéro de carte:');
  define('MODULE_PAYMENT_CARDPAY_TEXT_CREDIT_CARD_CVV', 'CVV2/CSC:');
  define('MODULE_PAYMENT_CARDPAY_TEXT_CREDIT_CARD_EXPIRES', 'Date d\'Expiration:');
  define('MODULE_PAYMENT_CARDPAY_TEXT_CREDIT_CARD_ISSUING_BANK', 'Banque Emettrice:');
  define('MODULE_PAYMENT_CARDPAY_TEXT_MONTH', 'Mois');
  define('MODULE_PAYMENT_CARDPAY_TEXT_YEAR', 'Année');
  
  //异常提示信息 
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_CARD_NUM',  'Numéro de carte de crédit est incorrect !\n');
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_CARD_TYPE', 'Type de carte de crédit est non pris en charge !\n');
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_NONE_CARD', 'Paiement est pas pris en charge tout type de carte, s\'il vous plaît contactez le centre de service à la clientèle de boutique en ligne !\n'); 
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_CARD_ALLOW','Nous soutenons le type de carte de crédit : ');
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_EXP_YEAR',  'Validité de l\'année est incorrecte !\n');
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_EXP_MONTH', 'Validité du mois est incorrect !\n');
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_EXPIRE',    'Carte de crédit validité a expiré !\n');
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_CVV',       'Code de sécurité incorrect !\n');

  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_ISSUING_BANK','Carte de crédit banque émettrice est incorrect !');
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_REFRESH',     'Attendez un instant, s\'il vous plaît ne pas actualiser le paiement !');
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_RE_INPUT',    'S\'il vous plaît entrer de nouveau !');
  define('MODULE_PAYMENT_CARDPAY_TEXT_ERROR_MSG_TIMEOUT',     'Paiement exception, s\'il vous plaît contactez le centre de service à la clientèle de boutique en ligne !');

  //结果页面
  define('MODULE_PAYMENT_CARDPAY_TEXT_MIDDLE_DIGITS_MESSAGE', 'Please direct this email to the Accounting department so that it may be filed along with the online order it relates to: ' . "\n\n" . 'Order: %s' . "\n\n" . 'Middle Digits: %s' . "\n\n");
  define('MODULE_PAYMENT_CARDPAY_TEXT_SUCCESS_MESSAGE',
		'<div style="background:#ffedde;padding:10px;line-height:15px">
			<div style="font-size: 16px; font-weight: bold;">
			<span style="background-color: green; color: #FFFFFF;">%s</span></div><br/>
			<div style="padding:3px;">Payment Amount :%s</div>
			<div style="padding:3px;">Payment Status :%s</div>
			<div style="padding:3px;">Product Name   :%s</div>
			<br/>
		</div>');

 //返回结果		
 define('CARDPAY_PAYRESULT_SUCCESS','Félicitations, le paiement est réussie !');
 define('CARDPAY_PAYRESULT_FAIL','Désolé, le paiement est échec, la raison est : ');
 define('CARDPAY_PAYRESULT_WARNING','Désolé, le paiement est échec, la raison en est la validation des données a échoué, s\'il vous plaît contacter le propriétaire commercial !');
 define('CARDPAY_PAYRESULT_PROCESSING','Le paiement est maintenant le traitement, s\'il vous plaît ne répétez pas le paiement dans les 24 heures !');
  
?>