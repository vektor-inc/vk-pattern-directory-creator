const SelectSizeAll = document.querySelectorAll('.vkpdc_select--size');
const SizeList = vkPatternsSizeSelect.sizeList;
let WindowSize = document.body.clientWidth;
const iframeContainer = document.querySelector('.vkpdc_iframe-wrapper');

function updateSizeOptionsAndIframe(selectSizeElement) {
  const sizeOption = selectSizeElement.querySelectorAll('option');
  let selectFlag = false;

  SizeList.forEach((size) => {
    sizeOption.forEach((option) => {
      if (size.value === option.value) {
        if (parseInt(size.value) > WindowSize) {
          option.style.display = 'none';
        } else {
          option.style.display = 'block';
          if (!selectFlag) {
            iframeContainer.style.width = (parseInt(option.value) + 2) + 'px';
            selectFlag = true;
          }
        }
      }
    });
  });
}

SelectSizeAll.forEach((selectSize) => {
  // 初期設定としてオプションとiframeの幅を更新
  updateSizeOptionsAndIframe(selectSize);

  // セレクトボックス変更時のイベントリスナー
  selectSize.onchange = () => {
    iframeContainer.style.width = (parseInt(selectSize.value) + 2) + 'px';
  };
});

// ウィンドウリサイズ時のイベントリスナー
window.addEventListener('resize', () => {
  WindowSize = document.body.clientWidth;
  SelectSizeAll.forEach(updateSizeOptionsAndIframe);
});
