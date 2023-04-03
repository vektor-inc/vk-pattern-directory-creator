((window, document) => {
	/*-------------------------------------------*/
	/*  iframeのレスポンシブ対応
    /*-------------------------------------------*/
	function iframe_responsive() {
		Array.prototype.forEach.call(
			document.getElementsByTagName('iframe'),
			(i) => {
				let iframeUrl = i.getAttribute('src');
				if (!iframeUrl) {
					return;
				}
				// iframeのURLの中に youtube か map が存在する位置を検索する
				// 見つからなかった場合には -1 が返される
				if (
					iframeUrl.indexOf('youtube') >= 0 ||
					iframeUrl.indexOf('vimeo') >= 0 ||
					iframeUrl.indexOf('maps') >= 0
				) {
					var iframeWidth = i.getAttribute('width');
					var iframeHeight = i.getAttribute('height');
					var iframeRate = iframeHeight / iframeWidth;
					var nowIframeWidth = i.offsetWidth;
					var newIframeHeight = nowIframeWidth * iframeRate;
					i.style.maxWidth = '100%';
					i.style.height = newIframeHeight + 'px';
				}
			}
		);
	}

	window.addEventListener('DOMContentLoaded', iframe_responsive);
	let timer = false;
	window.addEventListener('resize', () => {
		if (timer) clearTimeout(timer);
		timer = setTimeout(iframe_responsive, 200);
	});
})(window, document);
