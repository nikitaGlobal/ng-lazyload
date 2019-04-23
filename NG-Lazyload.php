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
            add_filter('post_thumbnail_html', array($this, 'postThumbnailRemoveSize'), 1, 5);
            add_filter('wp_get_attachment_image_attributes', array($this, 'thumbnailFilter'), 10, 3);
        }

        public function thumbnailFilter($attr, $attachment, $size)
        {
            unset($attr['sizes']);
            $attr['title'] = get_the_title($attachment->ID);
            return $attr;
        }

        public function postThumbnailRemoveSize($html, $post_id, $post_thumbnail_id, $size, $attr)
        {
            $id = get_post_thumbnail_id();
            $src = wp_get_attachment_image_src($id, $size);
            $alt = get_the_title($id);
            $class = '';
            if (isset($attr['class'])) {
                $class = $attr['class'];
            }
            if (strpos($class, 'retina') !== false) {
                $html = '<img src="" alt="" data-src="' . $src[0] . '" data-alt="' . $alt . '" class="' . $class . '" />';
            } else {
                $html = '<img src="' .
                NGLL::dataImg() .
                    '" data-ngll-src="' . $src[0] . '" alt="' . $alt . '" class="' . $class . '" />';
            }
            return $html;
        }

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
    public static function dataImg()
    {
        return 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
    }
}
