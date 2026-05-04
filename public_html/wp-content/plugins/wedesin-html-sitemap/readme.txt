=== Digihood HTML Sitemap ===
Contributors: Josif201
Author: digihood
Author URI: https://digihood.co.uk/
Plugin URI: https://digihood.co.uk/digihood-services/creating-plugin-for-wordpress/html-sitemap
Tags: sitemap, html sitemap, seo, simple sitemap, google sitemap
Requires at least: 4.0
Tested up to: 6.5.5
Stable tag: 3.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple sitemaps with customizable content and minimal styling. 
== Description ==
Digihood HTML Sitemap is a plugin for simple sitemaps creation using shortcodes. Create a map of the entire web or only specified posts/pages/custom post types, taxonomies and their terms, posts under specified taxonomy, or its term, or attachments. Sitemaps are customizable by theme or custom CSS with minimal default styling.
Blacklisting options included.

1. Sitemap of the entire web:
Example: [html-sitemap exclude="1,349" exclude_type=”book,product”]
(exclude and exclude_type are optional parameters for blacklisting - it does not work for 'page' post type, inputs are post IDs and post type keys)

2. Attachment sitemap:
Example: [attachment-sitemap file-type="application/pdf"]
(file-type is optional and uses MIME codes, default: all documents )
listing of all attachments of a specified type from wordpress admin.

3. Post-type sitemap:
Example: [post-type-sitemap post-type="post"]
(post-type parameter required)
listing of all posts under a specified post-type

4. Posts under taxonomy or term:
Example: [tax-post-sitemap taxonomy="category" term="news"]
listing of all posts under specified taxonomy/term

5. Taxonomy terms sitemap:
Example: [tax-term-sitemap taxonomy="post_tag"]
(taxonomy is required, optional parameter hide-empty="true/false" - default = false)
listing of all terms under specified taxonomy

Note: No additional styling has been added to the code except style="clear: both;" and the class "whs-wrap", which wraps the whole list and can be used for further personal styling. We recommend adding your styling outside the plugin itself since the changes could be overwritten by future updates.
    
== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wedesin-html-sitemap` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Optionaly fill out the settings page for the localization of your post-type or taxonomy names
4. Add sitemaps to your posts/pages using shortcodes
(You can find documentation in wordpress admin -> plugin settings)

== Frequently Asked Questions ==

= Where is the settings page? =

Settings page can be found under the Digi plugins tab

= Why don't I see my post's language in the settings? =

The settings fields for taxonomy/post-type headings in the administration will be visible if the language is installed in the administration. If you have posts in a language that is not added to the administration, you won't be able to customize headings for the sitemaps in these posts.

== Changelog ==

= 3.10 (2024-07-15) =
!SHORTCODE CHANGES: 
    digi-attachment-sitemap -> attachment-sitemap
    tax-sitemap -> tax-post-sitemap
new shortcode [tax-term-sitemap] enables listing term pages of individual taxonomies

= 3.00 (2023-07-13) = 
Added two new shortcodes [tax-sitemap], [post-type-sitemap] with some options for further filtering the sitemap content. Completely recreated the [attachment-sitemap] tag and added the possibility of filtering the content by MIME file-type. Added new settings and documentation admin page. Unified branding with other Digihood products planned for future release.

= 2.10 (2022-02-24) =
Fixed the schedules post error and added compatibility for latest WordPress version

= 2.00 (2019-12-12) =
Plugin moved to objective PHP and updated to latest WP version

= 1.03 (2016-10-27) =
Added the option to add Attachement list with the [attachment-sitemap] shortode. 
The return_post_type_posts_WHS_wedesin function updated to feed the attachments.
Posts are now listed by date.
Fixed bug in the options_to_array_WHS_wedesin function.

= 1.02 (2016-09-12) =
Added the option to exclude post types.

= 1.01 (2016-07-31) =
Class updated to whs-wrap.

= 1.0 (2016-07-31) =
Plugin published