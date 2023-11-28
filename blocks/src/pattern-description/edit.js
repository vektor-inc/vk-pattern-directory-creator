import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { useEffect } from '@wordpress/element';
import { select } from '@wordpress/data';

export default function PatternDescriptionEdit() {

	const blockProps = useBlockProps( {
		className: `vkpdc_pattern-description`,
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks />
		</div>
	);
}
