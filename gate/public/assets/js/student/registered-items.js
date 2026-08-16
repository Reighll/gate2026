function hideMySkeletons() {
    setTimeout(() => {
        document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
    }, 600);
}

document.addEventListener("DOMContentLoaded", hideMySkeletons);
document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

window.toggleEditMode = function(id) {
    const container = document.getElementById('modeContainer' + id);
    const viewPanel = document.getElementById('viewMode' + id);
    const editPanel = document.getElementById('editMode' + id);
    const editBtn = document.getElementById('editBtn' + id);
    const cancelBtn = document.getElementById('cancelBtn' + id);

    const showingView = !viewPanel.classList.contains('d-none');
    const outgoing = showingView ? viewPanel : editPanel;
    const incoming = showingView ? editPanel : viewPanel;

    container.style.height = container.offsetHeight + 'px';

    outgoing.classList.add('item-mode-panel-out');

    setTimeout(() => {
        outgoing.classList.add('d-none');
        outgoing.classList.remove('item-mode-panel-out');

        incoming.classList.remove('d-none');
        incoming.classList.add('item-mode-panel-in');

        if (editBtn) {
            editBtn.classList.toggle('d-none', showingView);
        }
        if (cancelBtn) {
            cancelBtn.classList.toggle('d-none', !showingView);
        }
        if (!showingView) {
            resetPhotoPreview(id);
        }

        const targetHeight = incoming.scrollHeight;
        void container.offsetHeight;
        container.style.height = targetHeight + 'px';

        setTimeout(() => {
            incoming.classList.remove('item-mode-panel-in');
            container.style.height = 'auto';
        }, 280);
    }, 180);
};

function toggleCollapseEl(el, show) {
    if (!el) return;
    const isHidden = el.classList.contains('d-none');
    if (show === !isHidden) return;

    if (show) {
        el.classList.remove('d-none');
        el.style.overflow = 'hidden';
        el.style.maxHeight = '0px';
        el.style.opacity = '0';
        void el.offsetHeight;
        const target = el.scrollHeight;
        el.style.transition = 'max-height 0.3s ease, opacity 0.25s ease';
        el.style.maxHeight = target + 'px';
        el.style.opacity = '1';
        el.addEventListener('transitionend', function handler(e) {
            if (e.target !== el || e.propertyName !== 'max-height') return;
            el.style.maxHeight = '';
            el.style.overflow = '';
            el.style.transition = '';
            el.removeEventListener('transitionend', handler);
        });
    } else {
        const startHeight = el.scrollHeight;
        el.style.overflow = 'hidden';
        el.style.maxHeight = startHeight + 'px';
        el.style.opacity = '1';
        void el.offsetHeight;
        el.style.transition = 'max-height 0.3s ease, opacity 0.25s ease';
        el.style.maxHeight = '0px';
        el.style.opacity = '0';
        el.addEventListener('transitionend', function handler(e) {
            if (e.target !== el || e.propertyName !== 'max-height') return;
            el.classList.add('d-none');
            el.style.maxHeight = '';
            el.style.overflow = '';
            el.style.transition = '';
            el.removeEventListener('transitionend', handler);
        });
    }
}

function resetPhotoPreview(id) {
    const img = document.getElementById('photoPreview' + id);
    const placeholder = document.getElementById('photoPlaceholder' + id);
    const fileInput = document.querySelector('#editForm' + id + ' input[type="file"]');
    if (fileInput) fileInput.value = '';
    if (!img) return;

    const original = img.dataset.originalSrc || '';
    if (original) {
        img.src = original;
        img.classList.remove('d-none');
        if (placeholder) placeholder.classList.add('d-none');
    } else {
        img.removeAttribute('src');
        img.classList.add('d-none');
        if (placeholder) placeholder.classList.remove('d-none');
    }
}

function swapFieldEl(showEl, hideEl) {
    if (!showEl || !hideEl) return;
    if (hideEl.classList.contains('d-none') && !showEl.classList.contains('d-none')) {
        hideEl.disabled = true;
        showEl.disabled = false;
        return;
    }

    hideEl.disabled = true;
    hideEl.style.transition = 'opacity 0.15s ease';
    hideEl.style.opacity = '0';
    setTimeout(function() {
        hideEl.classList.add('d-none');
        hideEl.style.transition = '';
        hideEl.style.opacity = '';

        showEl.disabled = false;
        showEl.classList.remove('d-none');
        showEl.style.opacity = '0';
        showEl.style.transition = 'opacity 0.15s ease';
        void showEl.offsetHeight;
        showEl.style.opacity = '1';
        setTimeout(function() {
            showEl.style.transition = '';
            showEl.style.opacity = '';
        }, 160);
    }, 160);
}

function applyEditDetailFieldState(id, isPCD, isOthers, subcategoryValue) {
    const detailWrap = document.getElementById('editDetailWrap' + id);
    const brandModelLabel = document.getElementById('editBrandModelLabel' + id);
    const brandModelInput = document.getElementById('editBrandModelInput' + id);
    const serialLabel = document.getElementById('editSerialLabel' + id);
    const serialInput = document.getElementById('editSerialInput' + id);
    const materialSelect = document.getElementById('editMaterialSelect' + id);
    const serialHelp = document.getElementById('editSerialHelp' + id);

    const showDetails = isOthers || (isPCD && subcategoryValue !== '');
    toggleCollapseEl(detailWrap, showDetails);
    brandModelInput.required = showDetails;

    if (isOthers) {
        swapFieldEl(materialSelect, serialInput);
        serialInput.required = false;
        materialSelect.required = showDetails;
    } else {
        swapFieldEl(serialInput, materialSelect);
        serialInput.required = showDetails;
        materialSelect.required = false;
    }

    if (isOthers) {
        brandModelLabel.textContent = 'Item';
        brandModelInput.placeholder = 'e.g., Power Tools, Cookware, Heavy Equipment';
        serialLabel.textContent = 'Material Identifier';
        serialHelp.textContent = 'Select the material this item is primarily made of.';
    } else if (isPCD) {
        brandModelLabel.textContent = 'Brand & Model';
        brandModelInput.placeholder = 'e.g., Acer Predator Helios 300';
        serialLabel.textContent = 'Unique Identifier/Serial Number';
        serialHelp.textContent = 'Found on the bottom of laptops or back of devices.';
    }
}

window.handleEditCategoryChange = function(id) {
    const category = document.getElementById('editCategory' + id).value;
    const isPCD = category === 'Personal Computing Device';
    const isOthers = category === 'Others';

    const subWrapper = document.getElementById('editSubcategoryWrap' + id);
    const subSelect = document.getElementById('editSubcategory' + id);

    toggleCollapseEl(subWrapper, isPCD);
    subSelect.required = isPCD;
    if (!isPCD) subSelect.value = '';

    applyEditDetailFieldState(id, isPCD, isOthers, subSelect.value);
};

window.handleEditSubcategoryChange = function(id) {
    const isPCD = document.getElementById('editCategory' + id).value === 'Personal Computing Device';
    const subSelect = document.getElementById('editSubcategory' + id);
    applyEditDetailFieldState(id, isPCD, false, subSelect.value);
};

window.previewItemPhoto = function(input, id) {
    const file = input.files && input.files[0];
    if (!file) return;

    const img = document.getElementById('photoPreview' + id);
    const placeholder = document.getElementById('photoPlaceholder' + id);
    if (!img) return;

    img.src = URL.createObjectURL(file);
    img.classList.remove('d-none');
    if (placeholder) placeholder.classList.add('d-none');
};

document.addEventListener('hidden.bs.modal', function(e) {
    const editMode = e.target.querySelector('div[id^="editMode"]');
    const viewMode = e.target.querySelector('div[id^="viewMode"]');
    const editBtn = e.target.querySelector('button[id^="editBtn"]');
    const cancelBtn = e.target.querySelector('button[id^="cancelBtn"]');
    const container = e.target.querySelector('div[id^="modeContainer"]');

    if (editMode && !editMode.classList.contains('d-none')) {
        const id = editMode.id.replace('editMode', '');
        resetPhotoPreview(id);
        editMode.classList.add('d-none');
        editMode.classList.remove('item-mode-panel-in', 'item-mode-panel-out');
        if (viewMode) {
            viewMode.classList.remove('d-none', 'item-mode-panel-in', 'item-mode-panel-out');
        }
        if (editBtn) editBtn.classList.remove('d-none');
        if (cancelBtn) cancelBtn.classList.add('d-none');
        if (container) container.style.height = 'auto';
    }
});