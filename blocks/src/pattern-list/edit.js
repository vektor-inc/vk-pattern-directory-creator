// import WordPress Scripts
import { __ } from '@wordpress/i18n';
import {
	RangeControl,
	PanelBody,
	BaseControl,
	SelectControl,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

export default function PostListEdit( props ) {
	const { attributes, setAttributes } = props;
	const { numberPosts, order, orderby } = attributes;

	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Display conditions', 'vk-patterns' ) }
					initialOpen={ false }
				>
					<BaseControl
						label={ __( 'Number of Posts', 'vk-patterns' ) }
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
						label={ __( 'Order', 'vk-patterns' ) }
						id={ `vk_postList-order` }
					>
						<SelectControl
							value={ order }
							onChange={ ( v ) => setAttributes( { order: v } ) }
							options={ [
								{
									value: 'ASC',
									label: __( 'ASC', 'vk-patterns' ),
								},
								{
									value: 'DESC',
									label: __( 'DESC', 'vk-patterns' ),
								},
							] }
						/>
					</BaseControl>
					<BaseControl
						label={ __( 'Order by', 'vk-patterns' ) }
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
										'vk-patterns'
									),
								},
								{
									value: 'modified',
									label: __( 'Modefied Date', 'vk-patterns' ),
								},
								{
									value: 'title',
									label: __( 'Title', 'vk-patterns' ),
								},
								{
									value: 'rand',
									label: __( 'Random', 'vk-patterns' ),
								},
							] }
						/>
					</BaseControl>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ServerSideRender
					block="vk-pattern-directory-creator/pattern-list"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
