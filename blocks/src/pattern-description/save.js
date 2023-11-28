import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function PatternDescriptionSave() {
	const blockProps = useBlockProps.save( {
		className: `vk-patterns_pattern-description`,
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks.Content />
		</div>
	);
}
