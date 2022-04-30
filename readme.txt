=== NG-Lazyload ===
Contributors: nikitaglobal
Plugin Name: NG-Lazyload
Tags: images, lazy load, lazyload, thumbnail, optimize content
Author URI: https://nikita.global
Author: Nikita Menshutin
Requires at least: 3.6
Tested up to: 5.9.3
Stable tag: 1.8
Requires PHP: 5.6
Version: 1.4
License: 			GPLv2 or later
License URI: 		http://www.gnu.org/licenses/gpl-2.0.html

Enables 'lazyload' for all thumbnails and images in the content.

Developed by Nikita Menshutin

[https://nikita.global/](https://nikita.global)

== Description ==

NG-Lazyload plugin replaces all the thumbails and images in the content with the smallest image possible (1 pixel gif) and then shows the full image only when it is in viewport.
The only plugin which also works with background images in style tag

== Installation ==

Use WordPress' Add New Plugin feature, searching "NG-Lazyload", or download the archive and:

1. Unzip the archive on your computer
2. Upload plugin directory to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. No more actions required

For theme developers: add nglazyload class to html blocks with background images
to lazy-load them if they are stated in css file.
If html block has style="background-image: url(...)" you don't have to.

== Changelog ==
= 1.8 (2022-05-01)
* Tested up to 5.9.3
* Minor js updates
* Fixed one filters' conflict

= 1.7 (2020-02-26)
* Tested up to 5.3.2
* Loads current and next viewport below
* Add 'nglazyload' class to your html block to hide its background images from preloading

= 1.6 (2019-09-95) =
* Tested up to 5.2.3

= 1.5 (2019-09-05) =
* jQuery noConflict script

= 1.4 (2019-05-06) =
* Slightly optimized js

= 1.3 (2019-05-05) =
* Added icon

= 1.2 (2019-05-04) =
* Now modifies style tags which have background-images. Useful when you have sliders like slick or owl caroussel.

= 1.0 (2019-05-02) =
* The First Upload, but was tested before at several sites
* Supports thumbnails, attachments and content
