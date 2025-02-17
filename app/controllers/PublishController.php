<?php

class PublishController extends Controller
{
    public function index() : void {
        if (count($_SESSION) == 0 || $_SESSION['user']['isLogin'] == false) {
            Site::redirect('/auth/login');
        }

        $data = array(
            'title' => 'เอกสารเผยแพร่',
        );
        
        $this->adminView('publish/publish', $data);
    }
}

class_alias('PublishController', 'Publish');

?>