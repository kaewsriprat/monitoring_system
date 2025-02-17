<?php

class App
{
    private $config = [];
    public $Wdb;
    public $Rdb;

    function __construct()
    {
        ini_set('display_errors', -1);
        error_reporting(E_ERROR | E_WARNING | E_PARSE);

        define('URI', $_SERVER['REQUEST_URI']);
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
        require(ROOT . '/core/config/config.php');
    }

    /**
     * Autoload
     */
    public function autoload(): void
    {
        spl_autoload_register(function ($class) {
            $class = strtolower($class);
            $class = ucfirst($class);
            if (file_exists(ROOT . '/core/classes/' . $class . '.php')) {
                require_once(ROOT . '/core/classes/' . $class . '.php');
            } else if (file_exists(ROOT . '/core/helpers/' . $class . '.php')) {
                require_once(ROOT . '/core/helpers/' . $class . '.php');
            }
        });
    }

    public function config(): void
    {
        require_once(ROOT . '/core/config/database.php');
        require_once(ROOT . '/core/config/session.php');

        //WRITE DB
        try {
            $this->Wdb = new PDO(
                $this->config['Wdb']['driver'] . ':host=' . $this->config['Wdb']['host'] . ';dbname=' . $this->config['Wdb']['name'],
                $this->config['Wdb']['username'],
                $this->config['Wdb']['password']
            );

            $this->Wdb->query('SET NAMES utf8');
            //  $this->Wdb->query('SET CHARACTER_SET utf8_unicode_ci');

            $this->Wdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log(
                '[' . date('Y-m-d H:i:s') . '] [ERROR] [' . $_SERVER['REMOTE_ADDR'] . '] ' . $e->getMessage() . "\n",
                3,
                'error.log'
            );
            die($e->getMessage());
        }

        //READ DB
        try {
            $this->Rdb = new PDO(
                $this->config['Rdb']['driver'] . ':host=' . $this->config['Rdb']['host'] . ';dbname=' . $this->config['Rdb']['name'],
                $this->config['Rdb']['username'],
                $this->config['Rdb']['password']
            );

            $this->Rdb->query('SET NAMES utf8');
            // $this->Rdb->query('SET CHARACTER_SET utf8_unicode_ci');

            $this->Rdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log(
                '[' . date('Y-m-d H:i:s') . '] [ERROR] [' . $_SERVER['REMOTE_ADDR'] . '] ' . $e->getMessage() . "\n",
                3,
                'error.log'
            );
            die($e->getMessage());
        }
    }


    /**
     * Start
     */
    public function start(): void
    {
        session_name($this->config['session-name']);
        session_start();

        $route    = explode('/', URI);
        $route[1] = strtolower($route[1]);
        $route[1] = ucfirst($route[1]);

        if (file_exists(ROOT . '/app/controllers/' . $route[1] . 'Controller.php')) {
            require(ROOT . '/app/controllers/' . $route[1] . 'Controller.php');
            $controller = new $route[1]();
        } else {
            require(ROOT . '/app/controllers/MainController.php');
            $main = new MainController();
        }
    }
}