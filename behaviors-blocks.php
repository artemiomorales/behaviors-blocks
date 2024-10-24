<?php
/**
 * Plugin Name:       Behaviors Blocks
 * Description:       An interactive block with the Interactivity API.
 * Version:           0.1.0
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       behaviors-blocks
 *
 * @package           create-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Render callback function for the behaviors block.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The block content.
 * @param WP_Block $block      The block instance.
 * @return string  The rendered block markup.
 */
function render_behaviors_block( $attributes, $content ) {
	if ( isset( $attributes['metadata']['bindings']['content']['args']['directive'] ) ) {
		add_filter( 'render_block_interactivity/behaviors-blocks', 'block_interactivity_render_behaviors_block', 15, 2 );
	}

	return $content;
}

function block_interactivity_render_behaviors_block( $content, $block ) {
	$attributes = $block['attrs'];

	if ( ! isset( $attributes['metadata']['bindings']['content']['args'] ) ) {
		return $content;
	}

	$args = $attributes['metadata']['bindings']['content']['args'];

	$directive    = isset( $args['directive'] ) ? $args['directive'] : null;
	$action       = isset( $args['action'] ) ? $args['action'] : null;
	$target_field = isset( $args['target_field'] ) ? $args['target_field'] : null;

	if ( ! $directive || ! $action || ! $target_field ) {
		return $content;
	}

	wp_interactivity_state(
		'interactivity/behaviors-blocks',
		array(
			'nonce'        => wp_create_nonce( 'wp_rest' ),
			'target_field' => $target_field,
			'field_value'  => get_post_meta( get_the_ID(), $target_field, true ),
			'post_id'      => get_the_ID(),
		),
	);

	// loop through all the inner blocks
	$inner_blocks = parse_blocks( $content );
	foreach ( $inner_blocks as $inner_block ) {
		if ( isset( $inner_block['attrs']['metadata']['bindings']['content'] ) ) {
			$inner_block['attrs']['metadata']['bindings']['content']['args']['action'] = $action;
		}
	}

	return sprintf(
		'<div data-wp-interactive="interactivity/behaviors-blocks"><div data-wp-on-async--%1$s="actions.%2$s">%3$s</div></div>',
		$directive,
		$action,
		do_blocks( $content )
	);
}

// Register the render callback
register_block_type_from_metadata(
	__DIR__ . '/build',
	array(
		'render_callback' => 'render_behaviors_block',
	)
);

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'custom/v1',
			'/nonce',
			array(
				'methods'  => 'GET',
				'callback' => 'generate_nonce',
			)
		);
	}
);

function generate_nonce() {
	return wp_create_nonce( 'wp_rest' );
}

function register_test_fields() {
	register_meta(
		'post',
		'like_count',
		array(
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
			'default'      => '0',
			'label'        => __( 'Like Count' ),
		)
	);
}
add_action( 'init', 'register_test_fields' );
