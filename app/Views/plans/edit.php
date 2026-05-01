<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <h3 class="text-2xl font-bold mb-6">Edit Rencana</h3>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="<?= base_url('/plans/update/'.$plan['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Judul *</label>
            <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="<?= old('title', $plan['title']) ?>" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md"><?= old('description', $plan['description']) ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Tanggal *</label>
            <input type="date" name="date" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="<?= old('date', $plan['date']) ?>" required>
        </div>
        <div class="flex justify-between">
            <a href="<?= base_url('/plans') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Update</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>