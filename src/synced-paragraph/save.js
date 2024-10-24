import { useBlockProps } from '@wordpress/block-editor';

// Default save function
export default function save() {

	return (
        <p { ...useBlockProps.save( { className, dir: direction } ) }>
            <RichText.Content value={ content } />
        </p>
    )
}
