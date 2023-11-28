import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

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
