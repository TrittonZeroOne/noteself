<?php

namespace App\Controllers;

use App\Models\PlanModel;

class PlanController extends BaseController
{
    protected $helpers = ['form', 'url', 'text']; // text helper untuk character_limiter

     public function index()
    {
        $model = new PlanModel();
        $userId = session()->get('id');
        
        // Ambil parameter filter status dari query string
        $status = $this->request->getVar('status');
        if ($status === 'completed') {
            $model->where('is_completed', 1);
        } elseif ($status === 'pending') {
            $model->where('is_completed', 0);
        } else {
            // default: tampilkan semua, tapi urutkan yang pending dulu
            $model->orderBy('is_completed', 'ASC');
        }
        
        $data['plans'] = $model->where('user_id', $userId)->orderBy('date', 'ASC')->paginate(10);
        $data['pager'] = $model->pager;
        $data['status'] = $status; // untuk mempertahankan filter di view
        
        return view('plans/index', $data);
    }
    public function view($id)
    {
        $model = new PlanModel();
        $plan = $model->find($id);
        if (!$plan || $plan['user_id'] != session()->get('id')) {
            return redirect()->to('/plans')->with('error', 'Rencana tidak ditemukan.');
        }
        return view('plans/view', ['plan' => $plan]);
    }
    public function complete($id)
    {
        $model = new PlanModel();
        $plan = $model->find($id);
        if ($plan && $plan['user_id'] == session()->get('id')) {
            $model->update($id, ['is_completed' => 1]);
            session()->setFlashdata('success', 'Rencana telah selesai!');
        } else {
            session()->setFlashdata('error', 'Rencana tidak ditemukan.');
        }
        return redirect()->to('/plans');
    }
    public function uncomplete($id)
    {
        $model = new PlanModel();
        $plan = $model->find($id);
        if ($plan && $plan['user_id'] == session()->get('id')) {
            $model->update($id, ['is_completed' => 0]);
            session()->setFlashdata('success', 'Rencana dikembalikan ke status belum selesai.');
        } else {
            session()->setFlashdata('error', 'Rencana tidak ditemukan.');
        }
        return redirect()->to('/plans');
    }

    public function create()
    {
        return view('plans/create');
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
        
        $model = new PlanModel();
        $model->save([
            'user_id' => session()->get('id'),
            'title' => $this->request->getVar('title'),
            'description' => $this->request->getVar('description'),
            'date' => $this->request->getVar('date')
        ]);
        
        session()->setFlashdata('success', 'Rencana berhasil ditambahkan.');
        return redirect()->to('/plans');
    }

    public function edit($id)
    {
        $model = new PlanModel();
        $plan = $model->find($id);
        if (!$plan || $plan['user_id'] != session()->get('id')) {
            return redirect()->to('/plans')->with('error', 'Rencana tidak ditemukan.');
        }
        return view('plans/edit', ['plan' => $plan]);
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
        
        $model = new PlanModel();
        $plan = $model->find($id);
        if (!$plan || $plan['user_id'] != session()->get('id')) {
            return redirect()->to('/plans')->with('error', 'Rencana tidak ditemukan.');
        }
        
        $model->update($id, [
            'title' => $this->request->getVar('title'),
            'description' => $this->request->getVar('description'),
            'date' => $this->request->getVar('date')
        ]);
        
        session()->setFlashdata('success', 'Rencana berhasil diupdate.');
        return redirect()->to('/plans');
    }

    public function delete($id)
    {
        $model = new PlanModel();
        $plan = $model->find($id);
        if ($plan && $plan['user_id'] == session()->get('id')) {
            $model->delete($id);
            
            // Reset auto_increment jika tidak ada data lagi
            $remaining = $model->where('user_id', session()->get('id'))->countAllResults();
            if ($remaining == 0) {
                $db = \Config\Database::connect();
                $db->query("ALTER TABLE plans AUTO_INCREMENT = 1");
            }
            
            session()->setFlashdata('success', 'Rencana dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus rencana.');
        }
        return redirect()->to('/plans');
    }
    public function export()
    {
        $model = new PlanModel();
        $userId = session()->get('id');
        $plans = $model->where('user_id', $userId)->orderBy('date', 'ASC')->findAll();
    
        $filename = 'rencana_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');
        fwrite($output, "\xEF\xBB\xBF");
        fputcsv($output, ['No', 'Judul', 'Deskripsi', 'Tanggal', 'Status', 'Dibuat Pada']);
    
        $no = 1;
        foreach ($plans as $plan) {
            $description = str_replace(["\r\n", "\n", "\r", "\t"], ' ', $plan['description'] ?? '');
            $status = $plan['is_completed'] ? 'Selesai' : 'Belum Selesai';
            fputcsv($output, [
                $no++,
                $plan['title'],
                $description,
                date('Y-m-d', strtotime($plan['date'])),
                $status,
                date('Y-m-d H:i:s', strtotime($plan['created_at']))
            ]);
        }
    
        fclose($output);
        exit();
    }
}