<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-blue-600 rounded-lg shadow p-6 text-white">
        <h5 class="text-lg font-semibold">Total Catatan</h5>
        <p class="text-4xl font-bold mt-2"><?= $notes_count ?></p>
        <a href="<?= base_url('/notes') ?>" class="inline-block mt-4 bg-white text-blue-600 px-4 py-2 rounded-md hover:bg-gray-100 font-medium">Lihat semua →</a>
    </div>
    <div class="bg-green-600 rounded-lg shadow p-6 text-white">
        <h5 class="text-lg font-semibold">Rencana Belum Selesai</h5>
        <p class="text-4xl font-bold mt-2"><?= $plans_count ?></p>
        <a href="<?= base_url('/plans') ?>" class="inline-block mt-4 bg-white text-green-600 px-4 py-2 rounded-md hover:bg-gray-100 font-medium">Lihat semua →</a>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6 mt-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h4 class="text-lg font-semibold border-b pb-2 mb-4">Catatan Terbaru</h4>
        <div class="space-y-2">
            <?php foreach($recent_notes as $note): ?>
            <div><strong><?= esc($note['title']) ?></strong> - <?= $note['date'] ?></div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h4 class="text-lg font-semibold border-b pb-2 mb-4">Rencana Belum Selesai (Terbaru)</h4>
        <div class="space-y-2">
            <?php foreach($recent_plans as $plan): ?>
            <div><strong><?= esc($plan['title']) ?></strong> - <?= $plan['date'] ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>