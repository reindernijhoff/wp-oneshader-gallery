# Wordpress OneShader Gallery Plugin

This Wordpress plugin enables a shortcode that can be used to add a gallery with [OneShader](https://oneshader.net) shaders to your Worpress site. The content of the gallery is based on a _query_ attribute of the shortcode. The content of the gallery will update automatically.

You can find a live demo of this plugin [here](https://reindernijhoff.net/oneshader/).

Note:
- This is (one of) the first Wordpress plugins I have ever made. 

I don't want to DDoS OneShader and I want a fast plugin. Therefore the result of a query will be cached for (at least) one day.

## Installation

Copy the _oneshader_ directory into _wp-content/plugins_ and activate the plugin in the Admin.

## Basic usage

Add a _oneshader-list_ shortcode to your post or page. For example:

```
[oneshader-list query="shader/browse/love/"]
```

## Optional attributes

You can use the following (optional) attributes:

- *query* - The query term.
- *columns* (optional, default = 2) - Number of columns of the gallery. Values 1,2,3 and 4 are supported.
- *limit* (optional, default = 0) - Maximum number of shaders in the gallery if limit > 0.
