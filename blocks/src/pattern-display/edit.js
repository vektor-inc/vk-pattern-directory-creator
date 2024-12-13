import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import {
	PanelBody,
	BaseControl,
	CheckboxControl,
	ToolbarGroup,
	ToolbarButton,
	Dropdown,
	Button,
} from '@wordpress/components';
import {
	InspectorControls,
	useBlockProps,
	BlockControls,
	URLInput,
} from '@wordpress/block-editor';
import { link, linkOff, keyboardReturn } from '@wordpress/icons';
import { useState, useEffect } from 'react';

export default function PatternDisplayEdit( props ) {
	const { attributes, setAttributes } = props;
	const {
		postUrl, 
		selectButton,
		copyButton,
	} = attributes;

	useEffect(() => {
		// 初回レンダリング時にデフォルト値を設定
		if (attributes.selectButton === undefined) {
			setAttributes({ selectButton: true });
		}
		if (attributes.copyButton === undefined) {
			setAttributes({ copyButton: true });
		}
	}, []);

	const blockProps = useBlockProps( {
		className: `vkpdc vkpdc_pattern-display`,
	} );

	let editContent = '';

	// eslint-disable-next-line no-undef
	const homeUrl = vkpdcPatternDisplay.homeUrl;

	if ( postUrl !== undefined && postUrl.indexOf( homeUrl ) !== -1 ) {
		editContent = (
			<ServerSideRender
				block="vkpdc/pattern-display"
				attributes={ attributes }
			/>
		);
	} else {
		editContent = (
			<div className="vkpdc_warning">
				<div className="vkpdc_warning_text">
					{ __(
						'Please set a valid internal post URL.This block will not be displayed because the url is empty or out of this site.',
						'vk-pattern-directory-creator'
					) }
				</div>
			</div>
		);
	}

	return (
		<>
			<BlockControls>
				<ToolbarGroup>
					<Dropdown
						renderToggle={ ( { isOpen, onToggle } ) => {
							const setLink = () => {
								if ( isOpen && postUrl !== '' ) {
									// linkOff
									setAttributes( { postUrl: '' } );
								}
								onToggle();
							};
							return (
								<ToolbarButton
									aria-expanded={ isOpen }
									icon={
										postUrl !== '' && isOpen
											? linkOff
											: link
									}
									isActive={
										postUrl !== '' && isOpen ? true : false
									}
									label={
										postUrl !== '' && isOpen
											? __( 'Unlink' )
											: __(
												  'Input Internal Post URL',
												  'vk-pattern-directory-creator'
											  )
									}
									onClick={ setLink }
								/>
							);
						} }
						renderContent={ ( params ) => {
							return (
								<div className="block-editor-postUrl-input__button block-editor-link-control">
									<form
										className="block-editor-link-control__search-input-wrapper"
										onSubmit={ () => {
											if (
												postUrl.indexOf( homeUrl ) ===
												-1
											) {
												setAttributes( {
													postUrl: '',
												} );
											}
											params.onClose();
										} }
									>
										<div className="block-editor-link-control__search-input" style={{ display: 'flex', alignItems: 'center' }}>
											<URLInput
												value={ postUrl }
												onChange={ ( v, post ) => {
													setAttributes( {
														postUrl: v,
													} );
													if ( post && post.title ) {
														// select post
														params.onClose();
													}
												} }
											/>
											<Button
												icon={ keyboardReturn }
												label={ __( 'Submit' ) }
												type="submit"
											/>
										</div>
									</form>
								</div>
							);
						} }
					/>
				</ToolbarGroup>
			</BlockControls>
			<InspectorControls>
				<PanelBody
					title={ __( 'Pattern Display Option', 'vk-pattern-directory-creator' ) }
					initialOpen={ true }
				>
					<BaseControl
						id={ 'vkpdc_selectButton' }
						label={ __( 'Display Pulldowns', 'vk-pattern-directory-creator' ) }
					>
						<CheckboxControl
							label={ __( 'Display Pulldowns', 'vk-pattern-directory-creator' ) }
							checked={ selectButton }
							onChange={ ( checked ) =>
								setAttributes( { selectButton: checked } )
							}
						/>
					</BaseControl>
					<BaseControl
						id={ 'vkpdc_copyButton' }
						label={ __( 'Display Buttons', 'vk-pattern-directory-creator' ) }
					>
						<CheckboxControl
							label={ __( 'Display Buttons', 'vk-pattern-directory-creator' ) }
							checked={ copyButton }
							onChange={ ( checked ) =>
								setAttributes( { copyButton: checked } )
							}
						/>
					</BaseControl>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>{ editContent }</div>
		</>
	);
}
