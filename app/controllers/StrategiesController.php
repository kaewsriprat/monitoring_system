<?php

class StrategiesController extends Controller {
    public function index() {
        if(!User::isLogin() ) {
            Site::redirect('/auth/login');
        }
        if(!User::isAdmin() ) {
            Site::redirect('/');
        }

        $data = [
            'title' => 'ยุทธศาสตร์',
        ];

        $this->adminView('strategies/strategies', $data);
    }

    public function getStrategies($year) {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if(!Method::isGet()) {
            Method::restrictMethod();
        }

        $this->model('StrategiesModel');
        $result = $this->StrategiesModel->getStrategies($year);

        echo json_encode($result);
    }

    public function getStrategyById($id) {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if(!Method::isGet()) {
            Method::restrictMethod();
        }

        $this->model('StrategiesModel');
        $result = $this->StrategiesModel->getStrategyById($id);

        echo json_encode($result);
    }

    public function newStrategy() {
        if(!User::isLogin() || !User::isAdmin()) {
            Site::redirect('/auth/login');
        }

        if(!Method::isPost()) {
            Method::restrictMethod();
        }

        $data = [
            'yearSelect' => $_POST['yearSelect'],
            'strategyName' => $_POST['strategyName'],
        ];
        
        $this->model('StrategiesModel');
        $result = $this->StrategiesModel->newStrategy($data);
  
        if($result) {
            echo json_encode(['status' => 'success', 'message' => 'เพิ่มยุทธศาสตร์สำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'เพิ่มยุทธศาสตร์ไม่สำเร็จ']);
        }
    }   

    public function updateStrategy($id) {
        if(!User::isLogin() || !User::isAdmin()) {
            Site::redirect('/auth/login');
        }

        if(!Method::isPost()) {
            Method::restrictMethod();
        }

        $data = [
            'id' => $id,
            'yearSelect' => $_POST['yearSelect'],
            'strategyName' => $_POST['strategyName'],
        ];

        $this->model('StrategiesModel');
        $result = $this->StrategiesModel->updateStrategy($data);

        if($result) {
            echo json_encode(['status' => 'success', 'message' => 'แก้ไขยุทธศาสตร์สำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'แก้ไขยุทธศาสตร์ไม่สำเร็จ']);
        }
    }

    public function deleteStrategy($id) {
        if(!User::isLogin() || !User::isAdmin()) {
            Site::redirect('/auth/login');
        }

        if(!Method::isDelete()) {
            Method::restrictMethod();
        }

        $this->model('StrategiesModel');
        $result = $this->StrategiesModel->deleteStrategy($id);

        if($result) {
            echo json_encode(['status' => 'success', 'message' => 'ลบยุทธศาสตร์สำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ลบยุทธศาสตร์ไม่สำเร็จ']);
        }
    }
}

class_alias('StrategiesController', 'strategies');
?>