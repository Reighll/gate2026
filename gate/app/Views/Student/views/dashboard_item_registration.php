<?= $this->extend('Student/layout/main') ?>
<?= $this->section('title') ?>Item Registration | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="pt-5 mt-4">
        <h4 class="fw-semibold mb-3">Item Registration</h4>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger p-3 rounded-3 shadow-sm"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success p-3 rounded-3 shadow-sm"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="alert alert-info d-flex align-items-center">
                    <i class="ti ti-info-circle fs-5 me-2"></i>
                    Register your personal equipment to bring inside the campus.
                </div>

                <form action="<?= base_url('student/items/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Item Category</label>
                        <select class="form-select" name="category" required>
                            <option value="" disabled <?= empty(old('category')) ? 'selected' : '' ?>>Select a category...</option>
                            <option value="Personal Computing & Mobile" <?= old('category') == 'Personal Computing & Mobile' ? 'selected' : '' ?>>Personal Computing & Mobile</option>
                            <option value="Photography & Videography" <?= old('category') == 'Photography & Videography' ? 'selected' : '' ?>>Photography & Videography</option>
                            <option value="Audio & Music Equipment" <?= old('category') == 'Audio & Music Equipment' ? 'selected' : '' ?>>Audio & Music Equipment</option>
                            <option value="Technical & Engineering Gear" <?= old('category') == 'Technical & Engineering Gear' ? 'selected' : '' ?>>Technical & Engineering Gear</option>
                            <option value="Art & Design Supplies" <?= old('category') == 'Art & Design Supplies' ? 'selected' : '' ?>>Art & Design Supplies</option>
                            <option value="Sporting & Fitness Equipment" <?= old('category') == 'Sporting & Fitness Equipment' ? 'selected' : '' ?>>Sporting & Fitness Equipment</option>
                            <option value="Large Portable Storage" <?= old('category') == 'Large Portable Storage' ? 'selected' : '' ?>>Large Portable Storage</option>
                            <option value="Bulky/Household Items" <?= old('category') == 'Bulky/Household Items' ? 'selected' : '' ?>>Bulky/Household Items</option>
                            <option value="Personal Mobility Devices" <?= old('category') == 'Personal Mobility Devices' ? 'selected' : '' ?>>Personal Mobility Devices</option>
                            <option value="Administrative/Office Use" <?= old('category') == 'Administrative/Office Use' ? 'selected' : '' ?>>Administrative/Office Use</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Brand & Model</label>
                        <input type="text" class="form-control" name="brand_model" value="<?= old('brand_model') ?>" placeholder="e.g., Acer Predator Helios 300" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Serial Number / Unique Identifier</label>
                        <input type="text" class="form-control" name="serial_number" value="<?= old('serial_number') ?>" placeholder="Required for verification" required>
                        <div class="form-text">Found on the bottom of laptops or back of devices.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Item Photo</label>
                        <input class="form-control" type="file" name="photo" accept="image/*" required>
                        <div class="form-text text-muted">Max file size: 2MB. Clear photo of the item.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5 fw-bold shadow-sm">Submit Registration</button>
                </form>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>