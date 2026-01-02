# WordPress OneShader Gallery Plugin

OneShader Gallery lets you embed curated shader galleries powered by [OneShader](https://oneshader.net). Using a single shortcode, the plugin fetches remote thumbnails directly from OneShader, keeps them cached for performance, and renders a responsive WordPress block gallery with optional metadata.

Live demo: [https://reindernijhoff.net/oneshader/](https://reindernijhoff.net/oneshader/)

## Features

* Shortcode-driven galleries.
* Adjustable column counts and item limits.
* Optional hiding of the creator username per gallery.
* Automatic JSON-LD metadata embedding for better SEO.
* Caching layer to avoid unnecessary API calls while keeping content fresh.

## Installation

1. Upload the plugin folder `oneshader` to `/wp-content/plugins/oneshader-gallery`, or install it directly from the WordPress admin Plugins screen.
2. Activate the **OneShader Gallery** plugin from **Plugins → Installed Plugins**.
3. Add the shortcode to any post or page.

## Usage

Insert the `[oneshader-list]` shortcode wherever you want the gallery to appear.

```
[oneshader-list query="shader/browse/love/"]
```

To showcase shaders from a specific user:

```
[oneshader-list query="user/USERNAME/love/"]
```

## Optional Attributes

* `query` – Required. The API query path (e.g., `shader/browse/love/`).
* `columns` – Optional; default `2`. Accepts `1`, `2`, `3`, or `4` columns.
* `limit` – Optional; default `0` (unlimited). Set to a positive integer to cap the number of shaders.
* `hideusername` – Optional; default `0`. Set to `1` to omit the creator attribution in the caption.

## External services

This plugin calls the OneShader API (`https://oneshader.net/api/v1/…`) to fetch gallery data based on the shortcode parameters. The service is provided by OneShader — review their [Terms of Service](https://oneshader.net/terms) before use.

## Frequently Asked Questions

### Why are the images loaded from OneShader directly?

To ensure you always display the latest previews without bloating your WordPress Media Library. Thumbnails are hotlinked from oneshader.net and never stored locally.

### Does the plugin store any personal data?

No. Only cached API responses (without personal data) are stored temporarily using WordPress transients for performance.
