<?php
class signup extends Controller
{
    public function index()
    {
        $data['css'] = 'signup';
        $data['judul'] = 'signup';
        $data['js'] = 'signup';
        $this->view('templates/header', $data);
        $this->view('signup/index', $data);
        $this->view('templates/footer', $data);
    }
    
    public function tambahakun() {
        $email = $_POST['email'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flasher::setFlash('Email tidak valid', 'danger');
            header('Location: ' . BASEURL . '/signup');
            exit;
        }

        // Panggil model untuk membuat akun
        $result = $this->model('Customer_db')->insertakun($_POST);

        // Periksa hasil dan set flash message sesuai dengan hasil
        if ($result['status']) {
            header('Location: ' . BASEURL . '/profile');
        } else {
            Flasher::setFlash($result['message'], 'danger');
            header('Location: ' . BASEURL . '/signup');
        }

        // Redirect ke halaman yang sesuai
        exit;
    }
}
