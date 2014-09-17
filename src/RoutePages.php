<?php

class RoutePages
{

    /**
     * The name of the database option that will store generated route
     * pages meta information.
     */
    const META_OPTION_NAME = 'Route_Pages_Meta';

    /**
     * The tag of the filter that will prevent route page generation from
     * happening if boolean `false` is returned. See RoutePages_PageManager
     * class for usage.
     */
    const SHOULD_GENERATE_ROUTE_POSTS = 'RoutePages_should_generate_route_posts';

    public $version = null;
    public $path = null;
    public $uri = null;
    public $prefix = null;
    public $js_assets = null;
    public $css_assets = null;

    /**
     * An instance of the plugin main class, meant to be singleton.
     *
     * @var RoutePages
     */
    private static $instance = null;

    /**
     * The global functions adapter used to isolate the class.
     *
     * @var tad_FunctionsAdapter or a mock object.
     */
    private $f = null;

    /**
     * @var RoutePages_PageManager
     */
    private $pageManager = null;

    public function __construct(RoutePages_PageManager $pageManager = null, tad_FunctionsAdapterInterface $f = null)
    {
        $this->f = $f ? $f : new tad_FunctionsAdapter();
        $this->pageManager = $pageManager ? $pageManager : new RoutePages_PageManager();
    }

    public function set_locale()
    {
        $locale = apply_filters('plugin_locale', get_locale(), 'routep');
        load_textdomain('routep', WP_LANG_DIR . '/routep/routep-' . $locale . '.mo');
        load_plugin_textdomain('routep', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Do not allow writing access to properties.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        trigger_error(sprintf('Cannot set %s->%s property.', __CLASS__, $name));
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
        $this->uri = $this->f->plugin_basename(__FILE__);
        $this->prefix = "routep";
        $this->js_assets = $this->uri . '/assets/js';
        $this->css_assets = $this->uri . '/assets/css';
    }

    /*
    * Default initialization for the plugin:
    * - Initializes the plugin vars
    * - Hooks into actions and filters
    * - Registers the default textdomain.
    * - Deals with route pages creation.
    */
    public static function init()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        self::$instance->init_vars();
        self::$instance->hook();
        self::$instance->set_locale();

        self::$instance->pageManager->maybeGenerateRoutePages();
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
// check for the plugin dependencies
        $routePages = new tad_Plugin('Route Pages', 'route-pages', __FILE__);
        $routePages->requires('WP Router', 'wp-router', 'https://wordpress.org/plugins/wp-router/', 'wp-router/wp-router.php');
        $routePages->checkRequirements();
    }

    /**
     * Deactivate the plugin
     * Uninstall routines should be in uninstall.php
     */
    public static function deactivate()
    {
// if some pages have been created remove them
//        self::$instance->pageManager->removeRoutePages();
    }
}
