<?php
/**
 *
 * @category  PLugins
 * @package   Wordpress
 * @author    IPdev.ir
 * @copyright 2022 The Ideh Pardazan (ipe.ir). All rights reserved.
 * @license   https://sms.ir/ ipe license
 * @version   IPE: 1.0.19
 * @link      https://app.sms.ir
 *
 */

/*
Plugin Name:   افزونه وردپرس پنل پیامکی ایده پردازان
Plugin URI:    https://app.sms.ir
Description:   افزونه وردپرسی ارسال پیامک ایده پردازان به همراه قابلیت مدیریت پیشرفته پیامک ها با استفاده از پنل قدرتمند sms.ir. این افزونه امکان اتصال به GravityFrom, Contact7 و Woocommerce را داشته و شما می توانید به راحتی نسبت به ثبت و مدیریت پیامک های مختلف اقدام فرمایید.
Version:       1.0.19
Author:        IPdev.ir
Author URI:    https://www.ipdev.ir
Support Email: tech@ipdev.ir
*/

if (!function_exists('SMSIRApp_activate_plugin')) {
    /**
     * @return void
     */
    function SMSIRApp_activate_plugin()
    {
        global $wpdb;
        $wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sms_ir_app_notifications` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `product_id` INT(11) NOT NULL,
                `type` ENUM('promotion', 'inventory') NOT NULL,
                `name` VARCHAR(100) NOT NULL,
                `mobile` BIGINT NOT NULL,
                PRIMARY KEY(`id`)
            ) {$wpdb->get_charset_collate()};
        ");

        $wpdb->query("ALTER TABLE `{$wpdb->prefix}sms_ir_app_notifications`
            ADD UNIQUE KEY `product` (`product_id`,`type`,`mobile`);"
        );

	    $wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sms_ir_app_log` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `title` varchar(100) NOT NULL,
                `message` varchar(100) NOT NULL,
                `status` INT(11) NOT NULL,
                `created_at` DATETIME NOT NULL,
                PRIMARY KEY(`id`)
            ) {$wpdb->get_charset_collate()};
        ");
    }
}

if (!function_exists('SMSIRApp_deactivation_plugin')) {
    /**
     * @return void
     */
    function SMSIRApp_deactivation_plugin()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE `{$wpdb->prefix}sms_ir_app_notifications`");
        $wpdb->query("DROP TABLE `{$wpdb->prefix}sms_ir_app_log`");
    }
}

/**
 * @return void
 */
function SMSIRAppAdmin()
{
    add_menu_page('SMSIRApp', 'ایده پردازان', 'manage_options', 'SMSIRApp', 'SMSIRAppAdminMain', plugin_dir_url(__FILE__) . '/includes/templates/assets/img/icon.png');
    add_submenu_page('SMSIRApp', 'تنظیمات', 'تنظیمات', "administrator", 'SMSIRApp-setting', 'SMSIRAppAdminSetting');
    add_submenu_page('SMSIRApp', 'ارسال پیامک', 'ارسال پیامک', "administrator", 'SMSIRApp-test', 'SMSIRAppAdminTest');
    add_submenu_page('SMSIRApp', 'پیامک های ارسالی', 'پیامک های ارسالی', "administrator", 'SMSIRApp-send', 'SMSIRAppAdminSend');
    add_submenu_page('SMSIRApp', 'پیامک های دریافتی', 'پیامک های دریافتی', "administrator", 'SMSIRApp-receive', 'SMSIRAppAdminReceive');
    add_submenu_page('SMSIRApp', 'موجود شد خبرم کن', 'موجود شد خبرم کن', "administrator", 'SMSIRApp-inventory', 'SMSIRAppAdminInventory');
    add_submenu_page('SMSIRApp', 'تخفیف دار شد خبرم کن', 'تخفیف دار شد خبرم کن', "administrator", 'SMSIRApp-promotion', 'SMSIRAppAdminPromotion');
    add_submenu_page('SMSIRApp', 'لاگ های خطا', 'لاگ های خطا', "administrator", 'SMSIRApp-log', 'SMSIRAppAdminLog');
}

require_once(dirname(__FILE__) . '/includes/functions.php');
require_once dirname(__FILE__) . '/includes/action.php';

register_activation_hook(__FILE__, 'SMSIRApp_activate_plugin');
register_deactivation_hook(__FILE__, 'SMSIRApp_deactivation_plugin');

