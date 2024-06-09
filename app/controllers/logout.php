<?php 
class logout extends Controller
{
    public function index()
    {
        $this->view('templates/header');
        $this->view('logout/index');
        $this->view('templates/footer');
    }
}