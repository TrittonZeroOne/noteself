<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="flex justify-between items-center flex-wrap gap-4 mb-6">
    <h2 class="text-2xl font-bold">📝 Catatan Saya</h2>
    <div class="flex gap-2">
        <a href="<?= base_url('/notes/export') ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">📎 Export CSV</a>
        <a href="<?= base_url('/notes/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">+ Tambah Catatan</a>
    </div>
</div>

<form method="get" class="flex flex-wrap gap-2 mb-6">
    <input type="text" name="search" placeholder="Cari judul/deskripsi" value="<?= esc($search ?? '') ?>" class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
    <select name="sort" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
        <option value="">Urutkan</option>
        <option value="date_asc" <?= ($sort ?? '') == 'date_asc' ? 'selected' : '' ?>>Tanggal (lama ke baru)</option>
        <option value="date_desc" <?= ($sort ?? '') == 'date_desc' ? 'selected' : '' ?>>Tanggal (baru ke lama)</option>
    </select>
    <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Filter</button>
</form>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($notes as $note): 
        $photos = json_decode($note['photo'], true) ?: [];
        $firstPhoto = !empty($photos) ? $photos[0] : null;
    ?>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4">
            <h3 class="text-xl font-semibold mb-2"><?= esc($note['title']) ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">📅 <?= date('d M Y', strtotime($note['date'])) ?></p>
            <p class="mb-3"><?= character_limiter(esc((string)($note['description'] ?? '')), 100) ?></p>
            <?php if($firstPhoto): ?>
                <div class="mb-3">
                    <img src="<?= base_url('uploads/' . $firstPhoto) ?>" class="w-24 h-24 object-cover rounded" alt="foto">
                    <?php if(count($photos) > 1): ?>
                        <span class="text-xs bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded-full">+<?= count($photos)-1 ?> foto</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="flex flex-wrap gap-2 mt-2">
                <a href="<?= base_url('/notes/view/'.$note['id']) ?>" class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded-md inline-flex items-center gap-1">👁️ Detail</a>
                <a href="<?= base_url('/notes/edit/'.$note['id']) ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-3 py-1 rounded-md inline-flex items-center gap-1">✏️ Edit</a>
                <a href="<?= base_url('/notes/delete/'.$note['id']) ?>" class="bg-red-600 hover:bg-red-700 text-white text-sm px-3 py-1 rounded-md inline-flex items-center gap-1" onclick="return confirm('Yakin hapus?')">🗑️ Hapus</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div class="mt-6">
    <?= $pager->links() ?>
</div>
<?= $this->endSection() ?>