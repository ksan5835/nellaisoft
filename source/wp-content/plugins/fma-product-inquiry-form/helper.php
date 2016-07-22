<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('fma_debug_test')) {

    function fma_debug_test($array, $exit = true) {
        echo '<pre>';
        print_r($array);
        echo '</pre>';

        if ($exit) {
            exit;
        }
    }

}
if (!function_exists('fma_get_plugin_options')) {

    function fma_get_plugin_options($section, $key, $default = '') {
        $options = get_option($section);
        

        if ((!isset($options[$key]) || $options[$key] == '') && $default) {
            return $default;
        }

        return $options[$key];
    }

}