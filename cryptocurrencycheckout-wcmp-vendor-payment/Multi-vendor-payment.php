<?php
/**
 * Plugin Name: CryptocurrencyCheckout Woocommerce WCMP Multi-Vendor Payment
 * Plugin URI: https://cryptocurrencycheckout.com/
 * Description: This Plugin and Child Theme mod adds a way for Vendors to Request Crypto Payments from Admins on the Woocommerce WCMP Multi-Vendor Plugin. (if you want to accept Crypto Payments from Buyers as well use the CryptocurrencyCheckout Woocommerce Payment Gateway plugin). 
 * Version: 1.0.0
 * Author: cryptocurrencycheckout
 * Text Domain: cryptocurrencycheckout-wc-wcmp-vendor-payments
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2018-2019 CryptocurrencyCheckout (support@cryptocurrencycheckout.com), WooCommerce, and WCMarketplace
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-CryptocurrencyCheckout-WCMP-Vendor-Payment
 * @author    CryptocurrencyCheckout
 * @category  Admin
 * @copyright Copyright (c) 2018-2019 CryptocurrencyCheckout and WooCommerce
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */

defined( 'ABSPATH' ) or exit;


// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}

// Make sure WCMP is active
if ( ! in_array( 'dc-woocommerce-multi-vendor/dc_product_vendor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}


add_filter( 'automatic_payment_method', function( $wcmp_payment_methods ){
   
    $cc_payment_methods = array('crypto_enable' => __('Enable Cryptocurrenices ("Direct Bank Transfer" must also be enabled.) ', 'dc-woocommerce-multi-vendor'), 'crypto_btc' => __('CC Bitcoin', 'dc-woocommerce-multi-vendor'), 'crypto_ltc' => __('CC Litecoin', 'dc-woocommerce-multi-vendor'), 'crypto_eth' => __('CC Ethereum', 'dc-woocommerce-multi-vendor'), 'crypto_dash' => __('CC Dash', 'dc-woocommerce-multi-vendor'), 'crypto_arrr' => __('CC PirateChain', 'dc-woocommerce-multi-vendor'), 'crypto_znz' => __('CC Zenzo', 'dc-woocommerce-multi-vendor'), 'crypto_cdzc' => __('CC CryptoDezireCash', 'dc-woocommerce-multi-vendor'), 'crypto_send' => __('CC SocialSend', 'dc-woocommerce-multi-vendor'), 'crypto_colx' => __('CC ColossusXT', 'dc-woocommerce-multi-vendor'), 'crypto_thc' => __('CC HempCoin', 'dc-woocommerce-multi-vendor'), 'crypto_eca' => __('CC Electra', 'dc-woocommerce-multi-vendor'), 'crypto_pivx' => __('CC Pivx', 'dc-woocommerce-multi-vendor'), 'crypto_nbr' => __('CC Niobio', 'dc-woocommerce-multi-vendor'), 'crypto_gali' => __('CC Galilel', 'dc-woocommerce-multi-vendor'), 'crypto_bitc' => __('CC Bitcash', 'dc-woocommerce-multi-vendor'), 'crypto_ok' => __('CC OKcash', 'dc-woocommerce-multi-vendor'), 'crypto_ethplo' => __('CC ETHplode', 'dc-woocommerce-multi-vendor'), 'crypto_veil' => __('CC Veil', 'dc-woocommerce-multi-vendor'), 'crypto_doge' => __('CC Doge', 'dc-woocommerce-multi-vendor'));
    $wcmp_payment_methods = array_merge($wcmp_payment_methods, $cc_payment_methods);
   
   return $wcmp_payment_methods;
   
 });


 
add_filter( 'wcmp_transaction_item_totals', 'CC_transaction_item_totals', 10, 2);

function CC_transaction_item_totals( $item_totals, $transaction_id ){

   $payment_admin_settings = get_option('wcmp_payment_settings_name');
   
   if (isset($payment_admin_settings['payment_method_crypto_enable'])) {
      
      $filterOutKeys = array( 'bank_account_type', 'aba_routing_number', 'bank_address', 'destination_currency', 'iban', 'bank_name' );
      $item_totals = array_diff_key( $item_totals, array_flip( $filterOutKeys ) );
   
      array_walk_recursive($item_totals, 'text_replacer');
   }

   return $item_totals;
}


function text_replacer(& $item, $key){

  if ($key == 'value') {
     $item = str_replace("Direct Bank", "Cryptocurrency", $item);
  }

  if ($key == 'label') {
     $item = str_replace("Bank Account Number", "Wallet Address", $item);
  }

  if ($key == 'label') {
     $item = str_replace("Account Holder Name", "Cryptocurrency", $item);
  }

}
