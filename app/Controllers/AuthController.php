<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $model = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        
        $user = $model->where('username', $username)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'id' => $user['id'],
                'username' => $user['username'],
                'isLoggedIn' => true
            ]);
            return redirect()->to('/dashboard');
        } else {
            return redirect()->to('/login')->with('error', 'Username atau password salah.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function dashboard()
    {
        $noteModel = new \App\Models\NoteModel();
        $planModel = new \App\Models\PlanModel();
        $userId = session()->get('id');
        
        $data['notes_count'] = $noteModel->where('user_id', $userId)->countAllResults();
        $data['plans_count'] = $planModel->where('user_id', $userId)->where('is_completed', 0)->countAllResults(); // hanya yang belum selesai
        $data['recent_notes'] = $noteModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll(5);
        $data['recent_plans'] = $planModel->where('user_id', $userId)->where('is_completed', 0)->orderBy('created_at', 'DESC')->findAll(5);
        
        return view('dashboard', $data);
    }
    public function setTheme()
    {
        $theme = $this->request->getPost('theme');
        if (in_array($theme, ['light', 'dark'])) {
            session()->set('theme', $theme);
        }
        return $this->response->setJSON(['status' => 'ok']);
    }
}