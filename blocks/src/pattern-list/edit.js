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
import { useEffect, useState } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import apiFetch from '@wordpress/api-fetch';

export default function PostListEdit( props ) {
	const { attributes, setAttributes } = props;
	const {
		numberPosts,
		order,
		orderby,
		display_author,
		display_date_publiched,
		display_date_modified,
		display_new,
		display_taxonomies,
		excluded_taxonomies,
		pattern_id,
		display_btn_view,
		display_btn_view_text,
		display_btn_copy,
		display_paged,
		display_image,
		thumbnail_size,
		new_date,
		new_text,
		colWidthMinMobile,
		colWidthMinTablet,
		colWidthMinPC,
		gap,
		gapRow,
	} = attributes;

	const blockProps = useBlockProps();
	const [taxonomies, setTaxonomies] = useState([]);

	useEffect(() => {
		if (display_new === undefined) setAttributes({ display_new: true });
		if (display_taxonomies === undefined) setAttributes({ display_taxonomies: true });
		if (pattern_id === undefined) setAttributes({ pattern_id: true });
		if (display_date_publiched === undefined) setAttributes({ display_date_publiched: true });
		if (display_date_modified === undefined) setAttributes({ display_date_modified: true });
		if (display_author === undefined) setAttributes({ display_author: true });
		if (display_btn_view === undefined) setAttributes({ display_btn_view: true });
		if (!display_btn_view_text) setAttributes({ display_btn_view_text: __('View', 'vk-pattern-directory-creator') });
		if (display_btn_copy === undefined) setAttributes({ display_btn_copy: true });
		if (display_paged === undefined) setAttributes({ display_paged: false });
		if (display_image === undefined) setAttributes({ display_image: __('featured', 'vk-pattern-directory-creator') });
		if (!thumbnail_size) setAttributes({ thumbnail_size: 'full' });
		if (new_date === undefined || isNaN(new_date)) setAttributes({ new_date: 7 });
		if (new_text === undefined) setAttributes({ new_text: 'NEW!!' });
		if (!colWidthMinMobile) setAttributes({ colWidthMinMobile: '300px' });
		if (!colWidthMinTablet) setAttributes({ colWidthMinTablet: '300px' });
		if (!colWidthMinPC) setAttributes({ colWidthMinPC: '300px' });
		if (!gap) setAttributes({ gap: '1.5rem' });
		if (!gapRow) setAttributes({ gapRow: '1.5rem' });
		if (!excluded_taxonomies) setAttributes({ excluded_taxonomies: [] });

		// Fetch taxonomies for vk-patterns
		apiFetch({ path: '/wp/v2/taxonomies?type=vk-patterns' }).then((data) => {
			const taxonomyList = Object.keys(data).map((key) => ({
				slug: key,
				label: data[key].name,
			}));
			setTaxonomies(taxonomyList);
		});
	}, []);

	useEffect(() => {
		// すべてのタクソノミーが除外された場合、display_taxonomies のチェックを外す
		if (excluded_taxonomies.length === taxonomies.length) {
			setAttributes({ display_taxonomies: false });
		}
	}, [excluded_taxonomies, taxonomies]);

	const handleTaxonomyChange = (taxonomySlug) => {
		const newExclusions = excluded_taxonomies.includes(taxonomySlug)
			? excluded_taxonomies.filter((item) => item !== taxonomySlug)
			: [...excluded_taxonomies, taxonomySlug];
		setAttributes({ excluded_taxonomies: newExclusions });
	};

	const handleDisplayTaxonomiesChange = (checked) => {
		setAttributes({ display_taxonomies: checked });
		if (checked) {
			// チェックがつけられたとき、すべてのタクソノミーを表示
			setAttributes({ excluded_taxonomies: [] });
		} else {
			// チェックが外されたとき、すべてのタクソノミーを除外
			setAttributes({ excluded_taxonomies: taxonomies.map(t => t.slug) });
		}
	};

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
						onChange={handleDisplayTaxonomiesChange}
					/>
					<div style={{ paddingLeft: '1.5rem', marginBottom: '16px' }}>
						{display_taxonomies && taxonomies.map((taxonomy) => (
							<CheckboxControl
								key={taxonomy.slug}
								label={taxonomy.label}
								checked={!excluded_taxonomies.includes(taxonomy.slug)}
								onChange={() => handleTaxonomyChange(taxonomy.slug)}
							/>
						))}
					</div>
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
					<CheckboxControl
						label={__('Pagenation', 'vk-pattern-directory-creator')}
						checked={display_paged}
						onChange={(checked) => setAttributes({ display_paged: checked })}
					/>
					<h4>{__('Image option', 'vk-pattern-directory-creator')}</h4>
					<SelectControl
						label={__('Display Image Option', 'vk-pattern-directory-creator')}
						value={display_image}
						options={[
							{ label: __('None', 'vk-pattern-directory-creator'), value: '' },
							{ label: __('Prioritize Featured Image', 'vk-pattern-directory-creator'), value: 'featured' },
							{ label: __('Iframe Only', 'vk-pattern-directory-creator'), value: 'iframe' },
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
						value={new_date}
						onChange={(value) =>
							setAttributes({ new_date: parseInt(value) })
						}
						type={'number'}
					/>
					<TextControl
						label={__('New post mark', 'vk-pattern-directory-creator')}
						value={new_text}
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
						value={colWidthMinMobile}
						onChange={(value) => setAttributes({ colWidthMinMobile: value })}
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
