<?php

namespace downapiadmin\app\core;

use downapiadmin\app\helpers\formats;
use downapiadmin\app\helpers\myforms;
use downapiadmin\app\helpers\mymail;
use downapiadmin\app\records;


class controller extends main
{


    protected $_view;
    protected $_model;
    protected $_basemodel;



    protected $_classname;

       


    function __construct()
    {

        $this->_view = new view();
        $this->_basemodel = new model();

       $this->_classname = get_class($this);

        $class_parts = explode('\\', $this->_classname);
        $this->_classname = end($class_parts);

        try {

            $this->_classname = 'downapiadmin\app\models\\' . $this->_classname;
            if(class_exists($this->_classname)) {
                $this->_model = new $this->_classname();
            }
        } catch(\Exception $e){

        }

    }

    protected function RegisterModul($name, $version, $link = '')
    {
        $data[$name]['name'] = $name;
        $data[$name]['version'] = $version;
    }


}
