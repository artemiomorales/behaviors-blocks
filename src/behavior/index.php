<?php

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
function render_behavior_block( $attributes, $content ) {
	if ( isset( $attributes['metadata']['bindings']['content']['args']['directive'] ) ) {
		add_filter( 'render_block_interactivity/behavior-block', 'block_interactivity_render_behavior_block', 15, 2 );
	}

	return $content;
}

function block_interactivity_render_behavior_block( $content, $block ) {
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
		'interactivity/behavior-block',
		array(
			'nonce'        => wp_create_nonce( 'wp_rest' ),
			'target_field' => $target_field,
			'post_id'      => get_the_ID(),
		),
	);

	$field_value      = esc_html( get_post_meta( get_the_ID(), $target_field, true ) );
	$behavior_context = wp_interactivity_data_wp_context( array( 'field_value' => $field_value ) );

	return <<<HTML
		<div data-wp-interactive="interactivity/behavior-block">
			<div { $behavior_context } data-wp-on-async--{$directive}="actions.{$action}">
				{$content}
			</div>
		</div>
	HTML;
}

// Register the render callback
register_block_type_from_metadata(
	__DIR__,
	array(
		'render_callback' => 'render_behavior_block',
	)
);
