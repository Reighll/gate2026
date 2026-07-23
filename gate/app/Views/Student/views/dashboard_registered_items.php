<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->section('styles') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css">
    <style>
        /* Include the same Intro.js styling overrides here from your dashboard to keep the dark mode and mobile fixes consistent */
        .introjs-overlay { background-color: rgba(17, 20, 45, 0.95) !important; z-index: 9999990 !important; }
        .introjs-helperLayer { background: rgba(0, 0, 0, 0.15) !important; border-radius: 12px !important; z-index: 9999995 !important; }
        .introjs-tooltip { border-radius: 16px !important; }

     /* =========================================
        1. COLOR VARIABLES (THEME MANAGEMENT)
        ========================================= */

             /* Light Mode (Default) Colors */
         :root {
             --tour-bg: #ffffff;
             --tour-border: none;
             --tour-title: #1e4db7;
             --tour-text: #546269;
             --tour-btn-top-border: 1px solid #ecf0f2;
             --tour-prev-color: #777e89;
             --tour-prev-hover: #11142d;
             --tour-next-bg: #1e4db7;
             --tour-next-color: #ffffff;
             --tour-next-hover: #183e92;
             --tour-bullet: #ced4da;
             --tour-bullet-active: #1e4db7;
             --tour-arrow: #ffffff;
             --tour-shadow: 0 16px 32px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
         }

        /* Dark Mode Colors */
        html[data-bs-theme="dark"] {
            --tour-bg: #223640;
            --tour-border: 1px solid #4f5467;
            --tour-title: #8bb4fa;
            --tour-text: #f1f9ff;
            --tour-btn-top-border: 1px solid #4f5467;
            --tour-prev-color: #a1aab2;
            --tour-prev-hover: #ffffff;
            --tour-next-bg: #8bb4fa;
            --tour-next-color: #11142d;
            --tour-next-hover: #a5c7ff;
            --tour-bullet: #4f5467;
            --tour-bullet-active: #8bb4fa;
            --tour-arrow: #223640;
            --tour-shadow: 0 16px 32px rgba(0, 0, 0, 0.4);
        }

        /* =========================================
           2. STRUCTURAL & COMPONENT STYLES
           ========================================= */

        /* Force structural integrity against Bootstrap resets */
        .introjs-tooltip {
            position: absolute !important;
            background: var(--tour-bg) !important;
            border: var(--tour-border) !important;
            border-radius: 16px !important;
            box-shadow: var(--tour-shadow) !important;
            padding: 24px !important;
            width: 340px !important;
            max-width: calc(100vw - 40px) !important;
            opacity: 1 !important;
            visibility: visible !important;
            z-index: 9999999 !important;
            box-sizing: border-box !important;
        }

        /* --- MOBILE ADAPTIVE SCALING --- */
        @media (max-width: 576px) {
            .introjs-tooltip {
                width: 90vw !important;
                padding: 18px !important;
                border-radius: 14px !important;
            }
            .introjs-tooltiptitle {
                font-size: 1.15rem !important;
            }
            .introjs-tooltiptext {
                font-size: 0.9rem !important;
            }
            .introjs-button {
                padding: 6px 16px !important;
                font-size: 0.85rem !important;
            }
        }

        /* The dark screen overlay */
        .introjs-overlay {
            background-color: rgba(17, 20, 45, 0.95) !important; /* Increased opacity for a darker background */
            z-index: 9999990 !important;
        }

        /* The transparent cutout target */
        .introjs-helperLayer {
            background: rgba(0, 0, 0, 0.15) !important; /* Adds a subtle dim effect over the highlighted element */
            border-radius: 12px !important;
            box-shadow: 0 0 0 0 transparent !important;
            border: 2px solid rgba(255, 255, 255, 0.6) !important;
            z-index: 9999995 !important;
        }

        /* Typography & Hierarchy */
        .introjs-tooltipheader {
            padding: 0 !important;
            margin-bottom: 12px !important;
        }

        .introjs-tooltiptitle {
            font-family: "DM Sans", sans-serif !important;
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: var(--tour-title) !important; /* Using variable */
            margin: 0 !important;
            line-height: 1.3 !important;
        }

        .introjs-tooltiptext {
            font-family: "DM Sans", sans-serif !important;
            font-size: 0.95rem !important;
            color: var(--tour-text) !important; /* Using variable */
            line-height: 1.6 !important;
            padding: 0 0 20px 0 !important;
            margin: 0 !important;
        }

        /* Action Buttons Area */
        .introjs-tooltipbuttons {
            border-top: var(--tour-btn-top-border) !important; /* Using variable */
            padding-top: 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        /* Shared Button Traits */
        .introjs-button {
            font-family: "DM Sans", sans-serif !important;
            border-radius: 50px !important;
            padding: 8px 20px !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            text-shadow: none !important;
            background-image: none !important;
            text-decoration: none !important;
        }

        /* Secondary/Back Buttons */
        .introjs-prevbutton, .introjs-skipbutton {
            background: transparent !important;
            color: var(--tour-prev-color) !important; /* Using variable */
            border: none !important;
            padding-left: 0 !important;
            box-shadow: none !important;
        }
        .introjs-prevbutton:hover, .introjs-skipbutton:hover {
            color: var(--tour-prev-hover) !important; /* Using variable */
        }

        /* Primary Next/Done Buttons */
        .introjs-nextbutton, .introjs-donebutton {
            background: var(--tour-next-bg) !important; /* Using variable */
            color: var(--tour-next-color) !important; /* Using variable */
            border: none !important;
            box-shadow: 0 4px 10px rgba(0,0,0, 0.15) !important; /* Unified shadow for simplicity */
        }
        .introjs-nextbutton:hover, .introjs-donebutton:hover {
            background: var(--tour-next-hover) !important; /* Using variable */
            transform: translateY(-2px) !important;
        }

        .introjs-disabled {
            opacity: 0.4 !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
        }

        /* Custom Elongated Progress Bullets */
        .introjs-bullets {
            display: flex !important;
            align-items: center !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .introjs-bullets ul li a {
            background: var(--tour-bullet) !important; /* Using variable */
            width: 8px !important;
            height: 8px !important;
            border-radius: 50% !important;
            margin: 0 4px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .introjs-bullets ul li a.active {
            background: var(--tour-bullet-active) !important; /* Using variable */
            width: 24px !important;
            border-radius: 10px !important;
        }

        /* Alignment Arrows */
        .introjs-arrow.top { border-bottom-color: var(--tour-arrow) !important; }
        .introjs-arrow.bottom { border-top-color: var(--tour-arrow) !important; }
        .introjs-arrow.left { border-right-color: var(--tour-arrow) !important; }
        .introjs-arrow.right { border-left-color: var(--tour-arrow) !important; }

        /* --- TOOLTIP BOX & ARROW ALIGNMENT FIXES --- */

        /* Desktop: Shift the entire box upward */
        @media (min-width: 992px) {
            .custom-register-tooltip {
                margin-top: -35px !important;
            }
            .custom-register-tooltip .introjs-arrow.left {
                top: 15px !important;
                margin-top: 0 !important;
            }
        }

        /* Mobile/Tablet: Align the box to the right edge */
        @media (max-width: 991px) {
            .custom-register-tooltip {
                left: auto !important;
                right: 15px !important;
                width: calc(100vw - 30px) !important;
                max-width: 350px !important;
            }
            .custom-register-tooltip .introjs-arrow {
                left: auto !important;
                right: 10px !important;
                margin-left: 0 !important;
            }
        }
    </style>
<?= $this->endSection() ?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Registered Items | Student Portal<?= $this->endSection() ?>
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

                                </div>
                            </div>

                            <!-- Modal: Item Details / Edit -->
                            <div class="modal fade" id="itemModal<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 rounded-4 shadow">

                                        <!-- NEW: Sticky Header with Edit and Cancel buttons -->
                                        <!-- NEW: Sticky Header with Edit and Cancel buttons (Right-Aligned) -->
                                        <div class="modal-header sticky-top bg-body px-4 pt-4 pb-2 border-0 z-3 w-100 d-flex justify-content-between align-items-center" style="border-radius: 24px 24px 0 0;">

                                            <h5 class="fw-bold mb-0 text-truncate pe-2">
                                                <?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?>
                                            </h5>

                                            <!-- flex-shrink-0 prevents the buttons from being squished if the title is long -->
                                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                                <button type="button" id="editBtn<?= $item['id'] ?>" class="btn btn-sm btn-light border rounded-pill px-3" onclick="toggleEditMode(<?= $item['id'] ?>)">
                                                    <i class="ti ti-pencil me-1"></i> Edit
                                                </button>

                                                <button type="button" id="cancelBtn<?= $item['id'] ?>" class="btn btn-sm btn-light border rounded-pill px-3 d-none" onclick="toggleEditMode(<?= $item['id'] ?>)">
                                                    Cancel
                                                </button>

                                                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <button type="button" class="mobile-sheet-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>

                                        </div>

                                        <div class="modal-body px-4 pb-4 pt-2">
                                            <div id="modeContainer<?= $item['id'] ?>" class="item-mode-container">
                                                <!-- VIEW MODE -->
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

                                                            <?php if (!empty($item['notes'])): ?>
                                                                <h6 class="fw-bold">Notes:</h6>
                                                                <div class="bg-light rounded-3 p-3 text-muted fst-italic">
                                                                    <?= esc($item['notes']) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- EDIT MODE -->
                                                <div id="editMode<?= $item['id'] ?>" class="item-mode-panel d-none">
                                                    <form action="<?= base_url('student/items/update/' . $item['id']) ?>" method="POST" enctype="multipart/form-data">
                                                        <?= csrf_field() ?>
                                                        <div class="row">
                                                            <div class="col-md-5 mb-4 mb-md-0">

                                                                <div class="text-center mb-3">
                                                                    <?php if (!empty($item['photo'])): ?>
                                                                        <img src="<?= base_url('uploads/items/' . esc($item['photo'])) ?>" class="img-fluid rounded-3 shadow-sm" alt="Current Photo" style="max-height: 180px; object-fit: contain;">
                                                                    <?php else: ?>
                                                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-3" style="height:150px;">
                                                                            <i class="ti ti-device-laptop fs-1 text-muted opacity-50"></i>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>

                                                                <div class="text-start">
                                                                    <label class="form-label small fw-bold text-muted mb-1 d-block">Replace Photo</label>
                                                                    <input type="file" class="form-control" name="photo" accept="image/*">
                                                                    <div class="form-text">Max file size: 50MB. Leave blank to keep the current photo.</div>
                                                                </div>

                                                            </div>

                                                            <div class="col-md-7">
                                                                <label class="form-label small fw-bold text-muted mb-1">Serial Number / Unique Identifier</label>
                                                                <input type="text" class="form-control mb-3" name="serial_number" value="<?= esc($item['serial_number'] ?? '') ?>" required>

                                                                <p class="text-muted small mb-0">
                                                                    <i class="ti ti-info-circle me-1"></i> Only the serial number and photo can be edited. Other fields require re-registration.
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <!-- UPDATED: Removed Cancel button, Save button is now full width on mobile -->
                                                        <div class="d-flex justify-content-end mt-4 modal-actions-mobile">
                                                            <button type="submit" class="btn btn-primary rounded-pill px-4 w-100">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
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

        <style>
            .item-mode-container {
                overflow: hidden;
                transition: height 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .item-mode-panel { transition: opacity 0.2s ease, transform 0.2s ease; }
            .item-mode-panel-out { opacity: 0; transform: translateY(6px); }
            .item-mode-panel-in { opacity: 0; transform: translateY(-6px); animation: itemModeFadeIn 0.22s ease forwards; }
            @keyframes itemModeFadeIn {
                from { opacity: 0; transform: translateY(-6px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* Custom circular close button — only shown on mobile/tablet, see media query below */
            .mobile-sheet-close {
                display: none;
                align-items: center;
                justify-content: center;
                width: 34px;
                height: 34px;
                min-width: 34px;
                border-radius: 50%;
                background: #f1f3f5;
                border: none;
                color: #495057;
                font-size: 1.05rem;
                padding: 0;
            }
            .mobile-sheet-close:active { background: #e9ecef; }

            /* ===================================================================
               MOBILE / TABLET ONLY — Bottom sheet modal styling
               Matches breakpoints up to Bootstrap's lg (992px), i.e. phones + tablets.
               Desktop (>=992px) keeps the original centered dialog untouched.
               =================================================================== */
            @media (max-width: 991.98px) {
                .modal .modal-dialog {
                    position: fixed;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    top: auto !important;
                    margin: 0;
                    width: 100%;
                    max-width: 100%;
                }
                .modal .modal-dialog.modal-dialog-centered {
                    display: block !important;
                    align-items: initial !important;
                    min-height: 0 !important;
                }
                .modal.fade .modal-dialog {
                    transform: translateY(100%);
                    transition: transform 0.32s cubic-bezier(0.32, 0.72, 0, 1);
                }
                .modal.show .modal-dialog {
                    transform: translateY(0);
                }

                .modal-content {
                    border-radius: 24px 24px 0 0 !important;
                    max-height: 92vh;
                    overflow-y: auto;
                }
                .modal-content::before {
                    content: '';
                    display: block;
                    width: 40px;
                    height: 4px;
                    border-radius: 999px;
                    background: #dbe0e6;
                    margin: 10px auto 6px;
                }

                .mobile-sheet-close {
                    display: inline-flex;
                }
                .modal-body .btn-close {
                    display: none;
                }
                .modal-body h5.fw-bold {
                    font-size: 1.1rem;
                }

                .modal-actions-mobile {
                    flex-direction: column-reverse !important;
                    align-items: stretch !important;
                }
                .modal-actions-mobile .btn {
                    width: 100%;
                }
                .modal-actions-mobile .btn-light {
                    background: transparent !important;
                    border: none !important;
                    color: #6c757d;
                }
                .modal-actions-mobile .btn-primary {
                    padding-top: 0.75rem;
                    padding-bottom: 0.75rem;
                    font-weight: 700;
                }
            }
        </style>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

    <script>
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));

                // NEW: Check for the handoff flag after the skeletons disappear
                checkItemsTourHandoff();
            }, 600);
        }

        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // NEW: The Catch Function
        function checkItemsTourHandoff() {
            if (typeof introJs === 'undefined') return;

            // Check if the dashboard sent us here for a tour
            if (localStorage.getItem('gate_tour_items_pending') === 'true') {

                // 1. Immediately delete the flag so the tour doesn't trigger on normal page visits
                localStorage.removeItem('gate_tour_items_pending');

                // 2. Start the second half of the tour
                introJs().setOptions({
                    showProgress: false,
                    showStepNumbers: false,
                    showBullets: true,
                    exitOnOverlayClick: false,
                    keyboardNavigation: true,
                    nextLabel: 'Next',
                    prevLabel: 'Back',
                    doneLabel: 'Got it! 🎉',
                    steps: [
                        {
                            title: 'Your Equipment Hub',
                            intro: 'Welcome to the Registered Items page! This is where you can manage all the devices you bring into the GATE system.'
                        },
                        {
                            element: document.querySelector('.real-wrapper'),
                            title: 'Item Status',
                            intro: 'You can click on any item card here to view its full details, update its photo, or check its specific RFID status.',
                            position: 'top'
                        }
                    ]
                }).start();
            }
        }
        window.toggleEditMode = function(id) {
            const container = document.getElementById('modeContainer' + id);
            const viewPanel = document.getElementById('viewMode' + id);
            const editPanel = document.getElementById('editMode' + id);
            const editBtn = document.getElementById('editBtn' + id);
            const cancelBtn = document.getElementById('cancelBtn' + id); // NEW: Get Cancel button

            const showingView = !viewPanel.classList.contains('d-none');
            const outgoing = showingView ? viewPanel : editPanel;
            const incoming = showingView ? editPanel : viewPanel;

            // Lock the container to its current rendered height before anything changes
            container.style.height = container.offsetHeight + 'px';

            outgoing.classList.add('item-mode-panel-out');

            setTimeout(() => {
                outgoing.classList.add('d-none');
                outgoing.classList.remove('item-mode-panel-out');

                incoming.classList.remove('d-none');
                incoming.classList.add('item-mode-panel-in');

                // NEW: Toggle Edit and Cancel button visibility
                if (editBtn) {
                    editBtn.classList.toggle('d-none', showingView);
                }
                if (cancelBtn) {
                    cancelBtn.classList.toggle('d-none', !showingView);
                }

                // Measure the incoming panel's natural height, then animate the container to it
                const targetHeight = incoming.scrollHeight;
                // Force reflow so the browser registers the starting height before we change it
                void container.offsetHeight;
                container.style.height = targetHeight + 'px';

                setTimeout(() => {
                    incoming.classList.remove('item-mode-panel-in');
                    // Release the fixed height so the modal can respond naturally afterward
                    container.style.height = 'auto';
                }, 280);
            }, 180);
        };
    </script>

<?= $this->endSection() ?>