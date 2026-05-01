<?php

namespace App\Models;

use CodeIgniter\Model;

class NoteModel extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'title', 'description', 'photo', 'video', 'date'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Helper untuk mengembalikan photo sebagai array
    public function getPhotoArray($note)
    {
        if (empty($note['photo'])) return [];
        $data = json_decode($note['photo'], true);
        return is_array($data) ? $data : ($note['photo'] ? [$note['photo']] : []);
    }

    // Helper untuk video array
    public function getVideoArray($note)
    {
        if (empty($note['video'])) return [];
        $data = json_decode($note['video'], true);
        return is_array($data) ? $data : ($note['video'] ? [$note['video']] : []);
    }
}