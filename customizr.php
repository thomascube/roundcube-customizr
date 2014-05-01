<?php

/**
 * Custom styling plugin
 *
 * Displays a custom page where usually the watermark image is shown
 * and allows to append a CSS file from config to override colors and such.
 *
 * Configuration options (to be added to main config.inc.php):
 *
 *   // define a custom watermark image (relative or absolute URL)
 *   $config['custom_watermark_image'] = './skins/custom_watermark.png';
 *
 *   // define a custom URI to be displayed instead of the empty watermark page
 *   $config['custom_watermark_uri'] = '';
 *
 *   // defines a custom CSS file which is added to every page
 *   $config['custom_stylesheet'] = './skins/custom_stylez.css';
 *
 *
 * @author Thomas Bruederli <thomas@roundcube.net>
 * @license GNU GPLv3+
 */
class customizr extends rcube_plugin
{
    public $noajax = true;
    public $task = '?(?!login|logout).*';

    private $rcmail;
    private $custom_css;
    private $watermark_uri;
    private $watermark_image;

    /**
     * Initialize the plugin
     */
    public function init()
    {
        $this->rcmail = rcube::get_instance();
        $this->custom_css = $this->rcmail->config->get('custom_stylesheet');
        $this->watermark_uri = $this->rcmail->config->get('custom_watermark_uri');
        $this->watermark_image = $this->rcmail->config->get('custom_watermark_image');

        if (!empty($this->custom_css) || !empty($this->watermark_uri) || !empty($this->watermark_image)) {
            $this->add_hook('render_page', array($this, 'render_page'));
            $this->register_action('plugin.watermark', array($this, 'watermark_page'));
        }
    }

    /**
     * Handler for the 'render_page' plugin hook
     */
    public function render_page($args)
    {
        // replace static links to <skin>/watermark.html
        if ((!empty($this->watermark_uri) || !empty($this->watermark_image)) && strpos($args['content'], 'watermark.html')) {
            $url = $this->watermark_uri ?: $this->rcmail->url('plugin.watermark');
            $args['content'] = preg_replace('!(src)="([^"]+/watermark.html)"!', '\\1="'.$url.'"', $args['content']);
            $this->rcmail->output->set_env('blankpage', $url);
        }

        // TODO: replace URL for <link rel="shortcut icon" href="xxx" />

        // append custom stylesheet
        if (!empty($this->custom_css)) {
            $this->rcmail->output->include_css($this->custom_css);
        }

        return $args;
    }

    /**
     * Handler for plugin.watermark request actions
     */
    public function watermark_page()
    {
    /*
        // render our own template and let other plugins hook into it
        $this->rcmail->output->reset();
        $templ = $this->rcmail->output->parse('custom_watermark.watermark', false, false);
        $plugin = $this->api->exec_hook("custom_watermark_page", array('content' => $templ));
        echo $plugin['content'];
        exit;
    */

        // simple search/replace of watermark background image
        if ($templ = $this->rcmail->output->get_skin_file('/watermark.html')) {
            echo preg_replace('!url\(.+watermark.+\)!U', "url('$this->watermark_image')", file_get_contents($templ));
        }
        exit;
    }
}
