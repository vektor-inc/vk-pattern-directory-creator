const SelectSizeAll = document.querySelectorAll('.vk-patterns-size-select');
// eslint-disable-next-line no-undef
const SizeList = vkPatternsSizeSelect.sizeList;
const WindowSize = document.body.clientWidth;
const iframeContainer = document.querySelector('.vk-patterns-iframe-wrapper');

SelectSizeAll.forEach((SelectSize) => {
	const sizeOption = SelectSize.querySelectorAll('option');

	// 要素の非表示と value の切り替え
	let selectflag = false;
	SizeList.forEach((size) => {
		sizeOption.forEach((option) => {
			if (size.value === option.value) {
				if (parseInt(size.value) > parseInt(WindowSize)) {
					option.style.display = 'none';
				} else {
					option.style.display = 'block';
					if (selectflag === false) {
						option.selected = true;
						iframeContainer.style.width = ( parseInt(option.value) + 2) + 'px';
						selectflag = true;
					}
				}
			}
		});
	});

	// iframe-wrapper の幅を切り替え
	SelectSize.onchange = () => {
		iframeContainer.style.width =  ( parseInt(SelectSize.value) + 2) + 'px';
	};
});
