<?php
class Controller
{

    public function view($view, $data = [])
    {
        // Extract the data array to variables
        extract($data);
        // Include the view file
        require_once '../app/views/' . $view . '.php';
    }

    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }

    // Check if user is logged in
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    // Require user to be logged in to access certain pages
    public function requireadminLogin()
    {
        if (!$this->isLoggedIn()) {
            header('Location: '. BASEURL .'/login');
            exit();
        }
    }
    public function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            header('Location: '. BASEURL .'/login');
            exit();
        }
    }
    public function adminmustgo()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            // Redirect ke halaman admin jika pengguna adalah admin
            header('Location: '. BASEURL .'/admin/menu');
            exit();
        }
    }
    public function acustomermustgo()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer') {
            // Redirect ke halaman admin jika pengguna adalah admin
            header('Location: '. BASEURL .'/katalog');
            exit();
        }
    }

}
?>