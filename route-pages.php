<?php
/**
 * Plugin Name: Route Pages
 * Plugin URI:  http://theaveragedev.com
 * Description: A WP Router extension to manage route created pages.
 * Version:     0.1.0
 * Author:      theAverageDev (Luca Tumedei)
 * Author URI:  http://theaveragedev.com
 * License:     GPLv2+
 * Text Domain: routep
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2014 theAverageDev (Luca Tumedei) (email : luca@theaveragedev.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 * version modified by theAverageDev (Luca Tumedei)
 * http://theaveragedev.com
 */

// Composer autoload
// modified to work with PHP 5.2 thanks to 
// https://bitbucket.org/xrstf/composer-php52
include 'vendor/autoload_52.php';

/**
 * Activation and deactivation
*/
register_activation_hook(__FILE__, array('RoutePages', 'activate'));
register_deactivation_hook(__FILE__, array('RoutePages', 'deactivate'));

// Bootstrap the plugin main class
RoutePages::init();
