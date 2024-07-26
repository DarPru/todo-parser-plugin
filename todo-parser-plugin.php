<?php
/*
Plugin Name: Todo Parser Plugin
Description: A plugin to parse todo items from API and save them to database
Version: 1.0
Author URI: https://darpru.pro
Author: DarPru
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-todo-base.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-todo-database-handler.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-todo-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-todo-parser-plugin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-todo-shortcode.php';

function run_todo_parser_plugin() {
    $db_handler = new Todo_Database_Handler();
    $plugin = new Todo_Parser_Plugin($db_handler);
    $plugin->run();

    new Todo_Shortcode($db_handler);

}
run_todo_parser_plugin();
