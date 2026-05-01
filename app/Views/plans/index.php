<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="flex justify-between items-center flex-wrap gap-4 mb-6">
    <h2 class="text-2xl font-bold">📅 Rencana Saya</h2>
    <div class="flex gap-2">
        <a href="<?= base_url('/plans/export') ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">📎 Export CSV</a>
        <a href="<?= base_url('/plans/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">+ Tambah Rencana</a>
    </div>
</div>

<div class="flex gap-2 mb-6">
    <a href="<?= base_url('/plans') ?>" class="px-4 py-2 rounded-md <?= ($status == '') ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' ?>">Semua</a>
    <a href="<?= base_url('/plans?status=pending') ?>" class="px-4 py-2 rounded-md <?= ($status == 'pending') ? 'bg-yellow-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' ?>">Belum Selesai</a>
    <a href="<?= base_url('/plans?status=completed') ?>" class="px-4 py-2 rounded-md <?= ($status == 'completed') ? 'bg-green-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' ?>">Selesai</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($plans as $plan): ?>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden <?= $plan['is_completed'] ? 'border-l-4 border-green-500' : '' ?>">
        <div class="p-4">
            <div class="flex justify-between items-start">
                <h3 class="text-xl font-semibold"><?= esc($plan['title']) ?></h3>
                <?php if($plan['is_completed']): ?>
                    <span class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 text-xs px-2 py-1 rounded-full">Selesai</span>
                <?php else: ?>
                    <span class="bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 text-xs px-2 py-1 rounded-full">Belum</span>
                <?php endif; ?>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">📅 <?= date('d M Y', strtotime($plan['date'])) ?></p>
            <p class="mt-2"><?= character_limiter(esc((string)($plan['description'] ?? '')), 100) ?></p>
            <div class="flex flex-wrap gap-2 mt-3">
                <a href="<?= base_url('/plans/view/'.$plan['id']) ?>" class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded-md inline-flex items-center gap-1">👁️ Detail</a>
                <?php if(!$plan['is_completed']): ?>
                    <a href="<?= base_url('/plans/complete/'.$plan['id']) ?>" class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1 rounded-md inline-flex items-center gap-1" onclick="return confirm('Tandai selesai?')">✓ Selesai</a>
                <?php else: ?>
                    <a href="<?= base_url('/plans/uncomplete/'.$plan['id']) ?>" class="bg-gray-500 hover:bg-gray-600 text-white text-sm px-3 py-1 rounded-md inline-flex items-center gap-1" onclick="return confirm('Batalkan selesai?')">↺ Batal</a>
                <?php endif; ?>
                <a href="<?= base_url('/plans/edit/'.$plan['id']) ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-3 py-1 rounded-md inline-flex items-center gap-1">✏️ Edit</a>
                <a href="<?= base_url('/plans/delete/'.$plan['id']) ?>" class="bg-red-600 hover:bg-red-700 text-white text-sm px-3 py-1 rounded-md inline-flex items-center gap-1" onclick="return confirm('Yakin hapus?')">🗑️ Hapus</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div class="mt-6">
    <?= $pager->links() ?>
</div>
<?= $this->endSection() ?>