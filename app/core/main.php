<?php
namespace downapiadmin\app\core;
use downapiadmin\app\helpers\session;

class main {

    public  $_url;
    private $_controller = null;
    private $_defaultController;

    public function __construct() {
        // Start der Session
        session::init();
        // Setzt die URL
        $this->_getUrl();
    }

    public function setController($name) {
        $this->_defaultController = $name;
    }

    public function init() {


        $this->_loadExistingController();
        // $this->_callControllerMethod();

    }

    private function _getUrl() {
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : NULL;
        $url = urlencode($url ?? '');
        $url = urldecode(htmlspecialchars($url));
        $this->_url = explode('/', $url);
    }



    private function _loadExistingController() {

        if(empty($this->_url[0])){
            $controllerName = 'start';
        } else {
            $controllerName = $this->_url[0] ?? 'start';
        }
      //  if($controllerName <> 'updates' AND $controllerName <> 'download' AND $controllerName <> 'pubkey'){
            $actionName = $this->_url[1] ?? 'index';
            /* } else {
            $actionName = 'index';
        } */
        // Namespace-Prefix für Controller
        $namespacePrefix = 'downapiadmin\\app\\controllers\\';

        // Vollqualifizierter Klassenname des Controllers
        $controllerClassName = $namespacePrefix . $controllerName;

        // Überprüfe, ob der Controller existiert
        if (class_exists($controllerClassName)) {
            $controller = new $controllerClassName();

            // Überprüfe, ob die Aktionsmethode existiert
            if (method_exists($controller, $actionName)) {
                $controller->$actionName();
            } else {
                // Aktionsmethode nicht gefunden
                echo '404 - 1Seite nicht gefunden';
            }
        } else {
            // Controller nicht gefunden
            echo '404 - 2Seite nicht gefunden';
        }

    }

    /**
     * If a method is passed in the GET url paremter
     *
     *  http://localhost/controller/method/(param)/(param)/(param)
     *  url[0] = Controller
     *  url[1] = Method
     *  url[2] = Parameter
     *  url[3] = Parameter
     *  url[4] = Parameter
     */
    private function _callControllerMethod()
    {
        unset($this->_url[0]);
        $method = 'index';

        if (is_callable(array($this->_controller, $this->_url[1]))) {
            $method = array_shift($this->_url);
        }

        $parameter = array_map("htmlspecialchars", $this->_url);
        //    $parameter = htmlspecialchars($this->_url);
        call_user_func_array(array($this->_controller, $method), $parameter);
    }

    /**
     * Display an error page if nothing exists
     *
     * @return boolean
     */
    private function _error($error) {
        $this->_controller = new myerror($error);
        $this->_controller->index();
        die;
    }

  /*  private function _error($error) {
        if(file_exists(DOCROOT.'/core/error.php'))
        {
            require DOCROOT.'/core/error.php';
        }
        else
        {
            require '../../core/error.php';
        }
        $this->_controller = new MyError($error);
        $this->_controller->index();
        die;
    } */

}
