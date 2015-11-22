<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Modifies Kohana to use [HTML Purifier](http://htmlpurifier.org/) for the
 * [Security::xss_clean] method.
 *
 * @package    Purifier
 * @category   Security
 * @author     Kohana Team
 * @copyright  (c) 2010 Woody Gilk
 * @license    BSD
 */
class Purifier_Security extends Kohana_Security {

    // Current purifier version
    const PURIFIER = '1.2.1';

    /**
     * @var  HTMLPurifier  singleton instance of the HTML Purifier object
     */
    protected static $htmlpurifier;

    /**
     * Returns the singleton instance of HTML Purifier. If no instance has
     * been created, a new instance will be created. Configuration options
     * for HTML Purifier can be set in `APPPATH/config/purifier.php` in the
     * "settings" key.
     *
     *     $purifier = Security::htmlpurifier();
     *
     * @return  HTMLPurifier
     */
    public static function htmlpurifier()
    {
        if ( ! Security::$htmlpurifier)
        {
            if ( ! class_exists('HTMLPurifier_Config', FALSE))
            {
                if (Kohana::$config->load('purifier.preload'))
                {
                    // Load the all of HTML Purifier right now.
                    // This increases performance with a slight hit to memory usage.
                    require 'modules/purifier/vendor/htmlpurifier/library/HTMLPurifier.includes.php';
                }

                // Load the HTML Purifier auto loader
                require 'modules/purifier/vendor/htmlpurifier/library/HTMLPurifier.auto.php';
            }

            // Create a new configuration object
            $config = HTMLPurifier_Config::createDefault();

            if ( ! Kohana::$config->load('purifier.finalize'))
            {
                // Allow configuration to be modified
                $config->autoFinalize = FALSE;
            }

            // Use the same character set as Kohana
            $config->set('Core.Encoding', Kohana::$charset);


            if (is_array($settings = Kohana::$config->load('purifier.settings')))
            {
                // Load the settings
                $config->loadArray($settings);
            }

            // Configure additional options
            $config = Security::configure($config);

            // Create the purifier instance
            Security::$htmlpurifier = new HTMLPurifier($config);


        }



        return Security::$htmlpurifier;
    }

    /**
     * Modifies the configuration before creating a HTML Purifier instance.
     *
     * [!!] You must create an extension and overload this method to use it.
     *
     * @param   HTMLPurifier_Config  configuration object
     * @return  HTMLPurifier_Config
     */
    public static function configure(HTMLPurifier_Config $config)
    {
        return $config;
    }

    /**
     * Removes broken HTML and XSS from text using [HTMLPurifier](http://htmlpurifier.org/).
     *
     *     $text = Security::xss_clean(Arr::get($_POST, 'message'));
     *
     * The original content is returned with all broken HTML and XSS removed.
     *
     * @param   mixed   text to clean, or an array to clean recursively
     * @return  mixed
     */
    public static function xss_clean($str)
    {
        if (is_array($str))
        {
            foreach ($str as $i => $s)
            {
                // Recursively clean arrays
                $str[$i] = Security::xss_clean($s);
            }

            return $str;
        }

        // Load HTML Purifier
        $purifier = Security::htmlpurifier();

        // Clean the HTML and return it
        return $purifier->purify($str);
    }

} // End Purifier Security