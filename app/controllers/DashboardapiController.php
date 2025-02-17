<?php

class DashboardapiController extends Controller
{
    
    public function index() {
        Site::redirect('/home/dashboard');
    }

    public function getProjects($year, $division) {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if(!Method::isGet()){
            Method::restrictMethod();
        } 

        $this->model('DashboardModel');

        $result = $this->DashboardModel->getProjects($year, $division);
        echo json_encode($result);
    }

    public function getReportedProjects($year, $division) {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if(!Method::isGet()){
            Method::restrictMethod();
        } 

        $this->model('DashboardModel');

        $result = $this->DashboardModel->getReportedProjects($year, $division);
        echo json_encode($result);
    }
}

class_alias('DashboardapiController', 'dashboardapi');

?>