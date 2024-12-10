// import WordPress Scripts
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	BaseControl,
	TextControl,
	SelectControl,
	CheckboxControl,
	RangeControl,
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
		display_image, //eslint-disable-line camelcase
		display_image_overlay_term, //eslint-disable-line camelcase
		display_excerpt, //eslint-disable-line camelcase
		display_author, //eslint-disable-line camelcase
		display_date, //eslint-disable-line camelcase
		display_new, //eslint-disable-line camelcase
		display_taxonomies, //eslint-disable-line camelcase
		display_btn, //eslint-disable-line camelcase
		new_date, //eslint-disable-line camelcase
		new_text, //eslint-disable-line camelcase
		btn_text, //eslint-disable-line camelcase
		btn_align, //eslint-disable-line camelcase
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
				<PanelBody
					title={__('Display item', 'vk-pattern-directory-creator')}
					initialOpen={false}
				>
					<CheckboxControl
						label={__('Image', 'vk-pattern-directory-creator')}
						checked={display_image} //eslint-disable-line camelcase
						onChange={(checked) =>
							setAttributes({ display_image: checked })
						}
					/>
					<CheckboxControl
						label={__('Author', 'vk-pattern-directory-creator')}
						checked={display_author} //eslint-disable-line camelcase
						onChange={(checked) =>
							setAttributes({ display_author: checked })
						}
					/>
					<CheckboxControl
						label={__('Date', 'vk-pattern-directory-creator')}
						checked={display_date} //eslint-disable-line camelcase
						onChange={(checked) => setAttributes({ display_date: checked })}
					/>
					<CheckboxControl
						label={__('New mark', 'vk-pattern-directory-creator')}
						checked={display_new} //eslint-disable-line camelcase
						onChange={(checked) => setAttributes({ display_new: checked })}
					/>
					<CheckboxControl
						label={__('Taxonomies (all)', 'vk-pattern-directory-creator')}
						checked={display_taxonomies} //eslint-disable-line camelcase
						onChange={(checked) =>
							setAttributes({ display_taxonomies: checked })
						}
					/>
					<CheckboxControl
						label={__('Button', 'vk-pattern-directory-creator')}
						checked={display_btn} //eslint-disable-line camelcase
						onChange={(checked) => setAttributes({ display_btn: checked })}
					/>
					<CheckboxControl
						label={__('Button', 'vk-pattern-directory-creator')}
						checked={display_btn} //eslint-disable-line camelcase
						onChange={(checked) => setAttributes({ display_btn: checked })}
					/>
					<h4>{__('New mark option', 'vk-pattern-directory-creator')}</h4>
					<TextControl
						label={__(
							'Number of days to display the new post mark',
							'vk-pattern-directory-creator'
						)}
						value={new_date} //eslint-disable-line camelcase
						onChange={(value) =>
							setAttributes({ new_date: parseInt(value) || 0 })
						}
						type={'number'}
					/>
					<TextControl
						label={__('New post mark', 'vk-pattern-directory-creator')}
						value={new_text} //eslint-disable-line camelcase
						onChange={(value) => setAttributes({ new_text: value })}
					/>
					<h4 className={'postList_itemCard_button-option'}>
						{__('Button option', 'vk-pattern-directory-creator')}
					</h4>
					<p>
						{__(
							"Click each card block to set the target url. You can find the url form at it's sidebar.",
							'vk-pattern-directory-creator'
						)}
					</p>
					<TextControl
						label={__('Button text', 'vk-pattern-directory-creator')}
						value={btn_text} //eslint-disable-line camelcase
						onChange={(value) => setAttributes({ btn_text: value })}
					/>
					<BaseControl
						label={__('Button align', 'vk-pattern-directory-creator')}
						id={'vk_displayItem-buttonAlign'}
					>
						<SelectControl
							value={btn_align} //eslint-disable-line camelcase
							onChange={(value) => setAttributes({ btn_align: value })}
							options={[
								{
									value: 'text-left',
									label: __('Left', 'vk-pattern-directory-creator'),
								},
								{
									value: 'text-center',
									label: __('Center', 'vk-pattern-directory-creator'),
								},
								{
									value: 'text-right',
									label: __('Right', 'vk-pattern-directory-creator'),
								},
							]}
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
