// import WordPress Scripts
import { __ } from '@wordpress/i18n';
import {
	RangeControl,
	PanelBody,
	BaseControl,
	SelectControl,
	CheckboxControl,
	RangeControl,
	ColorPalette,
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

export default function PostListEdit( props ) {
	const { attributes, setAttributes } = props;
	const {
		numberPosts,
		order,
		orderby,
		display_image, //eslint-disable-line camelcase
		display_author, //eslint-disable-line camelcase
		display_date_publiched, //eslint-disable-line camelcase
		display_date_modified, //eslint-disable-line camelcase
		display_new, //eslint-disable-line camelcase
		display_taxonomies, //eslint-disable-line camelcase
		pattern_id, //eslint-disable-line camelcase
		display_btn_view, //eslint-disable-line camelcase
		display_btn_view_text, //eslint-disable-line camelcase
		display_btn_copy, //eslint-disable-line camelcase
		new_date, //eslint-disable-line camelcase
		new_text, //eslint-disable-line camelcase
		thumbnail_size, //eslint-disable-line camelcase
		colWidthMin,
		colWidthMinTablet,
		colWidthMinPC,
		gap,
		gapRow,
		postBackgroundColor,
		postTextColor,
		buttonBackgroundColor,
		buttonTextColor,
	} = attributes;
	const { numberPosts, order, orderby } = attributes;

	const blockProps = useBlockProps();

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
					title={__('Display Items', 'vk-pattern-directory-creator')}
					initialOpen={true}
				>
					<CheckboxControl
						label={__('New Mark', 'vk-pattern-directory-creator')}
						checked={display_new}
						onChange={(checked) => setAttributes({ display_new: checked })}
					/>
					<CheckboxControl
						label={__('Taxonomies(all)', 'vk-pattern-directory-creator')}
						checked={display_taxonomies}
						onChange={(checked) => setAttributes({ display_taxonomies: checked })}
					/>
					<CheckboxControl
						label={__('Pattern ID', 'vk-pattern-directory-creator')}
						checked={pattern_id}
						onChange={(checked) => setAttributes({ pattern_id: checked })}
					/>
					<CheckboxControl
						label={__('Published Date', 'vk-pattern-directory-creator')}
						checked={display_date_publiched}
						onChange={(checked) => setAttributes({ display_date_publiched: checked })}
					/>
					<CheckboxControl
						label={__('Modified Date', 'vk-pattern-directory-creator')}
						checked={display_date_modified}
						onChange={(checked) => setAttributes({ display_date_modified: checked })}
					/>
					<CheckboxControl
						label={__('Author', 'vk-pattern-directory-creator')}
						checked={display_author}
						onChange={(checked) => setAttributes({ display_author: checked })}
					/>
					<CheckboxControl
						label={__('View Button', 'vk-pattern-directory-creator')}
						checked={display_btn_view}
						onChange={(checked) => setAttributes({ display_btn_view: checked })}
					/>
					<CheckboxControl
						label={__('Copy Button', 'vk-pattern-directory-creator')}
						checked={display_btn_copy}
						onChange={(checked) => setAttributes({ display_btn_copy: checked })}
						/>
					<h4>{__('Image option', 'vk-pattern-directory-creator')}</h4>
					<SelectControl
						label={__('Display Image Option', 'vk-pattern-directory-creator')}
						value={display_image}
						options={[
							{ label: __('None', 'vk-pattern-directory-creator'), value: '' },
							{ label: __('Use Featured Image', 'vk-pattern-directory-creator'), value: 'featured' },
							{ label: __('Use iframe Only', 'vk-pattern-directory-creator'), value: 'iframe' },
						]}
						onChange={(value) => setAttributes({ display_image: value })}
						defaultValue="featured"
					/>
					{display_image === 'featured' && (
						<BaseControl
							label={__('Thumbnail Size', 'vk-pattern-directory-creator')}
							id={`vk_postList-thumbnail_size`}
						>
							<SelectControl
								value={thumbnail_size}
								onChange={(v) => setAttributes({ thumbnail_size: v })}
								options={[
									{ value: 'thumbnail', label: __('Thumbnail', 'vk-pattern-directory-creator') },
									{ value: 'medium', label: __('Medium', 'vk-pattern-directory-creator') },
									{ value: 'large', label: __('Large', 'vk-pattern-directory-creator') },
									{ value: 'full', label: __('Full', 'vk-pattern-directory-creator') },
								]}
							/>
						</BaseControl>
					)}					<h4>{__('New mark option', 'vk-pattern-directory-creator')}</h4>
					<TextControl
						label={__(
							'Number of days to display the new post mark',
							'vk-pattern-directory-creator'
						)}
						value={new_date} //eslint-disable-line camelcase
						onChange={(value) =>
							setAttributes({ new_date: parseInt(value) })
						}
						type={'number'}
					/>
					<TextControl
						label={__('New post mark', 'vk-pattern-directory-creator')}
						value={new_text} //eslint-disable-line camelcase
						onChange={(value) => setAttributes({ new_text: value })}
					/>
					<h4>{__('View Button Text', 'vk-pattern-directory-creator')}</h4>
					<TextControl
						label={__('View Button Text', 'vk-pattern-directory-creator')}
						value={display_btn_view_text}
						onChange={(value) => setAttributes({ display_btn_view_text: value })}
					/>
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
				<PanelBody title={__('Color Settings', 'vk-pattern-directory-creator')}>
					<BaseControl
						label={__('Post Background Color', 'vk-pattern-directory-creator')}
					>
						<ColorPalette
							value={postBackgroundColor}
							onChange={(value) => setAttributes({ postBackgroundColor: value })}
							disableAlpha
						/>
					</BaseControl>
					<BaseControl
						label={__('Post Text Color', 'vk-pattern-directory-creator')}
					>
						<ColorPalette
							value={postTextColor}
							onChange={(value) => setAttributes({ postTextColor: value })}
							disableAlpha
						/>
					</BaseControl>
					<BaseControl
						label={__('Button Background Color', 'vk-pattern-directory-creator')}
					>
						<ColorPalette
							value={buttonBackgroundColor}
							onChange={(value) => setAttributes({ buttonBackgroundColor: value })}
							disableAlpha
						/>
					</BaseControl>
					<BaseControl
						label={__('Button Text Color', 'vk-pattern-directory-creator')}
					>
						<ColorPalette
							value={buttonTextColor}
							onChange={(value) => setAttributes({ buttonTextColor: value })}
							disableAlpha
						/>
					</BaseControl>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ServerSideRender
					block="vkpdc/pattern-list"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
