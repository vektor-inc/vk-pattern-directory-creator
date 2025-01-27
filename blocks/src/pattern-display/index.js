import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as Icon } from './icon.svg';
import metadata from './block.json';
import edit from './edit';

const { name } = metadata;

export { metadata, name };

export const settings = {
	icon: <Icon />,
	edit,
};

registerBlockType( metadata, settings );
