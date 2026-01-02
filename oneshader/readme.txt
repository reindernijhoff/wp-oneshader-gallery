=== OneShader Gallery ===
Contributors: reinder
Donate link: https://buymeacoffee.com/reindernijhoff
Tags: gallery, shader, shortcode, oneshader
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin provides a shortcode to display a gallery of OneShader shaders on your WordPress site.

== Description ==

OneShader Gallery allows you to display curated GLSL shaders rendered on [OneShader](https://oneshader.net). With a simple shortcode you can pull in remote thumbnails, keep them cached, and showcase the latest creations directly in WordPress.

Features include:

* Shortcode-driven galleries.
* Columns and layout configuration.
* Ability to limit how many shaders to show.
* Option to hide the original creator’s username.
* Automatic JSON-LD metadata for search engines.

= External Image Notice =

This plugin loads thumbnails directly from OneShader’s external servers.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/oneshader-gallery` directory, or install the plugin through the WordPress “Plugins” screen directly.
2. Activate the plugin through the “Plugins” screen in WordPress.
3. Add the `[oneshader-list query="shader/browse/love/"]` shortcode (or any other query) to your posts or pages to display a OneShader gallery.

== Usage ==

Insert the `[oneshader-list]` shortcode wherever you want the gallery to appear.

Example:

`[oneshader-list query="shader/browse/love/"]`

To show only shaders from a specific user:

`[oneshader-list query="user/USERNAME/love/"]`

== Optional Attributes ==

* `query` – Required. The query term or user filter.
* `columns` – Optional; default=2. Number of columns (1, 2, 3, or 4).
* `limit` – Optional; default=0 (unlimited). Maximum number of shaders to show if set > 0.
* `hideusername` – Optional; default=0. Set to 1 to hide the shader’s username.

== External services ==

This plugin connects to the OneShader API to fetch a list of all shaders that are displayed in the gallery.

It sends the optional shortcode arguments as query parameters.
This service is provided by OneShader: [Terms of Service](https://oneshader.net/terms).

== Frequently Asked Questions ==

= Why are images loaded from an external source? =

OneShader Gallery hotlinks images from the official API so you always serve the most up-to-date thumbnails without storing them locally.

= Does this plugin store any images or data on my server? =

No. We do not store any images locally, nor do we collect personal data. API responses are cached temporarily using WordPress transients for performance.

== Screenshots ==

1. Example screenshot of an OneShader gallery using the plugin.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
