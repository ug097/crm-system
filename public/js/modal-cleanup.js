// ページ読み込み時に残っているモーダルバックドロップを削除
document.addEventListener('DOMContentLoaded', function() {
    // モーダルバックドロップを削除
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    
    // bodyからmodal-openクラスを削除
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    // すべてのモーダルを非表示にする
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.classList.remove('show');
        modal.style.display = 'none';
    });
});

// モーダルが閉じられた後にバックドロップをクリーンアップ
document.addEventListener('hidden.bs.modal', function (event) {
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
});
