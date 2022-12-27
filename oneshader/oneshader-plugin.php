<?php

/*
Plugin Name: OneShader Gallery
Plugin URI: https://github.com/reindernijhoff/wp-oneshader-gallery
Description: Creates and update a gallery with OneShader ditties based on a query.
Version: 0.1
Author: Reinder Nijhoff
Author URI: https://reindernijhoff.net/
*/

$oneshader_db_version = '1.0';

function oneshader_install() {
}

function oneshader_curl_get_contents($url) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}

function oneshader_do_query($query, $timeout = 60*60) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'oneshader';

	$timeout += intval(rand(0, $timeout)); // prevent that all cached items get invalid at the same time

	$json = '';

	$dbkey = $query;

	$cached = get_transient( $dbkey );
	if ($cached) {
		$json = $cached;
	} else {
		$url = 'https://oneshader.net/api/v1/' . $query;
		$json = oneshader_curl_get_contents($url);

		json_decode($json);
		if (json_last_error() != JSON_ERROR_NONE) {
    		set_transient( $dbkey, $json, $timeout );
		}
	}

	$obj = json_decode($json, true);
	return $obj;
}

function oneshader_list($atts) {
	$a = shortcode_atts( array(
		'username' => false,
		'query' => '',
		'columns' => 3,
		'limit' => 0,
		'hideusername' => 0
	), $atts );

	$username = $a['username'];
	$limit = $a['limit'];

	$list = oneshader_do_query($a['query']);
	$results = $list["objects"];

	$html = '<ul class="wp-block-gallery columns-' . $a['columns'] . ' is-cropped">';

	$start = microtime(true);

    $count = 0;
	foreach ($results as $key => $turtle) {
		$info = $turtle;

		$html .= oneshader_layout_ditty($info, $a['hideusername']);

		if (microtime(true) - $start > 15) {
			break;
		}

		$count ++;
		if ($limit > 0 && $count >= $limit) {
		    break;
		}
	}


	$html .= '</ul>';	 
    return $html;
}

function oneshader_layout_ditty($info, $hideusername) {
	$html = '<li class="blocks-gallery-item" style="object-fit:cover; aspect-ratio: 16/9;"><figure>';
	$html .= '<a href="' . $info['url'] . '" title="' . htmlentities($info['title'] . ' by ' . $info['user_id']) .'">';
	$html .= '<picture>';
	$html .= '<source type="image/webp" srcset="' . $info['webp'] . '" />';
	$html .= '<img src="' . $info['img'] . '" alt="' . str_replace("\n", '&#10;', htmlentities($info['description'])) . '" width="512" height="512" />';
	$html .= '</picture>';
	$html .= '<figcaption>' . $info['title'] . (!$hideusername?'<br/>by ' . $info['user_id']:'') . '</figcaption>';
	$html .= '</a>';
	$html .= '</figure></li>';

	return $html;
}

register_activation_hook( __FILE__, 'oneshader_install' );
add_shortcode('oneshader-list', 'oneshader_list');
