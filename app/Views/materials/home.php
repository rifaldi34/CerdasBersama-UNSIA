<?= view('materials/header_material') ?>
<div class="container mt-4">

    <!-- Tombol Ajukan Materi Baru (hanya terlihat jika sudah login) -->
    <a href="<?= base_url('Material/add') ?>"
        class="btn btn-success mb-4 <?= $isLoggedIn ? '' : 'd-none' ?>">
        <i class="bi bi-plus-circle"></i> Ajukan Materi Baru
    </a>

    <?php
    if ($isLoggedIn) {
        $sections = [
            'Materi Pending' => $materials_pending,
            'Semua Materi'   => $materials,
            'Materi Ditolak' => $materials_rejected,
        ];
    }else{
        $sections = [
            'Semua Materi' => $materials,
        ];
    }
    ?>

    <?php foreach ($sections as $sectionTitle => $sectionData): ?>
        <h2><?= esc($sectionTitle) ?></h2>
        <div class="row">
            <?php if (empty($sectionData)): ?>
                <div class="col-12">
                    <p class="text-muted">Belum ada materi di bagian ini.</p>
                </div>
            <?php else: ?>
                <?php foreach ($sectionData as $mat): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <?php if (! empty($mat['thumbnail_path'])): ?>
                                <img src="<?= base_url('public/' . $mat['thumbnail_path']) ?>"
                                    class="card-img-top rounded-top"
                                    alt="Thumbnail">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title fw-bold"><?= esc($mat['title']) ?></h5>
                                <p class="card-text text-muted small">
                                <?= esc(substr($mat['article_content'], 0, 100)) ?>...
                                </p>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="badge bg-secondary-subtle text-dark rounded-pill px-3">
                                        Oleh: <?= esc($mat['irl_name']) ?>
                                    </span>
                                    <span class="badge bg-info-subtle text-dark rounded-pill px-3">
                                        File: <?= count($mat['files']) ?>
                                    </span>
                                </div>
                                
                                <div class="card-actions">
                                    <a href="<?= base_url('Material/view?id=' . $mat['id']) ?>"
                                        class="btn btn-outline-view">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>

                                    <?php if ($mat['author_id'] == $session_user_id): ?>
                                        <a href="<?= base_url('Material/edit?id=' . $mat['id']) ?>"
                                        class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($mat['author_id'] == $session_user_id || $canChangeStatus): ?>
                                        <form method="post" action="<?= base_url('Material/delete') ?>" onsubmit="return confirm('Yakin ingin menghapus materi ini?');">
                                        <input type="hidden" name="id" value="<?= esc($mat['id']) ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($canChangeStatus && in_array($mat['status'], ['pending', 'rejected'])): ?>
                                        <form method="post" action="<?= base_url('Material/changeStatus') ?>">
                                        <input type="hidden" name="id" value="<?= esc($mat['id']) ?>">
                                        <input type="hidden" name="status" value="approved">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-approve">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                        </form>

                                        <?php if ($mat['status'] !== 'rejected'): ?>
                                        <form method="post" action="<?= base_url('Material/changeStatus') ?>">
                                            <input type="hidden" name="id" value="<?= esc($mat['id']) ?>">
                                            <input type="hidden" name="status" value="rejected">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-reject">
                                            <i class="bi bi-x-circle"></i> Reject
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

</div>
<?= view('materials/footer_material') ?>
