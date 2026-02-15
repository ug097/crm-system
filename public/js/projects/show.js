// モーダルインスタンスを保持する変数
let timeEntryModal, deleteModal;

// モーダルインスタンスを取得または作成する関数
function getTimeEntryModal() {
    if (!timeEntryModal) {
        const modalElement = document.getElementById('timeEntryModal');
        if (modalElement) {
            timeEntryModal = bootstrap.Modal.getOrCreateInstance(modalElement);
        }
    }
    return timeEntryModal;
}

function getDeleteModal() {
    if (!deleteModal) {
        const modalElement = document.getElementById('deleteConfirmModal');
        if (modalElement) {
            deleteModal = bootstrap.Modal.getOrCreateInstance(modalElement);
        }
    }
    return deleteModal;
}

document.addEventListener('DOMContentLoaded', function() {
    // データ属性から値を取得
    const storeUrlElement = document.getElementById('timeEntryStoreUrl');
    const currentUserIdElement = document.getElementById('currentUserId');
    
    if (!storeUrlElement || !currentUserIdElement) {
        console.error('Required data attributes not found');
        return;
    }
    
    const storeUrl = storeUrlElement.dataset.url;
    const currentUserId = parseInt(currentUserIdElement.dataset.userId);
    const todayDate = currentUserIdElement.dataset.today || new Date().toISOString().split('T')[0];
    
    // モーダルインスタンスを初期化
    getTimeEntryModal();
    getDeleteModal();
    
    // 工数記録カードをクリックしたとき（編集モード）
    document.querySelectorAll('.time-entry-card').forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const entryId = this.dataset.entryId;
            const entryUserId = parseInt(this.dataset.entryUserId);
            const entryDate = this.dataset.entryDate;
            const entryHours = this.dataset.entryHours;
            const entryDescription = this.dataset.entryDescription || '';
            const entryTaskId = this.dataset.entryTaskId || '';
            
            openEditTimeEntryModal(entryId, entryUserId, entryDate, entryHours, entryDescription, entryTaskId, currentUserId);
        });
    });
    
    // 削除ボタンをクリックしたとき
    const deleteBtn = document.getElementById('deleteTimeEntryBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            const modal = getTimeEntryModal();
            const delModal = getDeleteModal();
            if (modal) modal.hide();
            if (delModal) delModal.show();
        });
    }
    
    // グローバル関数として公開（onclick属性から呼び出せるように）
    window.openNewTimeEntryModal = function() {
        openNewTimeEntryModal(storeUrl, todayDate);
    };
});

// 新規登録モーダルを開く
function openNewTimeEntryModal(storeUrl, todayDate) {
    const modal = getTimeEntryModal();
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // モーダルタイトルとボタンテキストを変更
    const titleText = document.getElementById('modalTitleText');
    const submitText = document.getElementById('submitButtonText');
    if (titleText) titleText.textContent = '工数記録の新規登録';
    if (submitText) submitText.textContent = '記録';
    
    // フォームを新規登録モードに設定
    const form = document.getElementById('timeEntryForm');
    if (form) form.action = storeUrl;
    // 新規登録時は_methodフィールドを削除
    const methodInput = document.getElementById('formMethod');
    if (methodInput) {
        methodInput.remove();
    }
    
    // フォームをリセット
    const dateInput = document.getElementById('modal_date');
    const hoursInput = document.getElementById('modal_hours');
    const descriptionInput = document.getElementById('modal_description');
    const taskIdSelect = document.getElementById('modal_task_id');
    
    if (dateInput) dateInput.value = todayDate;
    if (hoursInput) hoursInput.value = '';
    if (descriptionInput) descriptionInput.value = '';
    if (taskIdSelect) taskIdSelect.value = '';
    
    // フォームを有効化
    if (dateInput) dateInput.disabled = false;
    if (hoursInput) hoursInput.disabled = false;
    if (descriptionInput) descriptionInput.disabled = false;
    if (taskIdSelect) taskIdSelect.disabled = false;
    
    // 編集・削除ボタンを非表示
    const deleteBtn = document.getElementById('deleteTimeEntryBtn');
    const permissionMsg = document.getElementById('editPermissionMessage');
    const submitBtn = document.getElementById('submitTimeEntryBtn');
    if (deleteBtn) deleteBtn.style.display = 'none';
    if (permissionMsg) permissionMsg.classList.add('d-none');
    if (submitBtn) submitBtn.style.display = 'inline-block';
    
    modal.show();
}

// 編集モーダルを開く
function openEditTimeEntryModal(entryId, entryUserId, entryDate, entryHours, entryDescription, entryTaskId, currentUserId) {
    const modal = getTimeEntryModal();
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // モーダルタイトルとボタンテキストを変更
    const titleText = document.getElementById('modalTitleText');
    const submitText = document.getElementById('submitButtonText');
    if (titleText) titleText.textContent = '工数記録の編集';
    if (submitText) submitText.textContent = '更新';
    
    // フォームを編集モードに設定
    const form = document.getElementById('timeEntryForm');
    if (form) form.action = `/time-entries/${entryId}`;
    // _methodフィールドが存在しない場合は追加
    let methodInput = document.getElementById('formMethod');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.id = 'formMethod';
        if (form) form.appendChild(methodInput);
    }
    if (methodInput) methodInput.value = 'PUT';
    const deleteForm = document.getElementById('deleteTimeEntryForm');
    if (deleteForm) deleteForm.action = `/time-entries/${entryId}`;
    
    // フォームに値を設定（元のパラメータを表示）
    const dateInput = document.getElementById('modal_date');
    const hoursInput = document.getElementById('modal_hours');
    const descriptionInput = document.getElementById('modal_description');
    const taskIdSelect = document.getElementById('modal_task_id');
    
    if (dateInput) dateInput.value = entryDate || '';
    if (hoursInput) hoursInput.value = entryHours || '';
    if (descriptionInput) descriptionInput.value = entryDescription || '';
    if (taskIdSelect) taskIdSelect.value = entryTaskId || '';
    
    // 自分の工数記録かどうかで表示を切り替え
    const canEdit = entryUserId === currentUserId;
    const permissionMsg = document.getElementById('editPermissionMessage');
    const deleteBtn = document.getElementById('deleteTimeEntryBtn');
    const submitBtn = document.getElementById('submitTimeEntryBtn');
    
    if (permissionMsg) permissionMsg.classList.toggle('d-none', canEdit);
    if (deleteBtn) deleteBtn.style.display = canEdit ? 'inline-block' : 'none';
    if (submitBtn) submitBtn.style.display = canEdit ? 'inline-block' : 'none';
    
    // 編集不可の場合はフォームを無効化
    if (dateInput) dateInput.disabled = !canEdit;
    if (hoursInput) hoursInput.disabled = !canEdit;
    if (descriptionInput) descriptionInput.disabled = !canEdit;
    if (taskIdSelect) taskIdSelect.disabled = !canEdit;
    
    modal.show();
}
