<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="flex justify-center items-center min-h-[70vh]">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
        <h3 class="text-2xl font-bold text-center mb-6">Login</h3>
        <form action="<?= base_url('/auth/login') ?>" method="post">
            <div class="mb-4">
                <label class="block mb-1 font-medium">Username</label>
                <input type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-200">Masuk</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>