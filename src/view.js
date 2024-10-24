/**
 * WordPress dependencies
 */
import { store, getServerState } from '@wordpress/interactivity';

const { state } = store( 'interactivity/behaviors-blocks', {
	actions: {
		increment() {
			const serverState = getServerState();
			updateMeta(serverState.post_id, serverState.target_field, parseInt(serverState.field_value) + 1, serverState.nonce);
		},
		decrement() {
			const serverState = getServerState();
			updateMeta(serverState.post_id, serverState.target_field, parseInt(serverState.field_value) - 1, serverState.nonce);
		},
	},
} );

function updateMeta(postId, targetField, newValue, nonce) {
	const jsonData = {
		meta: {
			[targetField]: newValue.toString()
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
		.then(response => response)
		.then(data => console.log('Success:', data))
		.catch(error => console.error('Error:', error));

}