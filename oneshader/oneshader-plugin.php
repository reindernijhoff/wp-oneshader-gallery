<?php

/*
Plugin Name: OneShader Gallery
Plugin URI: https://github.com/reindernijhoff/wp-oneshader-gallery
Description: Creates and updates a gallery with OneShader shaders based on a query.
Version: 1.0.0
Author: Reinder Nijhoff
Author URI: https://reindernijhoff.net/
Text Domain: oneshader-gallery
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function oneshader_install() {
}

function oneshader_fetch( $url ) {
	$response = wp_remote_get( $url );

	if ( is_wp_error( $response ) ) {
		return null;
	}

	return wp_remote_retrieve_body( $response );
}

function oneshader_do_query( $query, $timeout = HOUR_IN_SECONDS ) {
	$data = '';
	$key  = 'oneshader_' . md5( $query );

	$cached = get_transient( $key );
	if ( false !== $cached ) {
		$data = $cached;
	} else {
		$url  = 'https://oneshader.net/api/v1/' . ltrim( $query, '/' );
		$data = oneshader_fetch( $url );
		$json = json_decode( $data );

		if ( json_last_error() === JSON_ERROR_NONE ) {
			$data = wp_json_encode( $json );
			set_transient( $key, $data, $timeout + wp_rand( 0, $timeout ) );
		}
	}

	$decoded = json_decode( $data, true );

	return is_array( $decoded ) ? $decoded : array( 'objects' => array() );
}

function oneshader_list( $atts ) {
	$a = shortcode_atts(
		array(
			'username'     => false,
			'query'        => '',
			'columns'      => 2,
			'limit'        => 0,
			'hideusername' => 0,
		),
		$atts
	);

	$list    = oneshader_do_query( $a['query'] );
	$results = isset( $list['objects'] ) ? $list['objects'] : array();

	$html = '<ul class="wp-block-gallery columns-' . esc_attr( $a['columns'] ) . ' is-cropped">';

	$start  = microtime( true );
	$count  = 0;
	$ldjson = array();

	foreach ( $results as $result ) {
		$html    .= oneshader_layout_shader( $result, (int) $a['hideusername'] );
		$ldjson[] = oneshader_ld_json( $result );

		if ( microtime( true ) - $start > 15 ) {
			break;
		}

		$count++;
		if ( $a['limit'] > 0 && $count >= $a['limit'] ) {
			break;
		}
	}

	$html .= '</ul>';
	$html .= '<script type="application/ld+json">' . wp_json_encode( $ldjson ) . '</script>';

	return $html;
}

function oneshader_ld_json( $info ) {
	return array(
		'@context'           => 'https://schema.org',
		'@type'              => 'ImageObject',
		'name'               => $info['title'],
		'caption'            => $info['title'],
		'creator'            => array(
			'@type'      => 'Person',
			'name'       => $info['user_id'],
			'identifier' => $info['user_id'],
			'url'        => 'https://oneshader.net/user/' . $info['user_id'],
		),
		'description'        => $info['description'],
		'image'              => 'https://oneshader.net/thumbnail/' . $info['object_id'] . '.jpg',
		'thumbnail'          => 'https://oneshader.net/thumbnail/' . $info['object_id'] . '.jpg',
		'contentUrl'         => 'https://oneshader.net/thumbnail/' . $info['object_id'] . '.jpg',
		'sameAs'             => 'https://oneshader.net/shader/' . $info['object_id'],
		'url'                => 'https://oneshader.net/shader/' . $info['object_id'],
		'dateCreated'        => $info['date_published'],
		'identifier'         => $info['object_id'],
		'material'           => 'GLSL Fragment Shader',
		'genre'              => 'Generative Art',
		'commentCount'       => $info['comments'],
		'copyrightHolder'    => array(
			'@type'      => 'Person',
			'name'       => $info['user_id'],
			'identifier' => $info['user_id'],
			'url'        => 'https://oneshader.net/user/' . $info['user_id'],
		),
		'copyrightYear'      => gmdate( 'Y' ),
		'copyrightNotice'    => '© ' . gmdate( 'Y' ) . ' ' . $info['user_id'] . ' - OneShader',
		'creditText'         => '© ' . gmdate( 'Y' ) . ' ' . $info['user_id'] . ' - OneShader',
		'acquireLicensePage' => 'https://oneshader.net/terms',
		'license'            => $info['license'],
	);
}

// phpcs:disable
// Images are served directly from oneshader.net so we always show the most up-to-date previews.
function oneshader_layout_shader( $info, $hide_username ) {
	$html  = '<li class="blocks-gallery-item"><figure>';
	$html .= '<a href="' . esc_url( $info['url'] ) . '" title="' . esc_attr( $info['title'] . ' by ' . $info['user_id'] ) . '">';
	$html .= '<picture>';
	$html .= '<source type="image/webp" srcset="' . esc_url( $info['webp'] ) . '" />';
	$html .= '<img src="' . esc_url( $info['img'] ) . '" alt="' . esc_attr( str_replace( "\n", '&#10;', $info['description'] ) ) . '" width="512" height="512" />';
	$html .= '</picture>';
	$html .= '<figcaption>' . esc_html( $info['title'] ) . ( ! $hide_username ? '<br/>by ' . esc_html( $info['user_id'] ) : '' ) . '</figcaption>';
	$html .= '</a>';
	$html .= '</figure></li>';

	return $html;
}
// phpcs:enable

register_activation_hook( __FILE__, 'oneshader_install' );
add_shortcode( 'oneshader-list', 'oneshader_list' );

