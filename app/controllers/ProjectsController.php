<?php

class ProjectsController extends Controller
{

    public function index()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        } else {
            if (User::isAdmin()) {
                Site::redirect('/projects/admin');
            } else {
                Site::redirect('/projects/lists');
            }
        }
    }

    public function admin()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/projects/lists');
        }

        $this->model('ProjectsModel');
        $this->model('DivisionsModel');
        $this->model('StrategiesModel');

        $data = array(
            'title' => 'โครงการ',
            'divisions' => $this->DivisionsModel->getDivisions(),
            // 'strategies' => $this->StrategiesModel->getStrategies(),
        );
        $this->adminView('projects/admin', $data);
    }

    public function lists()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('ProjectsModel');
        $this->model('DivisionsModel');

        $data = array(
            'title' => 'โครงการ',
            'projects' => $this->ProjectsModel->getProjectsByDivision(Budgetyear::getBudgetyearThai(), User::division()),
            'divisions' => $this->DivisionsModel->getDivisions(),
            'projectStatus' => $this->ProjectsModel->getProjectStatusByDivision(Budgetyear::getBudgetyearThai(), User::division()),
        );

        $this->adminView('projects/user', $data);
    }

    public function reo()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('ProjectsModel');
        $this->model('DivisionsModel');

        $data = array(
            'title' => 'โครงการ',
            'divisions' => $this->DivisionsModel->getDivisions(),
        );
        $this->adminView('projects/reo', $data);
    }

    public function create()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        // is post
        if (Method::isPost()) {
            $this->model('ProjectsModel');
            $this->ProjectsModel->createProject($_POST);
            Site::redirect('/projects/lists');
        }

        // is get
        if (Method::isGet()) {

            $this->model('ProjectsModel');
            $this->model('DivisionsModel');
            $data = array(
                'title' => 'เพิ่มโครงการ',
                'division' => $this->DivisionsModel->getDivisionById(User::division()),
            );

            $this->adminView('projects/create', $data);
        }
    }

    public function update($id)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        // is post
        if (Method::isPost()) {
            $this->model('ProjectsModel');
            $this->ProjectsModel->updateProject($_POST);
            Site::redirect('/projects/lists');
        }

        // is get
        if (Method::isGet()) {

            $this->model('ProjectsModel');
            $this->model('DivisionsModel');
            $data = array(
                'title' => 'ปรับปรุงโครงการ',
                'division' => $this->DivisionsModel->getDivisionById(User::division()),
                'project' => $this->ProjectsModel->getProjectById($id),
            );

            $this->adminView('projects/edit', $data);
        }
    }

    public function upload()
    {
        if (!Method::isPost()) {
            Method::restrictMethod();
        }

        $fileUploadStatus = false;
        $pathUpdateStatus = false;

        $year = $_POST['year'];
        $projectId = $_POST['projectID'];
        $quarter = $_POST['quarter'];

        $rootDir = 'projects/';
        $targetDir = $rootDir . $year . '/' . $projectId;
        $targetFileName = $projectId . '_' . $quarter;

        $file = new File();
        $file->setMaxFileSize(50);
        $file->setTargetDir($targetDir);
        $file->setTargetFileName($targetFileName);
        $file->setAllowedFileTypes('doc', 'docx');
        $result = $file->upload($_FILES['fileInput']);
        if ($result['status']) {
            $fileUploadStatus = true;
        }

        if (isset($result['filePath'])) {
            $this->model('ProjectsModel');
            $result = $this->ProjectsModel->updateFilePath($projectId, $quarter, $result['filePath']);
            if ($result) {
                $pathUpdateStatus = true;
            }
        }

        echo json_encode([
            'realFileName' => $_FILES['fileInput']['name'],
            'fileUploadStatus' => $fileUploadStatus,
            'pathUpdateStatus' => $pathUpdateStatus,
        ]);
    }

    ///////////////////////// API /////////////////////////

    public function getProjectById($id)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if (!Method::isGet()) {
            Method::restrictMethod();
        }

        $this->model('ProjectsModel');
        $project = $this->ProjectsModel->getProjectById($id);
        echo json_encode($project);
    }

    public function getProjectsByDivision($year, $division)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('ProjectsModel');
        $projects = $this->ProjectsModel->getProjectsByDivision($year, $division);
        echo json_encode($projects);
    }

    public function getProjectsTableData($year, $division, $strategy)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('ProjectsModel');
        $projects = $this->ProjectsModel->getProjectsTableData($year, $division, $strategy);
        echo json_encode($projects);
    }

    public function getProjectsByReo($year, $division)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('ProjectsModel');
        $projects = $this->ProjectsModel->getProjectsByReo($year, $division);
        echo json_encode($projects);
    }

    public function deleteFileFromProject($id, $quarter)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if (!Method::isDelete()) {
            Method::restrictMethod();
        }

        $this->model('ProjectsModel');
        $result = $this->ProjectsModel->deleteFile($id, $quarter);
        echo json_encode($result);
    }

    public function userDatatableProjectsAPI()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if (!Method::isPost()) {
            Method::restrictMethod();
        }

        $this->model('DatatableModel');
        $from = $_POST['start'];
        $length = $_POST['length'];
        $search = $_POST['search']['value'];
        $order = $_POST['order'][0]['column'];
        $dir = $_POST['order'][0]['dir'];
        $yearSelect = $_POST['yearsSelect'];

        $result = $this->DatatableModel->getProjectsByDiv($from, $length, $search, $order, $dir, $yearSelect);

        $datatableData['draw'] = $_POST['draw'];
        $datatableData['recordsTotal'] = $result['total'];
        $datatableData['recordsFiltered'] = $result['totalFiltered'];
        $datatableData['data'] =  $result['data'];

        echo json_encode($datatableData);
    }

    public function getProjectStatus($year, $division) {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('ProjectsModel');
        if($division == 0) {
            $result = $this->ProjectsModel->getProjectStatus($year);
        } else {
            $result = $this->ProjectsModel->getProjectStatusByDivision($year, $division);
        }
        echo json_encode($result);
    }

    public function updateProjectPlannedStatus()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if (!Method::isPost()) {
            Method::restrictMethod();
        }

        $this->model('ProjectsModel');
        $result = $this->ProjectsModel->updateProjectPlannedStatus($_POST);
        echo json_encode($result);
    }

    public function deleteProject($id) {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if(!Method::isDelete()) {
            Method::restrictMethod();
        }

        $this->model('ProjectsModel');
        $result = $this->ProjectsModel->deleteProject($id);
        echo json_encode($result);
    }
}

class_alias('ProjectsController', 'projects');
