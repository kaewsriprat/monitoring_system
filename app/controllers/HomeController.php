<?php

class HomeController extends Controller
{

    public function index(): void
    {
    
        if (count($_SESSION) == 0 || $_SESSION['user']['isLogin'] == false) {
            Site::redirect('/auth/login');
        }

        if($_SESSION['user']['id'] == 128){
            Site::redirect('/bict');
        }
        
        $this->model('HomeModel');
        $this->model('AnnouceModel');
        $this->model('QuarterModel');

        $data = array(
            'title' => 'หน้าหลัก',
            'annouces' => $this->AnnouceModel->get_active_annouce(),
            'quarters' => $this->QuarterModel->get_quarters(),
            'topReported' => $this->HomeModel->getTopReported(),
        );
        
        $this->adminView('home/home',  $data);
    }

    public function dashboard(): void
    {
        if (count($_SESSION) == 0 || $_SESSION['user']['isLogin'] == false) {
            Site::redirect('/auth/login');
        }

        $this->model('DivisionsModel');

        $data = array(
            'title' => 'แดชบอร์ด',
            'divisions' => $this->DivisionsModel->getDivisions(),
        );

        if(User::isAdmin()){
            $this->adminView('home/admin_dashboard', $data);
        } else {
            $this->adminView('home/dashboard', $data);
        }
    }

}

class_alias('HomeController', 'Home');
