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
    const { postUrl, displayPulldowns, displayButtons } = attributes;

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
    } else if ( !postUrl ) {
        editContent = (
            <div className="vkpdc_warning">
                <div className="vkpdc_warning_text">
                    { __(
                        'Please enter an internal URL to display the pattern. This block will not display any content until a valid URL is provided.',
                        'vk-pattern-directory-creator'
                    ) }
                </div>
            </div>
        );
    } else {
        editContent = (
            <div className="vkpdc_warning">
                <div className="vkpdc_warning_text">
                    { __(
                        'This block will not be displayed because the URL is empty or out of this site.',
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
                        id={ 'vkpdc_displayPulldowns' }
                        label={ __(
                            'Display Pulldowns',
                            'vk-pattern-directory-creator'
                        ) }
                    >
                        <CheckboxControl
                            label={ __(
                                'Display Pulldowns.',
                                'vk-pattern-directory-creator'
                            ) }
                            checked={ displayPulldowns }
                            onChange={ ( checked ) =>
                                setAttributes( { displayPulldowns: checked } )
                            }
                        />
                    </BaseControl>
                    <BaseControl
                        id={ 'vkpdc_displayButtons' }
                        label={ __( 'Display Buttons', 'vk-pattern-directory-creator' ) }
                    >
                        <CheckboxControl
                            label={ __( 'Display Buttons', 'vk-pattern-directory-creator' ) }
                            checked={ displayButtons }
                            onChange={ ( checked ) =>
                                setAttributes( { displayButtons: checked } )
                            }
                        />
                    </BaseControl>
                </PanelBody>
            </InspectorControls>
            <div { ...blockProps }>{ editContent }</div>
        </>
    );
}
