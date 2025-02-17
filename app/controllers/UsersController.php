<?php

class UsersController extends Controller
{
    public function index(): void
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('UsersModel');

        $data = array(
            'title' => 'จัดการผู้ใช้งาน',
            'users' => $this->UsersModel->getUsersData()
        );

        $this->adminView('users/users', $data);
    }

    public function profile($id = null): void
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('UsersModel');

        if (!User::isAdmin()) {
            $id = User::id();
        }

        $data = array(
            'title' => 'ข้อมูลผู้ใช้งาน',
            'user' => $this->UsersModel->getUserById($id)
        );

        $this->adminView('users/profile', $data);
    }

    public function updatePassword()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if (!Method::isPost()) {
            Site::redirect('/users/profile');
        }

        if (!isset($_POST) || empty($_POST)) {
            Site::redirect('/users/profile');
        }

        $id = $_POST['id'];
        $password = trim(md5($_POST['new_password']));
        $this->model('UsersModel');
        $this->UsersModel->updatePassword($id, $password);
        Site::redirect('/users/profile/' . $id);
    }

    public function getUsersByDivision($div)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('UsersModel');
        $data = $this->UsersModel->getUsersByDivision($div);
        echo json_encode($data);
    }
}

class_alias('UsersController', 'users');
