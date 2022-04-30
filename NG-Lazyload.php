<?php

/**
Plugin Name: NG Lazyload
Description: Implements lazyload for thumbnails and content images
Author: Nikita Menshutin
Version: 1.8
Author URI: http://nikita.global

PHP version 7.2
 *
@category NikitaGlobal
@package  NikitaGlobal
@author   Nikita Menshutin <wpplugins@nikita.global>
@license  http://opensource.org/licenses/gpl-license.php GNU Public License
@link     http://nikita.global
 * */
defined('ABSPATH') or die("No script kiddies please!");
if (!class_exists("nglazyload")) {
    /**
     * Our main class goes here
     *
     * @category NikitaGlobal
     * @package  NikitaGlobal
     * @author   Nikita Menshutin <wpplugins@nikita.global>
     * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
     * @link     http://nikita.global
     */
    class Nglazyload
    {
        /**
         * Construct method
         *
         * @return void
         */
        public function __construct()
        {
            $this->prefix = 'nglazyload';
            $this->version = '1.7';
            add_action('wp_enqueue_scripts', array($this, 'scripts'));
            add_filter(
                'post_thumbnail_html',
                array(
                    $this,
                    'filterContentTags',
                )
            );
            add_filter(
                'wp_get_attachment_image_attributes',
                array(
                    $this,
                    'thumbnailFilter',
                ),
                10,
                3
            );
            add_filter('the_content', array($this, 'filterContentTags'));
            add_filter('the_content', array($this, 'filterContentBackgroundImages'));
        }

        /**
         * Filtering thumbnail attributes
         *
         * @param array  $attr       attributes
         * @param object $attachment att
         * @param array  $size       size
         *
         * @return array with added lazyload attributes
         */
        public function thumbnailFilter($attr, $attachment, $size)
        {
            $attr[NGLL::dataAttr()] = $attr['src'];
            $attr['src'] = NGLL::dataImg();
            return $attr;
        }

        /**
         * Replacing all background images in styles with
         * lazy-load attributes
         *
         * @param string $content html content
         *
         * @return string updated images if any
         */
        public function filterContentBackgroundImages($content)
        {
            $match = '#<[^>]*background\-image[^url]*url[^(]*\(([^\)]*)\)[^>]*>#';
            return preg_replace_callback(
                $match,
                function ($matches) {
                    $newtag = $matches[0];
                    $url = $matches[1];
                    $newtag = str_replace($url, NGLL::dataImg(), $newtag);
                    $newtag = str_replace(
                        '>',
                        NGLL::dataAttrValue($url, true) .
                        '>',
                        $newtag
                    );
                    return $newtag;
                },
                $content
            );
        }

        /**
         * Replacing all images with
         * lazy-load attributes
         *
         * @param string $content html content
         *
         * @return string updated images if any
         */
        public function filterContentTags($content)
        {
            $xmlprefix = '<?xml encoding="utf-8" ?>';
            $doc = new DOMDocument('1.0', 'UTF-8');
            @$doc->loadHTML(
                $xmlprefix . $content //,
                // LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );
            $images = $doc->getElementsByTagName('img');
            if ($images->length == 0) {
                return $content;
            }
            foreach ($images as $image) {
                $src = $image->getAttribute('src');
                $image->setAttribute('src', NGLL::dataImg());
                $image->setAttribute(NGLL::dataAttr(), $src);
            }
            return html_entity_decode(
                str_replace(
                    $xmlprefix,
                    '',
                    preg_replace(
                        '~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i',
                        '',
                        $doc->saveHTML()
                    )
                )
            );
        }

        /**
         * Enqueue plugin.js
         *
         * @return void
         */
        public function scripts()
        {
            wp_register_style($this->prefix.'css',
            plugin_dir_url(__FILE__).'/nglazyload.css',
            array(),
            $this->version
            );
            wp_enqueue_style($this->prefix.'css');
            wp_register_script(
                $this->prefix,
                plugin_dir_url(__FILE__) . '/plugin.js',
                array('jquery'),
                $this->version,
                true
            );
            wp_localize_script(
                $this->prefix,
                $this->prefix,
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                )
            );
            wp_enqueue_script($this->prefix);
        }
    }
}
new nglazyload();

/**
 * Our abstract class goes here
 *
 * @category NikitaGlobal
 * @package  NikitaGlobal
 * @author   Nikita Menshutin <wpplugins@nikita.global>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://nikita.global
 */
abstract class NGLL
{
    /**
     * Attribue name
     *
     * @return string attribute name
     */
    public static function dataAttr()
    {
        return 'data-ngll-src';
    }

    /**
     * Generate data html tag attribute for real image
     *
     * @param string $src        string with link
     * @param bool   $background if tag for background image
     *
     * @return string data attr
     */
    public static function dataAttrValue($src, $background = false)
    {
        $suffix = '';
        if ($background) {
            $suffix = 'b';
        }
        return ' ' . self::dataAttr() . $suffix . '="' . $src . '"';
    }

    /**
     * Base64 encoded 1x1 white gif
     * The smallest possible image src
     *
     * @return string pixel
     */
    public static function dataImg()
    {
        return 'data:image/gif;base64,' .
            'R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
    }
}
