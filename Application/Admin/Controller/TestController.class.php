<?php

namespace Admin\Controller;

class TestController extends ThinkController {

    private $model_name = "test";

    public function _initialize() {
        parent::_initialize();
        $this->assign("high_url", 'Test/index');
    }

    public function index() {
        parent::lists($this->model_name);
    }

    public function lists() {
        parent::lists($this->model_name);
    }
    
    public function record() {
        $this->assign("high_url", 'Test/record');
        parent::group($this->model_name);
    }

    public function add() {
        parent::add($this->model_name);
    }

    public function del($ids) {
        $this->assign("high_url", 'Stock/record');
        parent::del($this->model_name, $ids);
    }

    public function edit($id) {
        $this->assign("high_url", 'Stock/record');
        parent::edit($this->model_name, $id);
    }

}
