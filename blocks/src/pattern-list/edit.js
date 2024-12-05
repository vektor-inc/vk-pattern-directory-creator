// import WordPress Scripts
import { __ } from '@wordpress/i18n';
import {
	RangeControl,
	PanelBody,
	BaseControl,
	SelectControl,
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { useEffect } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

export default function PostListEdit( props ) {
	const { attributes, setAttributes } = props;
	const {
		numberPosts,
		order,
		orderby,
		colWidthMin,
		colWidthMinTablet,
		colWidthMinPC,
		gap,
		gapRow,
	} = attributes;

	const blockProps = useBlockProps();

	useEffect(() => {
		if (!colWidthMin) setAttributes({ colWidthMin: '300px' });
		if (!colWidthMinTablet) setAttributes({ colWidthMinTablet: '300px' });
		if (!colWidthMinPC) setAttributes({ colWidthMinPC: '300px' });
		if (!gap) setAttributes({ gap: '30px' });
		if (!gapRow) setAttributes({ gapRow: '30px' });
	}, []);

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Display conditions', 'vk-pattern-directory-creator' ) }
					initialOpen={ false }
				>
					<BaseControl
						label={ __( 'Number of Posts', 'vk-pattern-directory-creator' ) }
						id={ `vk_postList-numberPosts` }
					>
						<RangeControl
							value={ numberPosts }
							onChange={ ( value ) =>
								setAttributes( { numberPosts: value } )
							}
							min="1"
							max="100"
						/>
					</BaseControl>
					<BaseControl
						label={ __( 'Order', 'vk-pattern-directory-creator' ) }	
						id={ `vk_postList-order` }
					>
						<SelectControl
							value={ order }
							onChange={ ( v ) => setAttributes( { order: v } ) }
							options={ [
								{
									value: 'ASC',
									label: __( 'ASC', 'vk-pattern-directory-creator' ),
								},
								{
									value: 'DESC',
									label: __( 'DESC', 'vk-pattern-directory-creator' ),
								},
							] }
						/>
					</BaseControl>
					<BaseControl
						label={ __( 'Order by', 'vk-pattern-directory-creator' ) }
						id={ `vk_postList-orderBy` }
					>
						<SelectControl
							value={ orderby }
							onChange={ ( v ) =>
								setAttributes( { orderby: v } )
							}
							options={ [
								{
									value: 'date',
									label: __(
										'Published Date',
										'vk-pattern-directory-creator'
									),
								},
								{
									value: 'modified',
									label: __( 'Modefied Date', 'vk-pattern-directory-creator' ),
								},
								{
									value: 'title',
									label: __( 'Title', 'vk-pattern-directory-creator' ),
								},
								{
									value: 'rand',
									label: __( 'Random', 'vk-pattern-directory-creator' ),
								},
							] }
						/>
					</BaseControl>
				</PanelBody>
				<PanelBody title={__('Column Width Setting', 'vk-pattern-directory-creator')}>
					<UnitControl
						label={__('Column min width (Mobile)', 'vk-pattern-directory-creator')}
						value={colWidthMin}
						onChange={(value) => setAttributes({ colWidthMin: value })}
					/>
					<UnitControl
						label={__('Column min width (Tablet)', 'vk-pattern-directory-creator')}
						value={colWidthMinTablet}
						onChange={(value) => setAttributes({ colWidthMinTablet: value })}
					/>
					<UnitControl
						label={__('Column min width (PC)', 'vk-pattern-directory-creator')}
						value={colWidthMinPC}
						onChange={(value) => setAttributes({ colWidthMinPC: value })}
					/>
				</PanelBody>
				<PanelBody title={__('Column Gap Setting', 'vk-pattern-directory-creator')}>
					<UnitControl
						label={__('Column gap size', 'vk-pattern-directory-creator')}
						value={gap}
						onChange={(value) => setAttributes({ gap: value })}
					/>
					<UnitControl
						label={__('Row gap size', 'vk-pattern-directory-creator')}
						value={gapRow}
						onChange={(value) => setAttributes({ gapRow: value })}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<ServerSideRender
					block="vkpdc/pattern-list"
					attributes={attributes}
				/>
			</div>
		</>
	);
}
