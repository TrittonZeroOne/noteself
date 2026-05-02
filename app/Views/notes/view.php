<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <h3 class="text-2xl font-bold mb-4"><?= esc($note['title']) ?></h3>
    <p class="text-gray-600 mb-2">📅 <?= date('d F Y', strtotime($note['date'])) ?></p>
    <div class="mb-4">
        <strong>Deskripsi:</strong><br>
        <p class="whitespace-pre-wrap"><?= nl2br(esc((string)($note['description'] ?? ''))) ?></p>
    </div>

    <?php if(!empty($note['photo_array'])): ?>
    <div class="mb-4">
        <strong>Foto:</strong>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
            <?php foreach($note['photo_array'] as $photo): ?>
            <div class="flex flex-col">
                <img src="<?= base_url('uploads/'.$photo) ?>" alt="" class="rounded shadow object-cover h-32 w-full">
                <a href="<?= base_url('notes/download-media/'.$note['id'].'/'.rawurlencode($photo)) ?>" class="text-center text-sm text-blue-600 hover:underline mt-1">Unduh foto</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if(!empty($note['video_array'])): ?>
    <div class="mb-4">
        <strong>Video:</strong>
        <?php foreach($note['video_array'] as $video): ?>
        <div class="mt-2">
            <video controls class="w-full rounded shadow">
                <source src="<?= base_url('uploads/'.$video) ?>" type="video/mp4">
            </video>
            <a href="<?= base_url('notes/download-media/'.$note['id'].'/'.rawurlencode($video)) ?>" class="inline-block text-sm text-blue-600 hover:underline mt-1">Unduh video</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="flex gap-2 mt-4">
        <a href="<?= base_url('/notes') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Kembali</a>
        <a href="<?= base_url('/notes/edit/'.$note['id']) ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">Edit</a>
    </div>
</div>
<?= $this->endSection() ?>
