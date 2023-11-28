const copyButtonOuterAll = document.querySelectorAll(
	'.vkpdc_button-outer--copy'
);
// eslint-disable-next-line no-undef
const beforeTextSingle = VKPDCButtonCopy.beforeTextSingle;
// eslint-disable-next-line no-undef
const beforeTextArchive = VKPDCButtonCopy.beforeTextArchive;
// eslint-disable-next-line no-undef
const afterText = VKPDCButtonCopy.afterText;
// eslint-disable-next-line no-undef
const copyAjaxUrl = VKPDCButtonCopy.ajaxUrl;

copyButtonOuterAll.forEach( ( copyButtonOuter ) => {
	const post_id = Number( copyButtonOuter.dataset.post );
	const copyButton = copyButtonOuter.querySelector( `.vkpdc_button--copy` );

	copyButton.onclick = () => {
		const req = new XMLHttpRequest(); // eslint-disable-line no-undef
		req.open( 'POST', copyAjaxUrl, true );
		req.setRequestHeader(
			'content-type',
			'application/x-www-form-urlencoded;charset=UTF-8'
		);
		req.send( `action=copy_count&post=${ post_id }` );
		let str = copyButton.getAttribute( 'data-clipboard-text' );
		str = str.replace( /\\\[/g, '[' );
		str = str.replace( /\\\]/g, ']' );
		// eslint-disable-next-line no-undef
		navigator.clipboard.writeText( str ).then(
			// eslint-disable-next-line no-unused-vars
			( success ) => {
				let html = copyButton.innerHTML;
				html = html.replace( beforeTextSingle, afterText );
				html = html.replace( beforeTextArchive, afterText );
				copyButton.innerHTML = html;
			},
			// eslint-disable-next-line no-unused-vars, no-undef, no-alert
			( error ) => alert( 'テキストのコピーに失敗😫' )
		);
	};
} );
