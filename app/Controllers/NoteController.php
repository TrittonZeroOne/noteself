<?php

namespace App\Controllers;

use App\Models\NoteModel;

class NoteController extends BaseController
{
    protected $helpers = ['form', 'url', 'text'];

    public function index()
    {
        $model = new NoteModel();
        $userId = session()->get('id');
        
        $search = $this->request->getVar('search');
        if ($search) {
            $model->groupStart()
                  ->like('title', $search)
                  ->orLike('description', $search)
                  ->groupEnd();
        }
        
        $sort = $this->request->getVar('sort');
        if ($sort == 'date_asc') {
            $model->orderBy('date', 'ASC');
        } elseif ($sort == 'date_desc') {
            $model->orderBy('date', 'DESC');
        } else {
            $model->orderBy('created_at', 'DESC');
        }
        
        $data['notes'] = $model->where('user_id', $userId)->paginate(10);
        $data['pager'] = $model->pager;
        $data['search'] = $search;
        $data['sort'] = $sort;
        
        return view('notes/index', $data);
    }

    public function create()
    {
        return view('notes/create');
    }

    public function store()
    {
        $rules = [
            'title' => 'required|min_length[3]',
            'date'  => 'required|valid_date',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $model = new NoteModel();
        
        // Upload multiple photos
        $photoFiles = $this->request->getFileMultiple('photos');
        $photoNames = [];
        if ($photoFiles) {
            foreach ($photoFiles as $file) {
                if ($file->isValid() && !$file->hasMoved() && $file->getSize() <= 2097152 && in_array($file->getMimeType(), ['image/jpeg','image/png','image/jpg'])) {
                    $newName = $file->getRandomName();
                    $file->move('uploads', $newName);
                    $photoNames[] = $newName;
                }
            }
        }
        
        // Upload multiple videos
        $videoFiles = $this->request->getFileMultiple('videos');
        $videoNames = [];
        if ($videoFiles) {
            foreach ($videoFiles as $file) {
                if ($file->isValid() && !$file->hasMoved() && $file->getSize() <= 10485760 && $file->getMimeType() == 'video/mp4') {
                    $newName = $file->getRandomName();
                    $file->move('uploads', $newName);
                    $videoNames[] = $newName;
                }
            }
        }
        
        $model->save([
            'user_id' => session()->get('id'),
            'title' => $this->request->getVar('title'),
            'description' => $this->request->getVar('description'),
            'photo' => json_encode($photoNames),
            'video' => json_encode($videoNames),
            'date' => $this->request->getVar('date')
        ]);
        
        session()->setFlashdata('success', 'Catatan berhasil ditambahkan.');
        return redirect()->to('/notes');
    }

    public function edit($id)
    {
        $model = new NoteModel();
        $note = $model->find($id);
        if (!$note || $note['user_id'] != session()->get('id')) {
            return redirect()->to('/notes')->with('error', 'Catatan tidak ditemukan.');
        }
        return view('notes/edit', ['note' => $note]);
    }

    public function update($id)
    {
        $rules = [
            'title' => 'required|min_length[3]',
            'date'  => 'required|valid_date',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $model = new NoteModel();
        $note = $model->find($id);
        if (!$note || $note['user_id'] != session()->get('id')) {
            return redirect()->to('/notes')->with('error', 'Catatan tidak ditemukan.');
        }
        
        // Decode JSON dengan aman
        $oldPhotos = [];
        if (!empty($note['photo'])) {
            $decoded = json_decode((string)$note['photo'], true);
            $oldPhotos = is_array($decoded) ? $decoded : [];
        }
        $oldVideos = [];
        if (!empty($note['video'])) {
            $decoded = json_decode((string)$note['video'], true);
            $oldVideos = is_array($decoded) ? $decoded : [];
        }
        
        // Hapus file yang dicentang
        $deletePhotos = $this->request->getPost('delete_photos') ?? [];
        $deleteVideos = $this->request->getPost('delete_videos') ?? [];
        
        foreach ($deletePhotos as $photo) {
            if (in_array($photo, $oldPhotos)) {
                if (file_exists('uploads/' . $photo)) unlink('uploads/' . $photo);
                $oldPhotos = array_diff($oldPhotos, [$photo]);
            }
        }
        foreach ($deleteVideos as $video) {
            if (in_array($video, $oldVideos)) {
                if (file_exists('uploads/' . $video)) unlink('uploads/' . $video);
                $oldVideos = array_diff($oldVideos, [$video]);
            }
        }
        
        // Upload file baru (multiple)
        $newPhotoFiles = $this->request->getFileMultiple('new_photos');
        if ($newPhotoFiles) {
            foreach ($newPhotoFiles as $file) {
                if ($file->isValid() && !$file->hasMoved() && $file->getSize() <= 2097152 && in_array($file->getMimeType(), ['image/jpeg','image/png','image/jpg'])) {
                    $newName = $file->getRandomName();
                    $file->move('uploads', $newName);
                    $oldPhotos[] = $newName;
                }
            }
        }
        
        $newVideoFiles = $this->request->getFileMultiple('new_videos');
        if ($newVideoFiles) {
            foreach ($newVideoFiles as $file) {
                if ($file->isValid() && !$file->hasMoved() && $file->getSize() <= 10485760 && $file->getMimeType() == 'video/mp4') {
                    $newName = $file->getRandomName();
                    $file->move('uploads', $newName);
                    $oldVideos[] = $newName;
                }
            }
        }
        
        $data = [
            'title' => $this->request->getVar('title'),
            'description' => $this->request->getVar('description'),
            'date' => $this->request->getVar('date'),
            'photo' => json_encode(array_values($oldPhotos)),
            'video' => json_encode(array_values($oldVideos))
        ];
        
        $model->update($id, $data);
        session()->setFlashdata('success', 'Catatan berhasil diupdate.');
        return redirect()->to('/notes');
    }

    public function delete($id)
    {
        $model = new NoteModel();
        $note = $model->find($id);
        if ($note && $note['user_id'] == session()->get('id')) {
            // Hapus file foto
            $photos = [];
            if (!empty($note['photo'])) {
                $decoded = json_decode((string)$note['photo'], true);
                $photos = is_array($decoded) ? $decoded : [];
            }
            foreach ($photos as $photo) {
                if (file_exists('uploads/' . $photo)) unlink('uploads/' . $photo);
            }
            // Hapus file video
            $videos = [];
            if (!empty($note['video'])) {
                $decoded = json_decode((string)$note['video'], true);
                $videos = is_array($decoded) ? $decoded : [];
            }
            foreach ($videos as $video) {
                if (file_exists('uploads/' . $video)) unlink('uploads/' . $video);
            }
            $model->delete($id);
            
            // Reset auto_increment jika tidak ada data lagi
            $remaining = $model->where('user_id', session()->get('id'))->countAllResults();
            if ($remaining == 0) {
                $db = \Config\Database::connect();
                $db->query("ALTER TABLE notes AUTO_INCREMENT = 1");
            }
            
            session()->setFlashdata('success', 'Catatan dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus catatan.');
        }
        return redirect()->to('/notes');
    }
    
    public function view($id)
    {
        $model = new NoteModel();
        $note = $model->find($id);
        if (!$note || $note['user_id'] != session()->get('id')) {
            return redirect()->to('/notes')->with('error', 'Catatan tidak ditemukan.');
        }
        $note['photo_array'] = [];
        if (!empty($note['photo'])) {
            $decoded = json_decode((string)$note['photo'], true);
            $note['photo_array'] = is_array($decoded) ? $decoded : [];
        }
        $note['video_array'] = [];
        if (!empty($note['video'])) {
            $decoded = json_decode((string)$note['video'], true);
            $note['video_array'] = is_array($decoded) ? $decoded : [];
        }
        return view('notes/view', ['note' => $note]);
    }

    public function export()
    {
        $model = new NoteModel();
        $userId = session()->get('id');
        $notes = $model->where('user_id', $userId)->orderBy('date', 'DESC')->findAll();
    
        $filename = 'catatan_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');
        fwrite($output, "\xEF\xBB\xBF");
        fputcsv($output, ['No', 'Judul', 'Deskripsi', 'Tanggal', 'Dibuat Pada']);
    
        $no = 1;
        foreach ($notes as $note) {
            $description = str_replace(["\r\n", "\n", "\r", "\t"], ' ', $note['description'] ?? '');
            fputcsv($output, [
                $no++,
                $note['title'],
                $description,
                date('Y-m-d', strtotime($note['date'])),
                date('Y-m-d H:i:s', strtotime($note['created_at']))
            ]);
        }
    
        fclose($output);
        exit();
    }
}