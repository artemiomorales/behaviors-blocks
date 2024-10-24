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
 * @package           interactivity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// include index.php for both behavior and synced-paragraph
require_once __DIR__ . '/build/behavior/index.php';
require_once __DIR__ . '/build/synced-paragraph/index.php';

function register_test_fields() {

	unregister_post_meta(
		'post',
		'like_count',
	);

	register_meta(
		'post',
		'like_count',
		array(
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'integer',
			'default'      => 0,
			'label'        => __( 'Like Count' ),
		)
	);
}
add_action( 'init', 'register_test_fields' );
