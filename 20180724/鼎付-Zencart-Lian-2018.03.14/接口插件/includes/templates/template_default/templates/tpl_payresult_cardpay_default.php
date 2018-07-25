<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=checkout_success.<br />
 * Displays confirmation details after order has been successfully processed.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_checkout_success_default.php 5407 2006-12-27 01:35:37Z drbyte $
 */
?>
<div class="centerColumn" id="checkoutSuccess">

<h1 id="checkoutSuccessHeading">
<?php echo $messageStack->output('payment_result'); ?>
</h1>

<div id="checkoutpayresultreason"><?php echo TEXT_YOUR_ORDER_PAYRESULT . $orderInfo ?></div>
<div id="checkoutSuccessOrderNumber"><?php echo TEXT_YOUR_ORDER_NUMBER . $orderNo; ?></div>
<div id="checkoutSuccessOrderAmount"><?php echo TEXT_YOUR_ORDER_AMOUNT . $orderAmount. " " . $orderCurrency; ?></div>

</div>