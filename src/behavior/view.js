/**
 * WordPress dependencies
 */
import { store, getServerState, getContext } from '@wordpress/interactivity';

store( 'interactivity/behavior-block', {
	actions: {
		increment() {
			const serverState = getServerState();
			const { post_id, target_field, nonce } = serverState;
			const context = getContext();
			const { field_value } = context;
			updateMeta(post_id, target_field, parseInt(field_value) + 1, nonce, context);
		},
	},
} );

function updateMeta(postId, targetField, newValue, nonce, context) {
	const jsonData = {
		meta: {
			[targetField]: newValue
		}
	};

	fetch(`/wp-json/wp/v2/posts/${postId}`, {
		method: 'POST', // Use POST or PUT depending on your needs
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce': nonce, // Include the nonce for authentication
		},
		body: JSON.stringify(jsonData),
	})
		.then(response => {
			return response.json();
		})
		.then(data => {
			context.field_value = data?.meta?.[targetField];
		})
		.catch(error => console.error('Error:', error));

}