<?php

class BudgetformController extends Controller
{
    public function index() {}

    public function admin()
    {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if(!User::isAdmin()) {
            Site::redirect('/budgetform/reports');
        }
        $data = [
            'title' => 'แบบรายงานสงป.',
        ];

        $this->adminView('budgetform/admin', $data);
    }

    public function reports()
    {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if(User::isAdmin()) {
            Site::redirect('/budgetform/admin');
        }
        $data = [
            'title' => 'รายงานสงป.',
        ];

        $this->adminView('budgetform/reports', $data);
    }

    public function create() {}

    public function getBudgetformReports()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Method::restrictMethod();
        }

        $this->model('BudgetformModel');
        $result = $this->BudgetformModel->getBudgetformReports();

        echo json_encode($result);
    }

    public function getBudgetformReportsByDivision($division)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('BudgetformModel');
        $result = $this->BudgetformModel->getBudgetformReportsByDivision($division);

        echo json_encode($result);
    }

    public function getFilteredFormReports($year, $division)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('BudgetformModel');
        $result = $this->BudgetformModel->getFilteredFormReports($year, $division);

        echo json_encode($result);
    }

    public function submitReportYear()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $reportYear = $_POST['reportYear'];
        $division = User::division();

        $this->model('BudgetformModel');
        $result = $this->BudgetformModel->submitReportYear($reportYear, $division);

        echo json_encode($result);
    }

    public function submitReportForm() {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if(!Method::isPost()) {
            Method::restrictMethod();
        }

        $id = $_POST['id'];
        $division = User::division();
        $budgetYear = $_POST['budgetYear'];
        $quarter = $_POST['quarter'];
        $file = $_FILES['reportFile'];

        $uploadStatus = $this->upload($division, $budgetYear, $quarter, $file);
        if($uploadStatus['fileUploadStatus']['status'] === false) {
            echo json_encode([
                'status' => $uploadStatus['fileUploadStatus']['status'],
                'message' => $uploadStatus['fileUploadStatus']['error']
            ]);
            exit;
        } else {
            $this->model('BudgetformModel');
            $result = $this->BudgetformModel->submitReportForm($id, $quarter, $uploadStatus['pathUpdateStatus']);
            echo json_encode($result);
        }
    }

    private function upload($division, $year, $quarter, $inputFile) {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        $rootDir = 'budgetform/';
        $targetDir = $rootDir . $year . '/' . $division;
        $targetFileName = $division . '_' . $quarter;
        
        // file extension
        $extension = explode('.', $inputFile['name']);
        $extension = end($extension);

        $file = new File();
        $file->setMaxFileSize(50);
        $file->setTargetDir($targetDir);
        $file->setTargetFileName($targetFileName);
        $file->setAllowedFileTypes('doc', 'docx', 'pdf', 'xls', 'xlsx');
        $result = $file->upload($inputFile);

        $status = [
            'fileUploadStatus' => $result,
            'pathUpdateStatus' => $targetDir . '/' . $targetFileName . '.' . $extension
        ];

        return $status;
    }

    public function delete($id, $quarter) {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if(!Method::isDelete()) {
            Method::restrictMethod();
        }

        $this->model('BudgetformModel');
        $result = $this->BudgetformModel->delete($id, $quarter);
        echo json_encode($result);
    }
}

class_alias('BudgetformController', 'Budgetform');
