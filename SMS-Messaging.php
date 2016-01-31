<?php

    /*
    Plugin Name: SMS Messaging
    Plugin URI: http://www.ballstateultimate.com
    Description: Plugin for generating text reminders
    Author: Brandon Groff
    Version: 0.1
    Author URI: http://www.mynamesbg.me
    */
global $wpbd;

$table_name = $wpdb->prefix . "SMS";
$table_name2 = $wpdb->prefix . "SMS_config";

function addCSS() {
    wp_register_style( 'smsCss', plugins_url( 'sms.css', __FILE__ ) );
    wp_enqueue_style('smsCss');
}

function addJS() {
    wp_register_script('jQuery', plugins_url('jquery-2.2.0.min.js', __FILE__));
    wp_register_script('smsJS', plugins_url('sms.js', __FILE__));
    wp_enqueue_script('jQuery');
    wp_enqueue_script('smsJS');
}

function smsAdmin(){
    include('messagingForm.php');
}

function sms_admin_actions() {
    add_options_page("SMS Messaging", "SMS Messaging", 1, "SMS Messaging", "smsAdmin");
}

function create_post_type(){
    register_post_type( 'SMS Message',
    array(
      'labels' => array(
        'name' => __( 'SMS Message' ),
        'singular_name' => __( 'SMS' )
      ),
      'public' => true,
      'has_archive' => false,
    )
  );
}

function db_install(){
    global $wpdb;
    global $table_name;
    global $table_name2;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      name text NOT NULL,
      phone_number varchar(10) NOT NULL,
      carrier varchar(30) NOT NULL,
      sms_email varchar(35) NOT NULL,
      PRIMARY KEY(id)
    ) $charset_collate;";

    $sql2 = "CREATE TABLE $table_name2 (
      from_email varchar(40) NOT NULL,
      PRIMARY KEY(from_email)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    dbDelta( $sql2 );
}

db_install();
add_action('admin_init', 'addCSS');
add_action('admin_init', 'addJS');

//add_action('init', 'create_post_type');

add_action('admin_menu', 'sms_admin_actions');

?>