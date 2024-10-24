/**
 * WordPress dependencies
 */
import { store, getContext, getServerState } from '@wordpress/interactivity';

store( 'interactivity/synced-paragraph', {
	callbacks: {
		pollPostMeta: () => {
			const serverState = getServerState();
			const context = getContext();

			const pollPostMeta = setInterval(() => {
				fetchPostMeta(serverState.post_id, serverState.target_field, serverState.nonce, context);
			}, 1000);

			return () => {
				clearInterval(pollPostMeta);
			};
		},
	},
} );

function fetchPostMeta(postId, targetField, nonce, context) {
	fetch(`/wp-json/wp/v2/posts/${postId}`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce': nonce,
		},
	})
		.then(response => response.json())
		.then(data => {
			context[targetField] = data?.meta?.[targetField];
		})
		.catch(error => console.error('Error fetching post:', error));
}