<?php 

$article_content = $material['article_content'];

if (! $isLoggedIn) {
    // If too long, cut down to 100 characters and append “...”
    if (mb_strlen($article_content) > 100) {
        $article_content = mb_substr($article_content, 0, 100) . '...';
    }
}

?>


<?= view('materials/header_material') ?>

<div class="container mt-4">
    <a href="<?= base_url('Material') ?>" class="btn btn-secondary mb-3">&larr; Kembali ke Materi</a>
    
    <h1><?= esc($material['title']) ?></h1>

    <!-- Side-by-side on desktop, stacked on mobile -->
    <div class="row mb-4 g-3 align-items-start">
        <div class="col-md-4 text-center">
            <img 
                src="<?= base_url('public/' . $material['thumbnail_path']) ?>" 
                class="img-fluid rounded shadow"
                alt="<?= esc($material['title']) ?>"
                style="max-height: 320px; object-fit: cover; width: 100%;"
            />
        </div>
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-body shadow-lg">
                    <?= nl2br($article_content) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- If not logged in, show only the thumbnail -->
    <?php if (! $isLoggedIn): ?>
        <div class="mb-4">
            <div class="alert alert-info mt-2">
                Login untuk melihat semua lampiran.
            </div>
        </div>
    <?php endif; ?>

    <!-- If logged in, list every file as a link -->
    <?php if ($isLoggedIn && ! empty($material['files'])): ?>
        <div class="mb-4">
            <h5>Lampiran:</h5>
            <ul>
                <?php foreach ($material['files'] as $file): ?>
                    <li>
                        <a 
                            href="<?= base_url('public/' . $file['file_path']) ?>" 
                            target="_blank"
                        >
                            <?= esc($file['file_name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <hr>
    <h3>Diskusi</h3>

    <!-- New Comment Form -->
    <?php if ($isLoggedIn): ?>
        <form method="post" action="<?= base_url('Comment/add?id=' . $material['id']) ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <textarea 
                    class="form-control" 
                    name="content" 
                    rows="2" 
                    placeholder="Tambahkan komentar..." 
                    required
                ></textarea>
            </div>
            <input type="hidden" name="parent_id" value="">
            <button type="submit" class="btn btn-primary">Kirim Komentar</button>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Login untuk bergabung dalam diskusi.</div>
    <?php endif; ?>

    <!-- Render Comments Recursively -->
    <div class="mt-4">
        <?php 
        function render_comments($comments, $isLoggedIn, $material_id) {
            foreach ($comments as $comment): ?>
                <div class="border rounded p-2 mb-2 ms-<?= $comment['parent_id'] ? 4 : 0 ?>">
                    <strong>User #<?= esc($comment['irl_name']) ?></strong>
                    <span class="text-muted small"><?= esc($comment['created']) ?></span>
                    <div><?= esc($comment['content']) ?></div>

                    <?php if ($isLoggedIn): ?>
                        <a 
                            href="#" 
                            class="reply-link small" 
                            data-id="<?= $comment['id'] ?>"
                        >
                            Balas
                        </a>
                        <form 
                            method="post" 
                            action="<?= base_url('Comment/add?id=' . $material_id) ?>" 
                            class="reply-form mt-2 d-none" 
                            id="reply-form-<?= $comment['id'] ?>"
                        >
                            <?= csrf_field() ?>
                            <div class="mb-2">
                                <textarea 
                                    class="form-control" 
                                    name="content" 
                                    rows="2" 
                                    placeholder="Balas..." 
                                    required
                                ></textarea>
                            </div>
                            <input 
                                type="hidden" 
                                name="parent_id" 
                                value="<?= $comment['id'] ?>"
                            >
                            <button type="submit" class="btn btn-sm btn-secondary">Kirim Balasan</button>
                        </form>
                    <?php endif; ?>

                    <?php if (! empty($comment['replies'])): ?>
                        <?php render_comments($comment['replies'], $isLoggedIn, $material_id); ?>
                    <?php endif; ?>
                </div>
            <?php endforeach;
        }

        render_comments($comments, $isLoggedIn, $material['id']); 
        ?>
    </div>
</div>

<script>
$(function() {
    $('.reply-link').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#reply-form-' + id).toggleClass('d-none');
    });
});
</script>

<?= view('materials/footer_material') ?>
