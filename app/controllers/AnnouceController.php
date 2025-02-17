<?php

class AnnouceController extends Controller
{

    public function index(): void
    {
        if (count($_SESSION) == 0 || $_SESSION['user']['isLogin'] == false) {
            Site::redirect('/auth/login');
        }

        $this->model('AnnouceModel');

        $data = array(
            'title' => 'จัดการข่าวประกาศ',
            'annouces' => $this->AnnouceModel->get_annouces(),
        );

        $this->adminView('annouce/annouce',  $data);
    }
    
    public function new_annouce_post() {
        $this->model('AnnouceModel');
        $this->AnnouceModel->new_annouce_post();

        Site::redirect('/annouce');
    }

    public function update_annouce_state() {
        $this->model('AnnouceModel');
        echo json_encode($this->AnnouceModel->update_annouce_state());
    }

    public function update_annouce() {
        $this->model('AnnouceModel');
        echo json_encode($this->AnnouceModel->update_annouce());
    }

    public function update_annouce_pin() {
        $this->model('AnnouceModel');
        echo json_encode($this->AnnouceModel->update_annouce_pin());
    }

    public function archive_annouce() {
        $this->model('AnnouceModel');
        echo json_encode($this->AnnouceModel->archive_annouce($_POST['id']));
    }
}

class_alias('AnnouceController', 'Annouce');
