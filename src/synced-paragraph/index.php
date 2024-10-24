<?php

function render_synced_paragraph( $attributes, $content ) {
	if ( isset( $attributes['metadata']['bindings']['content'] ) ) {
		add_filter(
			'render_block_interactivity/synced-paragraph',
			'block_interactivity_render_synced_paragraph_block',
			15,
			2
		);
	}

	return '<p>' . esc_html( $content ) . '</p>';
}

function block_interactivity_render_synced_paragraph_block( $content, $block ) {
	$attributes = $block['attrs'];

	if ( ! isset( $attributes['metadata']['bindings']['content'] ) ) {
		return $content;
	}

	$source = $attributes['metadata']['bindings']['content']['source'];
	$args   = $attributes['metadata']['bindings']['content']['args'];
	$key    = isset( $args['key'] ) ? $args['key'] : null;

	$binding_value = 'core/post-meta' === $source ? get_post_meta( get_the_ID(), $key, true ) : null;

	if ( ! $source || ! $key ) {
		return $content;
	}

	wp_interactivity_state(
		'interactivity/synced-paragraph',
		array(
			'nonce'        => wp_create_nonce( 'wp_rest' ),
			'target_field' => $key,
			'post_id'      => get_the_ID(),
		),
	);

	$synced_paragraph_context = wp_interactivity_data_wp_context( array( $key => $binding_value ) );

	return <<<HTML
		<div data-wp-interactive="interactivity/synced-paragraph">
			<p { $synced_paragraph_context } data-wp-watch="callbacks.pollPostMeta">
				<span data-wp-text="context.{$key}">{$binding_value}</span>
			</p>
		</div>
	HTML;
}

// Register the render callback
register_block_type_from_metadata(
	__DIR__,
	array(
		'render_callback' => 'render_synced_paragraph',
	)
);
