<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Registered Items | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="pt-5 mt-4">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-semibold text-dark mb-0">My Registered Items</h4>
        </div>

        <div class="row px-2 px-md-0 skeleton-wrapper">
            <?php for($i=0; $i<3; $i++): ?>
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

        <div class="real-wrapper d-none">
            <?php if (empty($items)): ?>
                <div class="card border-0 shadow-sm w-100 rounded-4">
                    <div class="card-body p-5 text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="ti ti-device-laptop d-block mb-3 text-muted" style="font-size: 2rem; opacity: 0.5;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">No Items Found</h5>
                        <p class="text-muted">You have no registered items yet.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="row px-2 px-md-0">
                    <?php foreach($items as $item): ?>
                        <div class="col-6 col-md-6 col-xl-4 mb-3 mb-md-4 px-2">

                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.2s;">

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
                                            $locationText = 'Disabled';
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
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }

        document.addEventListener("DOMContentLoaded", hideMySkeletons);

        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);
    </script>
<?= $this->endSection() ?>