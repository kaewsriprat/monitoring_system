<?php

class DivisionsController extends Controller
{

    public function index()
    {
        
        Site::redirect('/home');
    }

    public function getDivisions()
    {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if(!User::isAdmin()) {
            Site::redirect('/home');
        }
        $this->model('DivisionsModel');
        $divisions = $this->DivisionsModel->getDivisions();
        echo json_encode($divisions);
    }

}

class_alias('DivisionsController', 'divisions');

?>