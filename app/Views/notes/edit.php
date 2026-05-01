<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <h3 class="text-2xl font-bold mb-6">Edit Catatan</h3>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="<?= base_url('/notes/update/'.$note['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Judul *</label>
            <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="<?= old('title', $note['title']) ?>" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md"><?= old('description', $note['description']) ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Tanggal *</label>
            <input type="date" name="date" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="<?= old('date', $note['date']) ?>" required>
        </div>

        <?php 
            $photos = json_decode($note['photo'], true) ?: [];
            $videos = json_decode($note['video'], true) ?: [];
        ?>
        <?php if(!empty($photos)): ?>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Foto saat ini (centang untuk hapus)</label>
            <?php foreach($photos as $photo): ?>
            <div class="flex items-center mb-2">
                <input type="checkbox" name="delete_photos[]" value="<?= esc($photo) ?>" id="del_<?= esc($photo) ?>" class="mr-2">
                <label for="del_<?= esc($photo) ?>" class="flex items-center">
                    <img src="<?= base_url('uploads/'.$photo) ?>" class="w-16 h-16 object-cover rounded mr-2">
                    <span class="text-sm"><?= esc($photo) ?></span>
                </label>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Tambah foto baru (bisa multiple)</label>
            <input type="file" name="new_photos[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-md" accept="image/*">
        </div>

        <?php if(!empty($videos)): ?>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Video saat ini (centang untuk hapus)</label>
            <?php foreach($videos as $video): ?>
            <div class="flex items-center mb-2">
                <input type="checkbox" name="delete_videos[]" value="<?= esc($video) ?>" id="del_vid_<?= esc($video) ?>" class="mr-2">
                <label for="del_vid_<?= esc($video) ?>" class="flex items-center">
                    <video width="120" controls class="mr-2"><source src="<?= base_url('uploads/'.$video) ?>"></video>
                    <span class="text-sm"><?= esc($video) ?></span>
                </label>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Tambah video baru (bisa multiple)</label>
            <input type="file" name="new_videos[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-md" accept="video/mp4">
        </div>

        <div class="flex justify-between">
            <a href="<?= base_url('/notes') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Update</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>