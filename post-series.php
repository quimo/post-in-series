<?php

/*
    Plugin Name: Post series
    Description: Plugin WordPress per la gestione di post collegati in una serie.
    Author: Simone Alati
    Version: 0.1
    Author URI: https://github.com/quimo/post-series
    Text Domain: post-series
*/

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/inc/post-series.class.php";