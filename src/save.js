import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

// Default save function
export default function save( { attributes } ) {

	return (
        <div { ...useBlockProps.save() }>
            <InnerBlocks.Content />
        </div>
    )
}
