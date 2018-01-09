<?php

namespace app\summer\controller;


class editorstudy extends Common {

    public function index() {
        echo "fsdfsadfasdfs";
        $this->redirect('/bIndex');
    }

    /**
     *
     * @return mixed
     */
    public function studyvideo() {
        return $this->fetch("studyvideo");
    }
    public function studyeditor() {
        return $this->fetch("studyeditor");
    }
}
