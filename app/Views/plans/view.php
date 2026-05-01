<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-2xl font-bold mb-4"><?= esc($plan['title']) ?></h3>
    <p class="text-gray-600 dark:text-gray-400 mb-2">📅 <?= date('d F Y', strtotime($plan['date'])) ?></p>
    <div class="mb-4">
        <strong>Deskripsi:</strong><br>
        <p class="whitespace-pre-wrap"><?= nl2br(esc((string)($plan['description'] ?? ''))) ?></p>
    </div>
    <div class="mb-4">
        <strong>Status:</strong>
        <?php if($plan['is_completed']): ?>
            <span class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 text-sm px-2 py-1 rounded">✓ Selesai</span>
        <?php else: ?>
            <span class="bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 text-sm px-2 py-1 rounded">⏳ Belum Selesai</span>
        <?php endif; ?>
    </div>
    <div class="flex gap-2 mt-4">
        <a href="<?= base_url('/plans') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Kembali</a>
        <a href="<?= base_url('/plans/edit/'.$plan['id']) ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">✏️ Edit</a>
        <?php if(!$plan['is_completed']): ?>
            <a href="<?= base_url('/plans/complete/'.$plan['id']) ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md" onclick="return confirm('Tandai selesai?')">✓ Selesai</a>
        <?php else: ?>
            <a href="<?= base_url('/plans/uncomplete/'.$plan['id']) ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md" onclick="return confirm('Batalkan selesai?')">↺ Batal</a>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>