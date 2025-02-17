<?php

class IndicatorsController extends Controller
{
    public function index()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if (User::isAdmin()) {
            Site::redirect('/indicators/majorIndicators');
        }
        if (User::isDirector()) {
            Site::redirect('/indicators/approve');
        }
        if (User::isUser()) {
            Site::redirect('/indicators/reports');
        }
    }

    public function getFullIndicatorsReportByIndicatorId($indicator_id)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }
        if (!Method::isGet()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');
        $indicators = $this->IndicatorsModel->getFullIndicatorsReportByIndicatorId($indicator_id);
        echo json_encode($indicators);
    }

    public function getSummaryIndicatorReport($year, $classification)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }
        if (!Method::isGet()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');
        $indicators = $this->IndicatorsModel->getSummaryIndicatorReport($year, $classification);
        echo json_encode($indicators);
    }

    public function create($classification)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }

        $data = [
            'title' => 'เพิ่มตัวชี้วัด',
            'classification' => ($classification) ? $classification : 'major',
        ];

        $this->adminView('indicators/create', $data);
    }

    public function edit($id)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }

        $this->model('IndicatorsModel');

        $data = [
            'title' => 'แก้ไขตัวชี้วัด',
            'indicatorDetail' => $this->IndicatorsModel->getIndicatorById($id),
        ];

        $this->adminView('indicators/edit', $data);
    }

    public function newIndicator()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }
        if (!Method::isPost()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');
     
        $result = $this->IndicatorsModel->newIndicator($_POST);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Create indicator success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Create indicator failed']);
        }
    }

    public function editIndicator()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }
        if (!Method::isPost()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');

        $indicator = [
            'indicatorId' => $_POST['indicatorId'],
            'indicatorName' => $_POST['indicatorName'],
            'indicatorTarget' => $_POST['IndTarget'],
            'indicatorTargetText' => $_POST['IndTargetText'],
            'goalsIndSelect' => $_POST['goalsIndSelect'],
        ];

        $this->IndicatorsModel->updateIndicator($indicator);

        $newReports = [];
        foreach ($_POST as $key => $value) {
            preg_match('/new_([a-zA-Z]+)_(\d+)/', $key, $matches);
            if (isset($matches[2])) {
                $groupKey = $matches[2];
                $field = $matches[1];

                if (!isset($newReports[$groupKey])) {
                    $newReports[$groupKey] = [
                        'indicatorId' => $_POST['indicatorId'],
                        'divisionId' => null,
                        'projectId' => null,
                        'target' => null,
                        'targetText' => null,
                    ];
                }

                switch ($field) {
                    case 'indDivisionSelect':
                        $newReports[$groupKey]['divisionId'] = $value;
                        break;
                    case 'indProjectSelect':
                        $newReports[$groupKey]['projectId'] = $value;
                        break;
                    case 'indDivisionTarget':
                        $newReports[$groupKey]['target'] = $value;
                        break;
                    case 'indDivisionTargetText':
                        $newReports[$groupKey]['targetText'] = $value;
                        break;
                }
            }
        }

        // Remove the group keys for a clean structure
        $newReports = array_values($newReports);

        $updateReports = [];
        foreach ($_POST as $key => $value) {
            preg_match('/edit_(\d+)/', $key, $matches);
            if (isset($matches[1])) {
                $groupKey = 'edit_' . $matches[1];
                if (!isset($updateReports[$groupKey])) {
                    $updateReports[$groupKey] = [
                        'id' => null,
                        'projectId' => null,
                        'target' => null,
                        'targetText' => null,
                    ];
                }

                switch (true) {
                    case strpos($key, '_indProjectSelect') !== false:
                        $updateReports[$groupKey]['projectId'] = $value;
                        break;
                    case strpos($key, '_indDivisionTarget') !== false && strpos($key, 'Text') === false:
                        $updateReports[$groupKey]['target'] = $value;
                        break;
                    case strpos($key, '_indDivisionTargetText') !== false:
                        $updateReports[$groupKey]['targetText'] = $value;
                        break;
                    default:
                        $updateReports[$groupKey]['id'] = $value;
                        break;
                }
            }
        }

        $updateReports = array_values($updateReports);
        $status = [];

        if ($newReports) {
            array_push($status, $this->IndicatorsModel->updateNewReports($newReports));
        }
        if ($updateReports) {
            array_push($status, $this->IndicatorsModel->updateReports($updateReports));
        }
        $deleteReportsLength = count(json_decode($_POST['deleteReports'], true));
        if ($deleteReportsLength > 0) {
            array_push($status, $this->IndicatorsModel->deleteReports($_POST['deleteReports']));
        }

        if (in_array(false, $status)) {
            echo json_encode(['status' => 'error', 'message' => 'Update indicator failed']);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Update indicator success']);
        }
    }

    public function getIndicatorById($id)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('IndicatorsModel');
        $indicator = $this->IndicatorsModel->getIndicatorById($id);
        echo json_encode($indicator);
    }

    public function delete($id)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }
        if (!Method::isDelete()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');
        $result = $this->IndicatorsModel->deleteIndicator($id);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Delete indicator success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Delete indicator failed']);
        }
    }

    //////////////////////////////////// MAJOR INDICATORS ////////////////////////////////////

    public function majorIndicators()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }

        $data = [
            'title' => 'จัดการตัวชี้วัด',
            'classification' => 'major',
        ];

        $this->adminView('indicators/majorindicators', $data);
    }

    //////////////////////////////////// MAJOR INDICATORS ////////////////////////////////////

    //////////////////////////////////// MINOR INDICATORS ////////////////////////////////////

    public function minorIndicators()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }

        $data = [
            'title' => 'จัดการตัวชี้วัด',
            'classification' => 'minor',
        ];

        $this->adminView('indicators/minorIndicators', $data);
    }

    //////////////////////////////////// MINOR INDICATORS ////////////////////////////////////

    //////////////////////////////////// GOALS ////////////////////////////////////

    public function goals()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }
        if (!Method::isGet()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');

        $data = [
            'title' => 'รายงานตัวชี้วัด',
            'goals' => $this->IndicatorsModel->getGoals(),
        ];

        $this->adminView('indicators/goals', $data);
    }

    public function getGoalsByYear($year, $classification)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }
        if (!Method::isGet()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');
        $goals = $this->IndicatorsModel->getGoalsByYear($year, $classification);
        echo json_encode($goals);
    }

    public function getGoalById($id)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }

        $this->model('IndicatorsModel');
        $goal = $this->IndicatorsModel->getGoalById($id);
        echo json_encode($goal);
    }

    public function newGoal()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }
        if (!Method::isPost()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');
        $title = $_POST['goalInput'];
        $year = $_POST['yearsSelect'];
        $classification = $_POST['classificationInput'];
        $this->IndicatorsModel->newGoal($title, $year, $classification);
        Site::redirect('/indicators/goals');
    }

    public function updateGoal($id)
    {

        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }
        if (!Method::isPost()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');
        $title = $_POST['editGoalInput'];
        $year = $_POST['editYearSelect'];
        $classification = $_POST['editClassificationInput'];
        $result = $this->IndicatorsModel->updateGoal($id, $title, $year, $classification);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Update success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        }
    }

    public function deleteGoal($id)
    {

        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isAdmin()) {
            Site::redirect('/home');
        }
        if (!Method::isDelete()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');
        $resutl = $this->IndicatorsModel->deleteGoal($id);
        if ($resutl) {
            echo json_encode(['status' => 'success', 'message' => 'Delete success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
        }
    }

    //////////////////////////////////// GOALS ////////////////////////////////////

    //////////////////////////////////// REPORTS ////////////////////////////////////

    public function reports()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if (!User::isUser()) {
            Site::redirect('/home');
        }

        $data = [
            'title' => 'รายงานตัวชี้วัด',
        ];

        $this->adminView('indicators/reports', $data);
    }

    public function getIndicatorsByDivision($year, $division)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if (!User::isAdmin()) {
            if ($division != User::division()) {
                echo json_encode([]);
                exit;
            }
        }

        $this->model('IndicatorsModel');
        $indicators = $this->IndicatorsModel->getIndicatorsByDivision($year, $division);
        echo json_encode($indicators);
    }

    public function getIndicatorsByDivision2($year, $division)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('IndicatorsModel');
        $indicators = $this->IndicatorsModel->getIndicatorsByDivision2($year, $division);
        echo json_encode($indicators);
    }

    public function getReportById($id)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $this->model('IndicatorsModel');
        $report = $this->IndicatorsModel->getReportById($id);
        echo json_encode($report);
    }

    // public function updateIndicatorScore()
    // {
    //     if (!User::isLogin()) {
    //         Site::redirect('/auth/login');
    //     }
    //     if (!Method::isPost()) {
    //         Method::restrictMethod();
    //     }

    //     $repId = $_POST['repId'];
    //     $quarter = $_POST['quarter'];
    //     $score = $_POST['score'];

    //     $this->model('IndicatorsModel');
    //     $result = $this->IndicatorsModel->updateIndicatorScore($repId, $quarter, $score);
    //     echo json_encode($result);
    // }

    public function updateReport() {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if(!Method::isPost()) {
            Method::restrictMethod();
        }

        $data = [
            'rep_id' => $_POST['repId'],
            'target' => $_POST['target'],
            'targetDetail' => $_POST['targetDetail'],
            'q1_score' => $_POST['q1'],
            'q2_score' => $_POST['q2'],
            'q3_score' => $_POST['q3'],
            'q4_score' => $_POST['q4'],
        ];

        $this->model('IndicatorsModel');
        $result = $this->IndicatorsModel->updateUserReport($data);

        echo json_encode($result);
    }

    public function deleteUserReport($rep_id) {
        if(!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if(!Method::isDelete()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');
        $result = $this->IndicatorsModel->deleteUserReport($rep_id);

        echo json_encode($result);
    }

    //////////////////////////////////// SCORE APPROVE ////////////////////////////////////

    public function approve()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!User::isDirector()) {
            Site::redirect('/home');
        }

        $data = [
            'title' => 'อนุมัติคะแนนตัวชี้วัด',
            'division' => User::division(),
        ];

        $this->adminView('indicators/approve', $data);
    }

    public function doApprove()
    { {
            if (!User::isLogin()) {
                Site::redirect('/auth/login');
            }
            if (!User::isDirector()) {
                Site::redirect('/home');
            }
            if (!Method::isPost()) {
                Method::restrictMethod();
            }

            $this->model('IndicatorsModel');

            $result = $this->IndicatorsModel->doApprove($_POST);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Approve success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Approve failed']);
            }
        }
    }

    public function requestUpdateTarget($id, $newTarget)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }
        if (!Method::isGet()) {
            Method::restrictMethod();
        }

        $this->model('IndicatorsModel');

        $result = $this->IndicatorsModel->requestUpdateTargetPending($id, $newTarget);
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Update success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        }
    }

    //////////////////////////////////// TARGET APPROVE ////////////////////////////////////

    public function targetApprove()
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        $data = [
            'title' => 'อนุมัติเป้าหมายตัวชี้วัด',
        ];

        $this->adminView('indicators/targetapprove', $data);
    }

    public function getPendingRequestTargets($division)
    {
        if (!User::isLogin()) {
            Site::redirect('/auth/login');
        }

        if (User::isUser()) {
            Site::redirect('/home');
        }

        $this->model('IndicatorsModel');
        $result = $this->IndicatorsModel->getPendingRequestTargets($division);
        echo json_encode($result);
    }

    public function approveRequestTarget($id, $status)
    {
        $this->model('IndicatorsModel');
        $result = $this->IndicatorsModel->approveRequestTarget($id, $status);

        $isBothApproved = $this->IndicatorsModel->checkIfTargetIsApproved($id);
        if($isBothApproved['status']){
            $this->IndicatorsModel->updateTarget($isBothApproved['data']['rep_id'], $isBothApproved['data']['new_target']);
        }

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Approve success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Approve failed']);
        }
    }

    public function getPendingRequestTargetsCount()
    {
        $this->model('IndicatorsModel');
        $resullt = $this->IndicatorsModel->getPendingRequestTargetsCount();
        echo json_encode($resullt['count']);
    }

    public function assignProjectToReport()
    {
        echo json_encode($_POST);
        $this->model('IndicatorsModel');
        $result = $this->IndicatorsModel->assignProjectToReport($_POST);
        echo json_encode($result);

    }
}
class_alias('IndicatorsController', 'Indicators');
