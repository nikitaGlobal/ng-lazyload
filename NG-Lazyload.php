<?php

/**
Plugin Name: NG Lazyload
Plugin URI: http://nikita.global
Description: Remove width, height, implement lazyload
Author: Nikita Menshutin
Version: 1.0
Author URI: http://nikita.global

PHP version 7.2
 *
@category NikitaGlobal
@package  NikitaGlobal
@author   Nikita Menshutin <nikita@nikita.global>
@license  http://nikita.global commercial
@link     http://nikita.global
 * */
defined('ABSPATH') or die("No script kiddies please!");
if (!class_exists("nglazyload")) {
    class nglazyload
    {
        public function __construct()
        {
            $this->prefix = 'nglazyload';
            $this->version = '1.0';
            add_action('wp_enqueue_scripts', array($this, 'scripts'));
            add_filter(
                'post_thumbnail_html',
                array(
                    $this,
                    'filterContent',
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
            add_filter('the_content', array($this, 'filterContent'));
        }

        public function thumbnailFilter($attr, $attachment, $size)
        {
            unset($attr['sizes']);
            $attr['title'] = get_the_title($attachment->ID);
            $attr[NGLL::dataAttr()] = $attr['src'];
            $attr['src'] = NGLL::dataImg();
            return $attr;
        }

        public function filterContent($content)
        {
            preg_match_all('#(<img.*?>)#s', $content, $images);
            foreach ($images[0] as $tag) {
                $newtag = $tag;
                $newtag = str_replace('src=', NGLL::dataAttr() . '=', $newtag);
                $newtag = str_replace(
                    '<img',
                    '<img src="' .
                    NGLL::dataImg() .
                    '"',
                    $newtag
                );
                $content = str_replace($tag, $newtag, $content);
            }
            return $content;
        }

        /**
         * Engueue plugin.js
         *
         * @return void
         */
        public function scripts()
        {
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

abstract class NGLL
{
    /**
     * Attribue name
     *
     * @return void
     */
    public static function dataAttr()
    {
        return 'data-ngll-src';
    }

    /**
     * Generate data html tag attribute for real image
     *
     * @param string $src string with link
     *
     * @return void
     */
    public static function dataAttrValue($src)
    {
        return ' ' . self::dataAttr . '="' . $src . '" ';
    }

    /**
     * Base64 encoded 1x1 white gif
     * The smallest possible image src
     *
     * @return void
     */
    public static function dataImg()
    {
        return 'data:image/gif;base64,' .
            'R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
    }
}
