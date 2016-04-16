<?php
/*********************************************************************************************************************
 *
 * Main plugin file: sets everything up.
 *
 *********************************************************************************************************************/

namespace jdp\WP\AttachmentCentrePoint;

class Plugin
{
    /**
     * Constructor: sets everything up.
     */
    private function __construct()
    {
        $this->bindFilters();

        // manually enqueue assets, because… hey
        add_action('admin_enqueue_scripts', array($this, 'addAssets'));
    }

    /* -- Lifecycle functions -- */
    /**
     * Called by plugin_init function: starts the plugin up.
     */
    public static function init()
    {
        return new self();
    }

    /**
     * Called on plugin activation.
     */
    public static function activate()
    {
        // nothing in this plugin
    }

    /**
     * Called on plugin deactivation: performs any tidy-up
     */
    public static function deactivate()
    {
        // does nothing in this plugin
    }

    /** -- Hook functions -- */
    /**
     * Binds filters.
     */
    private function bindFilters()
    {
        Filters::init();
    }

    /**
     * Adds JS and CSS to admin system.
     */
    public function addAssets($sPage)
    {
        $sRootPath = plugin_dir_url(__DIR__).'assets/';
        wp_enqueue_script('jdp/wp/acp/script', "{$sRootPath}acp.js");
        wp_enqueue_style('jdp/wp/acp/css',    "{$sRootPath}acp.css");
    }
}
