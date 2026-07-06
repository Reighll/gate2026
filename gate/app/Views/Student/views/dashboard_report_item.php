<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Report Item | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="pt-5 mt-4">
        <h4 class="fw-semibold mb-3">Report Lost/Stolen Item</h4>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <div class="alert alert-warning rounded-3 border-0 shadow-sm">
                    <i class="ti ti-alert-circle me-2"></i> If your equipment goes missing inside the campus, report it immediately.
                </div>

                <div class="skeleton-wrapper mt-4">
                    <div class="mb-3">
                        <div class="skeleton skeleton-text w-25 mb-2"></div>
                        <div class="skeleton rounded-3 w-100" style="height: 42px;"></div>
                    </div>
                    <div class="mb-3">
                        <div class="skeleton skeleton-text w-25 mb-2"></div>
                        <div class="skeleton rounded-3 w-100" style="height: 42px;"></div>
                    </div>
                    <div class="mb-4">
                        <div class="skeleton skeleton-text w-25 mb-2"></div>
                        <div class="skeleton rounded-3 w-100" style="height: 86px;"></div>
                    </div>
                    <div class="skeleton rounded-3 w-100" style="height: 42px;"></div>
                </div>

                <form class="real-wrapper d-none mt-4" action="<?= base_url('student/items/report') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="item_id">Select Missing Item</label>
                        <select class="form-select bg-light" name="item_id" id="item_id" required>
                            <option value="" disabled selected>-- Select an item to report --</option>

                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <?php if ($item['status'] === 'approved' && isset($item['in_campus']) && $item['in_campus'] == 1): ?>
                                        <option value="<?= $item['id'] ?>">
                                            <?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?>
                                            (<?= esc($item['serial_number'] ?? 'N/A') ?>)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No registered items available</option>
                            <?php endif; ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="location">Last Known Location</label>
                        <input type="text" class="form-control bg-light" id="location" name="location" placeholder="e.g., Library 2nd Floor" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="details">Incident Details</label>
                        <textarea class="form-control bg-light" id="details" name="details" rows="3" placeholder="Provide more information..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-2 text-dark fw-bold shadow-sm rounded-3">Submit Report to Guards</button>
                </form>
            </div>
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