const copyButtonOuterAll = document.querySelectorAll('.vk-patterns-copy');
// eslint-disable-next-line no-undef
const beforeTextSingle = vkPatternsCopyButton.beforeTextSingle;
// eslint-disable-next-line no-undef
const beforeTextArchive = vkPatternsCopyButton.beforeTextArchive;
// eslint-disable-next-line no-undef
const afterText = vkPatternsCopyButton.afterText;
// eslint-disable-next-line no-undef
const copyAjaxUrl = vkPatternsCopyButton.ajaxUrl;

copyButtonOuterAll.forEach((copyButtonOuter) => {
	const post_id = Number(copyButtonOuter.dataset.post);
	const copyButton = copyButtonOuter.querySelector(`.vk-patterns-copy-button`);
	
	copyButton.onclick = () => {
		const req = new XMLHttpRequest();
		req.open('POST', copyAjaxUrl, true);
		req.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
		req.send(`action=copy_count&post=${post_id}`);
		let str = copyButton.getAttribute('data-clipboard-text');
		str = str.replace(/\\\[/g, '[');
		str = str.replace(/\\\]/g, ']');
		navigator.clipboard.writeText(str)
        .then(
            success => {
				let str = copyButton.innerHTML;
				str = str.replace(beforeTextSingle, afterText);
				str = str.replace(beforeTextArchive, afterText);
				copyButton.innerHTML = str;
			},
            error => alert('テキストのコピーに失敗😫')
        );
	};
});
