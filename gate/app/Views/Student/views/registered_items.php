<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Registered Items | Student Portal<?= $this->endSection() ?>
<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/student/registered-items.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class=" page-transition-container pt-5 mt-4">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-semibold text-dark mb-0">My Registered Items</h4>
        </div>

        <?php if (!empty($items)): ?>
            <div class="row px-2 px-md-0 skeleton-wrapper">
                <?php for($i=0; $i<count($items); $i++): ?>
                    <div class="col-6 col-md-6 col-xl-4 mb-3 mb-md-4 px-2">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="skeleton w-100" style="height: 150px; border-radius: 0;"></div>
                            <div class="card-body d-flex flex-column p-3 p-md-4">
                                <div class="skeleton skeleton-badge rounded-2 mb-2" style="width: 60px; height: 18px;"></div>
                                <div class="skeleton skeleton-title w-100 mb-1" style="height: 20px;"></div>
                                <div class="skeleton skeleton-text w-75 mb-3" style="height: 14px;"></div>
                                <div class="skeleton skeleton-text w-50 mb-1" style="height: 12px;"></div>
                                <div class="skeleton skeleton-text w-50 mb-3" style="height: 12px;"></div>
                                <div class="skeleton rounded-3 w-100 mt-2" style="height: 38px;"></div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <div class="real-wrapper <?= empty($items) ? '' : 'd-none' ?>">
            <?php if (empty($items)): ?>
                <div class="card border-0 shadow-sm w-100 rounded-4">
                    <div class="card-body p-5 text-center">
                        <i class="ti ti-device-laptop d-block mb-3 text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                        <h5 class="fw-bold text-dark">No Items Found</h5>
                        <p class="text-muted">You have no registered items yet.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="row px-2 px-md-0">
                    <?php foreach($items as $item): ?>
                        <div class="col-6 col-md-6 col-xl-4 mb-3 mb-md-4 px-2">

                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.2s; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#itemModal<?= $item['id'] ?>">

                                <?php if (!empty($item['photo'])): ?>
                                    <img src="<?= base_url('uploads/items/' . esc($item['photo'])) ?>" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Item Photo">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center card-img-top" style="height: 150px;">
                                        <i class="ti ti-device-laptop text-muted opacity-50" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="card-body d-flex flex-column p-3 p-md-4">

                                    <?php
                                    $badge = 'bg-secondary';
                                    if ($item['status'] === 'approved') $badge = 'bg-success';
                                    if ($item['status'] === 'missing') $badge = 'bg-danger';
                                    if ($item['status'] === 'pending') $badge = 'bg-warning text-dark';
                                    ?>
                                    <div class="mb-2">
                                        <span class="badge <?= $badge ?> px-2 py-1 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                            <?= esc($item['status']) ?>
                                        </span>
                                    </div>

                                    <h6 class="fw-bold mb-1 text-dark text-truncate" style="font-size: 1rem;" title="<?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?>">
                                        <?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?>
                                    </h6>

                                    <div class="mb-2">
                                        <span class="fw-bolder text-dark" style="font-size: 0.85rem;"><?= esc($item['serial_number'] ?? 'N/A') ?></span>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row text-muted mb-2 gap-1 gap-md-2 fw-medium" style="font-size: 0.8rem;">
                                        <div class="d-flex align-items-center text-truncate">
                                            <i class="ti ti-category me-1 fs-5"></i>
                                            <span class="text-truncate"><?= esc($item['category'] ?? 'N/A') ?></span>
                                        </div>
                                        <div class="d-flex align-items-center text-truncate">
                                            <?php if ($item['status'] === 'approved' && !empty($item['rfid'])): ?>
                                                <i class="ti ti-nfc me-1 fs-5 text-primary"></i>
                                                <span class="text-truncate" title="<?= esc($item['rfid']) ?>">
                            <?= esc($item['rfid']) ?>
                        </span>
                                            <?php else: ?>
                                                <i class="ti ti-nfc-off me-1 fs-5"></i> Unassigned
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <?php
                                        $locationText = (isset($item['in_campus']) && $item['in_campus'] == 1) ? 'Inside' : 'Outside';
                                        $locClass = (isset($item['in_campus']) && $item['in_campus'] == 1) ? 'btn-primary' : 'btn-outline-primary';
                                        $locIcon  = (isset($item['in_campus']) && $item['in_campus'] == 1) ? 'ti-building' : 'ti-building-off';

                                        if ($item['status'] !== 'approved') {
                                            $locationText = 'Outside';
                                            $locClass = 'btn-light text-muted border';
                                            $locIcon  = 'ti-ban';
                                        }
                                        ?>
                                        <div class="btn <?= $locClass ?> w-100 fw-bold py-2 rounded-3" style="pointer-events: none; font-size: 0.85rem;">
                                            <i class="ti <?= $locIcon ?> fs-5 me-1"></i> <?= $locationText ?> <span class="d-none d-md-inline">Campus</span>
                                        </div>
                                    </div>

                                    <?php if ($item['status'] === 'missing'): ?>
                                        <a href="<?= base_url('student/items/mark-found/' . $item['id']) ?>"
                                           class="btn btn-success w-100 fw-bold py-2 rounded-3 mt-2"
                                           style="font-size: 0.85rem;"
                                           onclick="event.stopPropagation(); return confirm('Mark this item as found? This clears the missing alert for guards.');">
                                            <i class="ti ti-circle-check fs-5 me-1"></i> Mark as Found
                                        </a>
                                    <?php endif; ?>

                                    <?php
                                    $itemStillAtSchool = (int) ($item['in_campus'] ?? 0) === 1;
                                    $toggleLocked = $itemStillAtSchool && empty($isInsideCampus);
                                    ?>
                                    <?php if ($item['status'] === 'approved' && ($item['brand_model'] ?? '') !== 'Item Pass'): ?>
                                        <form action="<?= base_url('student/items/toggle-bringing/' . $item['id']) ?>" method="POST"
                                              class="mt-2 d-flex align-items-center justify-content-between bg-light rounded-3 px-3 py-2"
                                              onclick="event.stopPropagation();">
                                            <?= csrf_field() ?>
                                            <label class="form-check-label small fw-semibold text-muted mb-0" for="bringToggle<?= $item['id'] ?>" style="font-size: 0.75rem;">
                                                Bringing
                                                <?php if ($toggleLocked): ?>
                                                    <span class="d-block text-muted fw-normal" style="font-size: 0.65rem;">Left at school — changeable once you're back inside</span>
                                                <?php endif; ?>
                                            </label>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="bringToggle<?= $item['id'] ?>"
                                                       onchange="this.form.submit()"
                                                        <?= $toggleLocked ? 'disabled title="Changeable once you\'re back inside campus"' : '' ?>
                                                        <?= (isset($item['is_bringing']) && (int) $item['is_bringing'] === 1) ? 'checked' : '' ?>>
                                            </div>
                                        </form>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <div class="modal fade" id="itemModal<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 rounded-4 shadow">
                                        <div class="modal-body p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="fw-bold mb-0">
                                                    <?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?>
                                                </h5>
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if ($item['status'] === 'pending'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 d-none" id="cancelBtn<?= $item['id'] ?>"
                                                                onclick="window.toggleEditMode('<?= $item['id'] ?>')">
                                                            Cancel
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-3" id="editBtn<?= $item['id'] ?>"
                                                                onclick="window.toggleEditMode('<?= $item['id'] ?>')">
                                                            <i class="ti ti-pencil"></i> Edit
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    <button type="button" class="mobile-sheet-close" data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="ti ti-x"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="modeContainer<?= $item['id'] ?>" class="item-mode-container">

                                                <div id="viewMode<?= $item['id'] ?>" class="item-mode-panel">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-5 text-center mb-4 mb-md-0">
                                                            <?php if (!empty($item['photo'])): ?>
                                                                <img src="<?= base_url('uploads/items/' . esc($item['photo'])) ?>" class="img-fluid rounded-3 shadow-sm" alt="Item Photo" style="max-height: 220px; object-fit: contain;">
                                                            <?php else: ?>
                                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height:180px;">
                                                                    <i class="ti ti-device-laptop fs-1 text-muted opacity-50"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>

                                                        <div class="col-md-7">
                                                            <?php
                                                            $badge2 = 'bg-secondary';
                                                            if ($item['status'] === 'approved') $badge2 = 'bg-success';
                                                            if ($item['status'] === 'missing') $badge2 = 'bg-danger';
                                                            if ($item['status'] === 'pending') $badge2 = 'bg-warning text-dark';
                                                            if ($item['status'] === 'rejected') $badge2 = 'bg-danger';
                                                            if ($item['status'] === 'staged') $badge2 = 'bg-warning text-dark';
                                                            if ($item['status'] === 'archived') $badge2 = 'bg-dark';
                                                            ?>
                                                            <span class="badge <?= $badge2 ?> px-2 py-1 text-uppercase mb-3 d-inline-block" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                                                <?= esc($item['status']) ?>
                                                            </span>

                                                            <p class="text-muted fw-semibold mb-2">Serial Number: <span class="fw-normal text-dark"><?= esc($item['serial_number'] ?? 'N/A') ?></span></p>
                                                            <p class="text-muted fw-semibold mb-2">Category: <span class="fw-normal text-dark"><?= esc($item['category'] ?? 'N/A') ?></span></p>
                                                            <p class="text-muted fw-semibold mb-2">
                                                                RFID Tag:
                                                                <?php if ($item['status'] === 'approved' && !empty($item['rfid'])): ?>
                                                                    <span class="fw-normal text-dark"><?= esc($item['rfid']) ?></span>
                                                                <?php else: ?>
                                                                    <span class="fw-normal text-muted">Unassigned</span>
                                                                <?php endif; ?>
                                                            </p>
                                                            <p class="text-muted fw-semibold mb-3">
                                                                Campus Status:
                                                                <span class="fw-normal text-dark">
                                                                    <?= (isset($item['in_campus']) && $item['in_campus'] == 1 && $item['status'] === 'approved') ? 'Inside Campus' : 'Outside Campus' ?>
                                                                </span>
                                                            </p>

                                                            <?php if ($item['status'] === 'approved' && ($item['brand_model'] ?? '') !== 'Item Pass'): ?>
                                                                <p class="text-muted fw-semibold mb-3">
                                                                    Bringing:
                                                                    <span class="fw-normal text-dark">
                                                                        <?= (isset($item['is_bringing']) && (int) $item['is_bringing'] === 1) ? 'Yes' : 'No — left at home' ?>
                                                                    </span>
                                                                </p>
                                                            <?php endif; ?>

                                                            <?php if (!empty($item['notes'])): ?>
                                                                <h6 class="fw-bold">Notes:</h6>
                                                                <div class="bg-light rounded-3 p-3 text-muted fst-italic">
                                                                    <?= esc($item['notes']) ?>
                                                                </div>
                                                            <?php endif; ?>

                                                            <?php if ($item['status'] === 'missing'): ?>
                                                                <a href="<?= base_url('student/items/mark-found/' . $item['id']) ?>"
                                                                   class="btn btn-success w-100 fw-bold py-2 rounded-3 mt-3"
                                                                   onclick="return confirm('Mark this item as found? This clears the missing alert for guards.');">
                                                                    <i class="ti ti-circle-check me-1"></i> Mark as Found
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if ($item['status'] === 'pending'): ?>
                                                    <div id="editMode<?= $item['id'] ?>" class="item-mode-panel d-none">
                                                        <form id="editForm<?= $item['id'] ?>" action="<?= base_url('student/items/update/' . $item['id']) ?>" method="post" enctype="multipart/form-data" class="bg-light rounded-3 p-3">
                                                            <?= csrf_field() ?>

                                                            <?php
                                                            $editIsPCD = ($item['category'] ?? '') === 'Personal Computing Device';
                                                            $editIsOthers = ($item['category'] ?? '') === 'Others';
                                                            $editShowDetails = $editIsOthers || ($editIsPCD && !empty($item['subcategory']));
                                                            ?>

                                                            <div class="text-center mb-3">
                                                                <div id="photoPlaceholder<?= $item['id'] ?>" class="bg-light border rounded-3 d-flex align-items-center justify-content-center mx-auto <?= !empty($item['photo']) ? 'd-none' : '' ?>" style="height:140px; width:140px;">
                                                                    <i class="ti ti-device-laptop fs-1 text-muted opacity-50"></i>
                                                                </div>
                                                                <img id="photoPreview<?= $item['id'] ?>"
                                                                     src="<?= !empty($item['photo']) ? base_url('uploads/items/' . esc($item['photo'])) : '' ?>"
                                                                     data-original-src="<?= !empty($item['photo']) ? base_url('uploads/items/' . esc($item['photo'])) : '' ?>"
                                                                     class="img-fluid rounded-3 shadow-sm mx-auto d-block <?= empty($item['photo']) ? 'd-none' : '' ?>"
                                                                     alt="Item Photo" style="max-height: 140px; object-fit: contain;">
                                                            </div>

                                                            <div class="mb-2">
                                                                <label class="form-label small fw-semibold mb-1">Category</label>
                                                                <select class="form-select form-select-sm" name="category" id="editCategory<?= $item['id'] ?>"
                                                                        onchange="window.handleEditCategoryChange('<?= $item['id'] ?>')">
                                                                    <option value="Personal Computing Device" <?= $editIsPCD ? 'selected' : '' ?>>Personal Computing Device</option>
                                                                    <option value="Others" <?= $editIsOthers ? 'selected' : '' ?>>Others</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-2 <?= $editIsPCD ? '' : 'd-none' ?>" id="editSubcategoryWrap<?= $item['id'] ?>">
                                                                <label class="form-label small fw-semibold mb-1">Device Type</label>
                                                                <select class="form-select form-select-sm" name="subcategory" id="editSubcategory<?= $item['id'] ?>"
                                                                        onchange="window.handleEditSubcategoryChange('<?= $item['id'] ?>')" <?= $editIsPCD ? 'required' : '' ?>>
                                                                    <option value="">Select a device type...</option>
                                                                    <?php foreach (['Laptop', 'Tablet', 'Camera', 'Handheld Gaming Device', 'E-Reader'] as $type): ?>
                                                                        <option value="<?= $type ?>" <?= ($item['subcategory'] ?? '') === $type ? 'selected' : '' ?>><?= $type ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>

                                                            <div id="editDetailWrap<?= $item['id'] ?>" class="<?= $editShowDetails ? '' : 'd-none' ?>">
                                                                <div class="mb-2">
                                                                    <label class="form-label small fw-semibold mb-1" id="editBrandModelLabel<?= $item['id'] ?>">
                                                                        <?= $editIsOthers ? 'Item' : 'Brand & Model' ?>
                                                                    </label>
                                                                    <input type="text" class="form-control form-control-sm" name="brand_model" id="editBrandModelInput<?= $item['id'] ?>"
                                                                           value="<?= esc(old('brand_model', $item['brand_model'] ?? '')) ?>"
                                                                           placeholder="<?= $editIsOthers ? 'e.g., Power Tools, Cookware, Heavy Equipment' : 'e.g., Acer Predator Helios 300' ?>" required>
                                                                </div>

                                                                <div class="mb-2">
                                                                    <label class="form-label small fw-semibold mb-1" id="editSerialLabel<?= $item['id'] ?>">
                                                                        <?= $editIsOthers ? 'Material Identifier' : 'Unique Identifier/Serial Number' ?>
                                                                    </label>

                                                                    <input type="text" class="form-control form-control-sm <?= $editIsOthers ? 'd-none' : '' ?>" name="serial_number" id="editSerialInput<?= $item['id'] ?>"
                                                                           value="<?= esc(old('serial_number', $item['serial_number'] ?? '')) ?>" <?= $editIsOthers ? 'disabled' : 'required' ?>>

                                                                    <select class="form-select form-select-sm <?= $editIsOthers ? '' : 'd-none' ?>" name="serial_number" id="editMaterialSelect<?= $item['id'] ?>" <?= $editIsOthers ? 'required' : 'disabled' ?>>
                                                                        <option value="" disabled <?= empty($item['serial_number']) ? 'selected' : '' ?>>Select a material...</option>
                                                                        <?php foreach (['Wooden', 'Plastic', 'Metal', 'Ceramic', 'Carbon Fiber'] as $material): ?>
                                                                            <option value="<?= $material ?>" <?= ($item['serial_number'] ?? '') === $material ? 'selected' : '' ?>><?= $material ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>

                                                                    <div class="form-text" id="editSerialHelp<?= $item['id'] ?>" style="font-size: 0.7rem;">
                                                                        <?= $editIsOthers ? 'Select the material this item is primarily made of.' : 'Found on the bottom of laptops or back of devices.' ?>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-semibold mb-1">Photo</label>
                                                                    <input type="file" class="form-control form-control-sm" name="photo" accept="image/*"
                                                                           onchange="window.previewItemPhoto(this, '<?= $item['id'] ?>')">
                                                                    <div class="form-text" style="font-size: 0.7rem;">Leave blank to keep the current photo.</div>
                                                                </div>
                                                            </div>

                                                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Save Changes</button>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <script src="<?= base_url('assets/js/student/registered-items.js') ?>"></script>

    </div>

<?= $this->endSection() ?>