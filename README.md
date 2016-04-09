=== Simple Custom CSS Cache ===
Contributors: Rahe
Requires at least: 3.0.1
Tested up to: 4.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Put Simple Custom CSS option to a real file

== Description ==

Instead of loading WordPress on every page to get Simple Custom CSS values, create a real file and load it instead of
Simple Custom CSS style.

== Installation ==
PHP5 Required.

1.  Download, unzip and upload to your WordPress plugins directory
2.  Activate the plugin within you WordPress Administration Backend
3. That's It !

[More help installing Plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins "WordPress Codex: Installing Plugins")

== Frequently Asked Questions ==

= Where the CSS file is written ? =

By default the css file is written in your WordPress Content dir in the folder cache/scss/ .
The files names are like 10-scss.css where 10 is the blog id.

= How to change the folder where the css files are created ? =

You can use the filter 'SCSS_Cache/folder_name' and change the folder name.

= I do not see any changes, the Simple Custom CSS custom URL is still used =

Check if the folder wp-content/cache/scss is here and writable. If not so, then create it.

== Changelog ==

= 1.0 =
* Inital Release