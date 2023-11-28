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

export default function PatternDisplayEdit( props ) {
	const { attributes, setAttributes } = props;

	const { postUrl, selectButton, copyButton } = attributes;

	const blockProps = useBlockProps( {
		className: `vk-patterns`,
	} );

	let editContent = '';

	// eslint-disable-next-line no-undef
	const homeUrl = VKPatterns.homeUrl;

	if ( postUrl !== undefined && postUrl.indexOf( homeUrl ) !== -1 ) {
		editContent = (
			<ServerSideRender
				block="vk-pattern-directory-creator/patterns"
				attributes={ attributes }
			/>
		);
	} else {
		editContent = (
			<div className="vk_patterns-warning">
				<div className="vk_patterns-warning_text">
					{ __(
						'This block will not be displayed because the url is empty or out of this site.',
						'vk-patterns'
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
													'vk-patterns'
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
										<div className="block-editor-link-control__search-input">
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
											<div className="block-editor-link-control__search-actions">
												<Button
													icon={ keyboardReturn }
													label={ __( 'Submit' ) }
													type="submit"
												/>
											</div>
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
					title={ __( 'Patterns Option', 'vk-patterns' ) }
					initialOpen={ true }
				>
					<BaseControl
						id={ 'vk_patterns-selectButton' }
						label={ __(
							'Display Size Select Pulldown',
							'vk-patterns'
						) }
					>
						<CheckboxControl
							label={ __(
								'Display Size Select Pulldown.',
								'vk-patterns'
							) }
							checked={ selectButton }
							onChange={ ( checked ) =>
								setAttributes( { selectButton: checked } )
							}
						/>
					</BaseControl>
					<BaseControl
						id={ 'vk_patterns-copyButton' }
						label={ __( 'Display Copy Button', 'vk-patterns' ) }
					>
						<CheckboxControl
							label={ __( 'Display Copy Button', 'vk-patterns' ) }
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
