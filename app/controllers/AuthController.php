<?php

class AuthController extends Controller
{

    public function index(): void
    {
        $dept = $_GET['dept'];
        Site::redirect('/auth/login/' . $dept);
    }

    public function login($dept = null)
    {

        if (isset($_SESSION['isLogin'])) {
            Site::redirect('/admin');
        }

        $error = null;
        if (count($_POST) > 0) {
            $email = trim($_POST['emailInput']);
            $password = trim($_POST['passwordInput']);
            if ($email == '' || $password == '') {
                $error = "Invalid email or password";
            }
            $this->model('AuthModel');
            $credential = $this->AuthModel->checkCredential($email, $password);

            if ($credential) {
                $this->createSession($credential);
                Site::redirect('/admin');
            } else {
                $error = "Invalid email or password";
            }
        }

        $data = array(
            'title' => 'Login',
            'dept' => $dept,
            'error' => $error,
        );

        $this->view('auth/login', $data);
    }

    private function createSession($user)
    {
        session_start();
        $_SESSION['user']['isLogin'] = true;
        $_SESSION['user']['id'] = $user['id'];
        $_SESSION['user']['prefix'] = $user['prefix'];
        $_SESSION['user']['firstname'] = $user['firstname'];
        $_SESSION['user']['lastname'] = $user['lastname'];
        $_SESSION['user']['fullname'] = $user['prefix'] . $user['firstname'] . ' ' . $user['lastname'];
        $_SESSION['user']['email'] = $user['email'];
        $_SESSION['user']['position'] = $user['position'];
        $_SESSION['user']['division_id'] = $user['division_id'];
        $_SESSION['user']['division_name'] = $user['division_name'];
        $_SESSION['user']['division_abbr'] = $user['division_abbr'];
        $_SESSION['user']['roles'] = explode(',', $user['roles']);
        $_SESSION['user']['active'] = $user['active'];
        $_SESSION['user']['last_login'] = $user['last_login'];
        $_SESSION['user']['created_date'] = $user['created_date'];
        $_SESSION['user']['updated_date'] = $user['updated_date'];

        // $sessionLifetime = 60 * 2; // 60 minutes
        // session_set_cookie_params($sessionLifetime);
    
        // ให้ session หมดอายุเมื่อผู้ใช้ไม่ได้ใช้งาน (หรือไม่มีกิจกรรม) เกินเวลาที่กำหนด
        // if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $sessionLifetime)) {
        //     session_unset();     // unset $_SESSION ทั้งหมด
        //     session_destroy();   // ทำลาย session ทั้งหมดที่เกี่ยวข้องกับเซสชั่นปัจจุบัน
        // }
    
        //ตั้งค่าเวลากิจกรรมล่าสุดของผู้ใช้เป็นปัจจุบัน
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    public function logout()
    {
        // echo "hello_logout";
        session_start();
        session_destroy();
        Site::redirect('/auth/login');
    }
    
}

class_alias('AuthController', 'auth');