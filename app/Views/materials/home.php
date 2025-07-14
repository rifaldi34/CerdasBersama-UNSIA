<?= view('materials/header_material') ?>
<div class="container mt-4">

    <!-- Tombol Ajukan Materi Baru (hanya terlihat jika sudah login) -->
    <a href="<?= base_url('Material/add') ?>"
       class="btn btn-success mb-3 <?= $isLoggedIn ? '' : 'd-none' ?>">
        Ajukan Materi Baru
    </a>

    <?php
    // Siapkan array seksi: judul => data
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
                        <div class="card h-100">
                            <?php if (! empty($mat['thumbnail_path'])): ?>
                                <img src="<?= base_url('public/' . $mat['thumbnail_path']) ?>"
                                     class="card-img-top"
                                     alt="Thumbnail">
                            <?php endif; ?>

                            <div class="card-body shadow">
                                <h5 class="card-title"><?= esc($mat['title']) ?></h5>
                                <p class="card-text">
                                    <?= esc(substr($mat['article_content'], 0, 100)) ?>...
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <span class="badge bg-light text-dark">
                                            Oleh: <?= esc($mat['irl_name']) ?>
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            File: <?= count($mat['files']) ?>
                                        </span>
                                    </small>
                                </p>
                                <a href="<?= base_url('Material/view?id=' . $mat['id']) ?>"
                                   class="btn btn-primary btn-sm">
                                    Lihat
                                </a>
                                <?php if ($mat['author_id'] == $session_user_id): ?>
                                    <a href="<?= base_url('Material/edit?id=' . $mat['id']) ?>" class="btn btn-warning ms-2 btn-sm">Edit</a>
                                <?php endif; ?>
                                <?php if ($mat['author_id'] == $session_user_id || $canChangeStatus): ?>
                                    <form method="post" action="<?= base_url('Material/delete') ?>" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus materi ini?');">
                                        <input type="hidden" name="id" value="<?= esc($mat['id']) ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger ms-2 btn-sm">Delete</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($canChangeStatus && in_array($mat['status'], ['pending', 'rejected'])): ?>
                                    <form method="post" action="<?= base_url('Material/changeStatus') ?>" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?= esc($mat['id']) ?>">
                                        <input type="hidden" name="status" value="approved">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-success ms-2 btn-sm" onclick="return confirm('Approve materi ini?');">Approve</button>
                                    </form>
                                    <?php if ($mat['status'] !== 'rejected'): ?>
                                    <form method="post" action="<?= base_url('Material/changeStatus') ?>" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?= esc($mat['id']) ?>">
                                        <input type="hidden" name="status" value="rejected">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-danger ms-2 btn-sm" onclick="return confirm('Reject materi ini?');">Reject</button>
                                    </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

</div>
<?= view('materials/footer_material') ?>
