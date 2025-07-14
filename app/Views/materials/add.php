<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Materi Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1><?= isset($material) ? 'Edit Materi' : 'Tambah Materi Baru' ?></h1>
        <div class="alert alert-danger" id="error-alert" style="display:none;">
            <ul class="mb-0" id="error-list"></ul>
        </div>
        
        <form method="post" action="<?= isset($material) ? base_url('Material/update') : base_url('Material/create') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <?php if (isset($material)): ?>
                <input type="hidden" name="id" value="<?= esc($material['id']) ?>">
            <?php endif; ?>
            
            <div class="mb-3">
                <label for="title" class="form-label">Judul</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= esc($old['title'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= esc($old['description'] ?? '') ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail (Gambar)</label>
                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                <small class="form-text text-muted">Upload gambar untuk thumbnail materi</small>
                <?php if (!empty($old['thumbnail_path'])): ?>
                    <div class="mt-2">
                        <img src="<?= base_url($old['thumbnail_path']) ?>" alt="Thumbnail" style="max-width:150px;">
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Konten Materi</label>
                <div id="content-container">
                    <?php if (!empty($old['files'])): ?>
                        <?php foreach ($old['files'] as $idx => $file): ?>
                            <div class="content-item border p-3 mb-3" data-id="<?= $idx ?>">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Tipe Konten</label>
                                        <select class="form-select content-type" name="files[<?= $idx ?>][content_type]">
                                            <option value="">Pilih tipe konten</option>
                                            <option value="file" <?= $file['content_type'] === 'file' ? 'selected' : '' ?>>File Upload</option>
                                            <option value="link" <?= $file['content_type'] === 'link' ? 'selected' : '' ?>>Link/URL</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kategori</label>
                                        <select class="form-select content-category" name="files[<?= $idx ?>][content_category]">
                                            <option value="">Pilih kategori</option>
                                            <option value="article" <?= $file['content_category'] === 'article' ? 'selected' : '' ?>>Artikel</option>
                                            <option value="image" <?= $file['content_category'] === 'image' ? 'selected' : '' ?>>Gambar</option>
                                            <option value="ebook" <?= $file['content_category'] === 'ebook' ? 'selected' : '' ?>>E-book (PDF)</option>
                                            <option value="audio" <?= $file['content_category'] === 'audio' ? 'selected' : '' ?>>Audio</option>
                                            <option value="video" <?= $file['content_category'] === 'video' ? 'selected' : '' ?>>Video</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="file-input-group" style="<?= $file['content_type'] === 'file' ? '' : 'display:none;' ?>">
                                    <label class="form-label">Upload File</label>
                                    <input type="file" class="form-control" name="files[<?= $idx ?>][file]">
                                    <?php if (!empty($file['file_path'])): ?>
                                        <div class="mt-2">
                                            <a href="<?= base_url($file['file_path']) ?>" target="_blank">File saat ini: <?= esc($file['file_name']) ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="link-input-group" style="<?= $file['content_type'] === 'link' ? '' : 'display:none;' ?>">
                                    <label class="form-label">URL/Link</label>
                                    <input type="url" class="form-control" name="files[<?= $idx ?>][link]" value="<?= esc($file['link_url'] ?? '') ?>" placeholder="https://example.com atau https://youtube.com/watch?v=...">
                                    <small class="form-text text-muted">Masukkan URL lengkap (contoh: YouTube, Google Drive, dll)</small>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-content" <?= count($old['files']) <= 1 ? 'style="display:none;"' : '' ?>>
                                        Hapus Konten
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="content-item border p-3 mb-3" data-id="1">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tipe Konten</label>
                                    <select class="form-select content-type" name="files[1][content_type]">
                                        <option value="">Pilih tipe konten</option>
                                        <option value="file">File Upload</option>
                                        <option value="link">Link/URL</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kategori</label>
                                    <select class="form-select content-category" name="files[1][content_category]">
                                        <option value="">Pilih kategori</option>
                                        <option value="article">Artikel</option>
                                        <option value="image">Gambar</option>
                                        <option value="ebook">E-book (PDF)</option>
                                        <option value="audio">Audio</option>
                                        <option value="video">Video</option>
                                    </select>
                                </div>
                            </div>
                            <div class="file-input-group" style="display:none;">
                                <label class="form-label">Upload File</label>
                                <input type="file" class="form-control" name="files[1][file]">
                            </div>
                            <div class="link-input-group" style="display:none;">
                                <label class="form-label">URL/Link</label>
                                <input type="url" class="form-control" name="files[1][link]" placeholder="https://example.com atau https://youtube.com/watch?v=...">
                                <small class="form-text text-muted">Masukkan URL lengkap (contoh: YouTube, Google Drive, dll)</small>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger btn-sm remove-content" style="display:none;">
                                    Hapus Konten
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <button type="button" class="btn btn-success" id="add-content">
                    + Tambah Konten
                </button>
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-primary"><?= isset($material) ? 'Update' : 'Kirim' ?></button>
                <a href="<?= base_url('Material') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    <script>
        $(function() {
            let contentCounter = 1;
            
            // Function to toggle input fields based on content type
            function toggleContentFields(contentItem) {
                const type = contentItem.find('.content-type').val();
                const fileGroup = contentItem.find('.file-input-group');
                const linkGroup = contentItem.find('.link-input-group');
                
                if (type === 'file') {
                    fileGroup.show();
                    linkGroup.hide();
                    // Clear link input
                    linkGroup.find('input').val('');
                } else if (type === 'link') {
                    linkGroup.show();
                    fileGroup.hide();
                    // Clear file input
                    fileGroup.find('input').val('');
                } else {
                    fileGroup.hide();
                    linkGroup.hide();
                }
            }
            
            // Function to update remove button visibility
            function updateRemoveButtons() {
                const contentItems = $('.content-item');
                if (contentItems.length > 1) {
                    $('.remove-content').show();
                } else {
                    $('.remove-content').hide();
                }
            }
            
            // Function to set file accept attribute based on category
            function updateFileAccept(contentItem) {
                const category = contentItem.find('.content-category').val();
                const fileInput = contentItem.find('input[type="file"]');
                
                switch(category) {
                    case 'image':
                        fileInput.attr('accept', 'image/*');
                        break;
                    case 'ebook':
                        fileInput.attr('accept', '.pdf');
                        break;
                    case 'audio':
                        fileInput.attr('accept', 'audio/*');
                        break;
                    case 'video':
                        fileInput.attr('accept', 'video/*');
                        break;
                    default:
                        fileInput.removeAttr('accept');
                        break;
                }
            }
            
            // Event handler for content type change
            $(document).on('change', '.content-type', function() {
                const contentItem = $(this).closest('.content-item');
                toggleContentFields(contentItem);
            });
            
            // Event handler for content category change
            $(document).on('change', '.content-category', function() {
                const contentItem = $(this).closest('.content-item');
                updateFileAccept(contentItem);
            });
            
            // Add new content item
            $('#add-content').click(function() {
                contentCounter++;
                const newContentItem = `
                    <div class="content-item border p-3 mb-3">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tipe Konten</label>
                                <select class="form-select content-type" name="content_type[]" required>
                                    <option value="">Pilih tipe konten</option>
                                    <option value="file">File Upload</option>
                                    <option value="link">Link/URL</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select class="form-select content-category" name="content_category[]" required>
                                    <option value="">Pilih kategori</option>
                                    <option value="article">Artikel</option>
                                    <option value="image">Gambar</option>
                                    <option value="ebook">E-book (PDF)</option>
                                    <option value="audio">Audio</option>
                                    <option value="video">Video</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="file-input-group" style="display:none;">
                            <label class="form-label">Upload File</label>
                            <input type="file" class="form-control" name="files[]">
                        </div>
                        
                        <div class="link-input-group" style="display:none;">
                            <label class="form-label">URL/Link</label>
                            <input type="url" class="form-control" name="links[]" placeholder="https://example.com atau https://youtube.com/watch?v=...">
                            <small class="form-text text-muted">Masukkan URL lengkap (contoh: YouTube, Google Drive, dll)</small>
                        </div>
                        
                        <div class="mt-2">
                            <button type="button" class="btn btn-danger btn-sm remove-content">
                                Hapus Konten
                            </button>
                        </div>
                    </div>
                `;
                
                $('#content-container').append(newContentItem);
                updateRemoveButtons();
            });
            
            // Remove content item
            $(document).on('click', '.remove-content', function() {
                $(this).closest('.content-item').remove();
                updateRemoveButtons();
            });
            
            // Initialize
            updateRemoveButtons();
            
            // Form validation
            // Form validation (konten file/link bersifat opsional)
            $('form').on('submit', function(e) {
                let isValid = true;
                const errors = [];

                $('.content-item').each(function() {
                    const $item = $(this);
                    const type = $item.find('.content-type').val();
                    const category = $item.find('.content-category').val();
                    const fileInput = $item.find('input[type="file"]');
                    const linkInput = $item.find('input[type="url"]');

                    // Jika pengguna TIDAK memilih tipe dan kategori sama sekali, lewati validasi
                    if (!type && !category && !fileInput.val() && !linkInput.val()) {
                        return;
                    }

                    // Jika salah satu field konten diisi, pastikan tipe & kategori dipilih
                    if (!type) {
                        errors.push('Pilih tipe konten jika ingin menambahkan materi tambahan');
                        isValid = false;
                    }
                    if (!category) {
                        errors.push('Pilih kategori konten jika ingin menambahkan materi tambahan');
                        isValid = false;
                    }

                    // Jika tipe “file” maka file wajib diupload
                    if (type === 'file' && !fileInput.val()) {
                        errors.push('File harus diupload untuk tipe konten "File Upload"');
                        isValid = false;
                    }

                    // Jika tipe “link” maka URL wajib diisi
                    if (type === 'link' && !linkInput.val()) {
                        errors.push('URL/Link harus diisi untuk tipe konten "Link/URL"');
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    $('#error-list').empty();
                    errors.forEach(err => $('#error-list').append('<li>' + err + '</li>'));
                    $('#error-alert').show();
                    $('html, body').animate({ scrollTop: 0 }, 500);
                }
            });

        });
    </script>
</body>
</html>