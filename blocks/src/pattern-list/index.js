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
			display_author: true,
			display_date_publiched: true,
			display_date_modified: false,
			display_new: true,
			display_taxonomies: true,
			excluded_taxonomies: [],
			pattern_id: true,
			display_btn_view: true,
			display_btn_copy: true,
			display_paged: false,
			display_image: 'featured',
			thumbnail_size: 'large',
			new_date: 7,
			new_text: 'NEW!!',
			display_btn_view_text: 'Read More',
			colWidthMinMobile: '300px',
			colWidthMinTablet: '300px',
			colWidthMinPC: '300px',
			gap: '1.5rem',
			gapRow: '1.5rem',
			className: 'custom-class',
		},		
	},
	edit,
};

registerBlockType( metadata, settings );
