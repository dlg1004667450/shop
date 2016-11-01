<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helpers('web_helper');
        //$this->member_data = member_login();
        $this->load->model('loop_model');
    }

    /**
     * 引导页
     */
    public function index()
    {
        display('default/activity/index.html');
    }

}
