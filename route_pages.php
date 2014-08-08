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
register_activation_hook(__FILE__, array('Route_Pages', 'activate'));
register_deactivation_hook(__FILE__, array('Route_Pages', 'deactivate'));

class Route_Pages
{
    public $version = null;
    public $path = null;
    public $uri = null;
    public $prefix = null;
    public $js_assets = null;
    public $css_assets = null;
    
    /**
     * An instance of the plugin main class, meant to be singleton.
     *
     * @var Route_Pages
     */
    private static $instance = null;
    
    /**
     * The global functions adapter used to isolate the class.
     *
     * @var tad_FunctionsAdapter or a mock object.
     */
    private $f = null;
    
    public function __construct(tad_FunctionsAdapterInterface $f = null)
    {
        if (is_null($f)) {
            $f = new tad_FunctionsAdapter();
        }
        $this->f = $f;
    }

    /**
     * Do not allow writing access to properties.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value){
        trigger_error(sprintf('Cannot set %s->%s property.',  __CLASS__, $name));
    }

    public static function the($key)
    {
        return self::$instance;
    }

    public static function get_instance()
    {
        return self::$instance;
    }

    private function init_vars()
    {
        $this->version = '0.1.0';
        $this->path = dirname(__FILE__);
        $this->uri = $this->f->plugin_basename(__FILE__ );
        $this->prefix = "routep";
        $this->js_assets = $this->uri . '/assets/js';
        $this->css_assets = $this->uri . '/assets/css';
    }

    /*
     * Default initialization for the plugin:
     * - Initializes the plugin vars
     * - Hooks into actions and filters
     * - Registers the default textdomain.
     */
    public static function init()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        self::$instance->init_vars();
        self::$instance->hook();

        $locale = apply_filters('plugin_locale', get_locale(), 'routep');
        load_textdomain('routep', WP_LANG_DIR . '/routep/routep-' . $locale . '.mo');
        load_plugin_textdomain('routep', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Hook into actions and filters here
     *
     */
    public function hook()
    {

    }
    /**
     * Activate the plugin
     */
    public static function activate()
    {
        // do something here
    }
    /**
     * Deactivate the plugin
     * Uninstall routines should be in uninstall.php
     */
    public static function deactivate()
    {
        // do something here
    }
}

// Bootstrap the plugin main class
Route_Pages::init();
