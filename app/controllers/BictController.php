<?php

class BictController extends Controller
{

    public function index(): void
    {
        // url
        $_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = explode('/', $_SERVER['REQUEST_URI_PATH']);
        $dept = $url[1];

        
        if (count($_SESSION) == 0 || $_SESSION['user']['isLogin'] == false) {
            Site::redirect('/auth/login?dept='.$dept);
        }
        
        $this->model('HomeModel');
        $this->model('AnnouceModel');
        $this->model('QuarterModel');

        $data = array(
            'title' => 'หน้าหลัก',
            'annouces' => $this->AnnouceModel->get_active_annouce(),
            'quarters' => $this->QuarterModel->get_quarters(),
        );
        
        $this->adminView('home/home',  $data);
    }

    public function dashboard(): void
    {
        if (count($_SESSION) == 0 || $_SESSION['user']['isLogin'] == false) {
            Site::redirect('/auth/login');
        }

        $this->model('HomeModel');
        $this->model('ProjectsModel');
        
        $data = array(
            'title' => 'Home',
            'selected_year' => $_POST['yearSelect'] ?? $current_year = date('Y') + 543,
            'project_year' => $this->ProjectsModel->get_project_years(),
            'project_stat' => $this->ProjectsModel->get_projects_stat_by_id($_SESSION['user']['division_id'], $_POST),
        );
        $this->adminView('home/dashboard', $data);
    }

}

class_alias('BictController', 'bict');
