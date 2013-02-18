<?php

class AppError extends ErrorHandler {

    public function pageNotFound($params) {
        $this->controller->layout = 'main';
        $this->controller->set('page', $params);

        $this->_outputMessage('page_not_found');
    }

    /**
     * Override the default 404 error
     * @param $params
     */
    public function error404($params) {
        $d = new Dispatcher();
        $this->controller->layout = 'main';
        parent::error404($params);
    }

}

?>