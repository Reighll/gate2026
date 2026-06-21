<?= $this->extend('Student/layout/main') ?>
<?= $this->section('title') ?>Report Item | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="pt-5 mt-4">
        <h4 class="fw-semibold mb-3">Report Lost/Stolen Item</h4>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="alert alert-warning">
                    <i class="ti ti-alert-circle me-2"></i> If your equipment goes missing inside the campus, report it immediately.
                </div>

                <form action="<?= base_url('student/items/report') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label" for="item_id">Select Missing Item</label>
                        <select class="form-select" name="item_id" id="item_id" required>
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
                        <label class="form-label" for="location">Last Known Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="e.g., Library 2nd Floor" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="details">Incident Details</label>
                        <textarea class="form-control" id="details" name="details" rows="3" placeholder="Provide more information..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-2 text-dark fw-bold">Submit Report to Guards</button>
                </form>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>