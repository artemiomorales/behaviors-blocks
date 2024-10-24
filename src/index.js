/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType, registerBlockBindingsSource } from '@wordpress/blocks';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor. All other files
 * get applied to the editor only.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';
import './editor.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import Save from './save';
import metadata from './block.json';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	edit: Edit,
	save: Save,
} );

// registerBlockBindingsSource( {
//     name: 'interactivity/behaviors-options',
//     usesContext: [ 'postType' ],	
//     // getValues( { select, bindings } ) {
//     //     const values = {};
//     //     for ( const [ attributeName, source ] of Object.entries( bindings ) ) {
//     //         if ( allowedAttributes.includes( source.args.key ) ) {
//     //             values[ attributeName ] = select( coreEditorStore ).getEditedPostAttribute( source.args.key );
//     //         }
//     //     }
//     //     return values;
//     // },
//     // setValues( { dispatch, bindings } ) {
//     //     const newValues = {};
//     //     for ( const [ attributeName, source ] of Object.entries( bindings ) ) {
//     //         if ( allowedAttributes.includes( source.args.key ) ) {
//     //             newValues[ source.args.key ] = source.newValue;
//     //         }
//     //     }
//     //     if ( Object.keys( newValues ).length > 0 ) {
//     //         dispatch( coreEditorStore ).editPost( newValues );
//     //     }
//     // },
//     getFieldsList( { context } ) {
//         return [
//             { key: 'on-click', label: 'On Click' },
// 			{ key: 'on-mouse-wheel', label: 'On Mouse Wheel' },
//         ];
//     },
// } );