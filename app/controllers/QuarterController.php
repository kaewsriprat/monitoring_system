<?php

class QuarterController extends Controller
{
    public function index(): void
    {
        if (count($_SESSION) == 0 || $_SESSION['user']['isLogin'] == false) {
            Site::redirect('/auth/login');
        }

        $this->model('QuarterModel');

        $data = array(
            'title' => 'จัดการผู้ใช้งาน',
            'quarters' => $this->QuarterModel->get_quarters()
        );
        $this->adminView('quarter/quarter', $data);
    }

    public function quater_post() {
        $post_data = array();
        $post_data['BudgetyearSelect'] = $_POST['BudgetyearSelect'];
        $post_data['quarterSelect'] = $_POST['quarterSelect'];
        $post_data['start_date'] = $this->convert_date($_POST['start_date']);
        $post_data['end_date'] = $this->convert_date($_POST['end_date']);
 
        $this->model('QuarterModel');
        $this->QuarterModel->new_quater_post($post_data);
        Site::redirect('/quarter');
    }

    public function delete($id) {
        $this->model('QuarterModel');
        $this->QuarterModel->delete_quarter($id);
        Site::redirect('/quarter');
    }

    private function convert_date($date) {
        // convert from dd/mm/yyyy to yyyy-mm-dd
        $date_arr = explode('/', $date);
        $date = $date_arr[2]-543 . '-' . $date_arr[1] . '-' . $date_arr[0];
        return $date;
    }
   
}

class_alias('QuarterController', 'Quarter');
