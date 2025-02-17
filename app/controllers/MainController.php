<?php

class MainController extends Controller
{

    public function index()
    {
        if (count($_SESSION) == 0 || $_SESSION['user']['isLogin'] == false) {
            Site::redirect('/auth/login');
        }
        Site::redirect('/home');
    }
}
