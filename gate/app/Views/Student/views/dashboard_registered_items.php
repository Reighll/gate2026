<?= $this->extend('Student/layout/main') ?>
<?= $this->section('title') ?>Registered Items | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="pt-5 mt-4">
        <div class="card border-0 shadow-sm w-100">
            <div class="card-body p-4">
                <h4 class="card-title fw-semibold mb-4">My Registered Items</h4>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0">
                        <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Item Details</h6></th>
                            <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Category</h6></th>
                            <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Serial Number</h6></th>
                            <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Assigned Tag</h6></th>
                            <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Status</h6></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($items)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">You have no registered items yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($items as $item): ?>
                                <tr>
                                    <td class="border-bottom-0">
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($item['photo'])): ?>
                                                <img src="<?= base_url('uploads/items/' . esc($item['photo'])) ?>" class="rounded-3 shadow-sm" width="50" height="50" style="object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                                                    <i class="ti ti-device-laptop text-muted fs-4"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="ms-3">
                                                <h6 class="fw-semibold mb-0"><?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= esc($item['category'] ?? 'N/A') ?></p></td>
                                    <td class="border-bottom-0"><p class="mb-0 fw-normal"><?= esc($item['serial_number'] ?? 'N/A') ?></p></td>
                                    <td class="border-bottom-0">
                                        <?php if ($item['status'] === 'approved' && !empty($item['rfid'])): ?>
                                            <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1 fs-3">
                                                <i class="ti ti-nfc me-1"></i> <?= esc($item['rfid']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border-bottom-0">
                                        <?php
                                        // Dynamic badge styling
                                        $badge = 'bg-secondary';
                                        if ($item['status'] === 'approved') $badge = 'bg-success';
                                        if ($item['status'] === 'missing') $badge = 'bg-danger shadow-sm border border-danger-subtle';
                                        if ($item['status'] === 'pending') $badge = 'bg-warning text-dark';

                                        // Location tag
                                        $location = (isset($item['in_campus']) && $item['in_campus'] == 1) ? ' (Inside)' : ' (Outside)';
                                        ?>
                                        <span class="badge <?= $badge ?> px-2 py-1 fs-3">
                                            <?= ucfirst(esc($item['status'])) ?> <?= ($item['status'] === 'approved') ? $location : '' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

<?= $this->endSection() ?>