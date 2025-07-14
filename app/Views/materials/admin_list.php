<?= view('materials/header_material') ?>
<div class="container mt-4">
    <h1>Admin - Review Materials</h1>
    <a href="<?= base_url('Material/index') ?>" class="btn btn-secondary mb-3">&larr; Back to Materials</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Author</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materials as $mat): ?>
                <tr>
                    <td><?= esc($mat['title']) ?></td>
                    <td><?= esc($mat['type']) ?></td>
                    <td><?= esc($mat['author_id']) ?></td>
                    <td><span class="badge bg-<?= $mat['status'] === 'approved' ? 'success' : ($mat['status'] === 'pending' ? 'warning' : 'danger') ?> text-uppercase"><?= esc($mat['status']) ?></span></td>
                    <td><?= esc($mat['created']) ?></td>
                    <td>
                        <?php if ($mat['status'] === 'pending'): ?>
                            <a href="<?= base_url('Material/approve/' . $mat['id']) ?>" class="btn btn-sm btn-success">Approve</a>
                            <a href="<?= base_url('Material/reject/' . $mat['id']) ?>" class="btn btn-sm btn-danger">Reject</a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= view('materials/footer_material') ?> 