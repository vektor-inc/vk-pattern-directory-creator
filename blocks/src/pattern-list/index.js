/**
 * post-list block type
 *
 */

import { registerBlockType } from '@wordpress/blocks';
// import React
import { ReactComponent as Icon } from './icon.svg';

// import block files
import metadata from './block.json';
import edit from './edit';

const { name } = metadata;

export { metadata, name };

export const settings = {
	icon: <Icon />,
	example: {
		attributes: {
			numberPosts: 6,
			order: 'DESC',
			orderby: 'date',
			colWidthMin: '300px',
			colWidthMinTablet: '300px',
			colWidthMinPC: '300px',
			gap: '30px',
			gapRow: '30px',
		},
	},
	edit,
};

registerBlockType( metadata, settings );
