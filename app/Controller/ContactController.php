<?php

class ContactController extends AppController {
    public $uses = array();

    public function index() {
        $this->layout = 'main';
        if ($this->request->is('post')) {
            try {
            App::uses('CakeEmail', 'Network/Email');

            if (empty($this->data['Contact']['email'])) {
                throw new CakeException('You must provide email');
            }

            if (empty($this->data['Contact']['feedback']) || mb_strlen($this->data['Contact']['feedback'], 'utf8') > 200) {
                throw new CakeException('You must provide valid feedback');
            }


            $emailContent = '<h1>Contact us</h1>' . $this->data['Contact']['feedback'] . '<p> From: ' . $this->data['Contact']['name'] . '</p>';
            $email = new CakeEmail();
            $email->from(array($this->data['Contact']['email']))
                ->to(array('bakkelun@gmail.com', 'smuffy2000@gmail.com'))
                ->emailFormat('html')
                ->template('default', 'default')
                ->subject('The DOS Spirit - Contact us')
                ->send($emailContent);

            $this->set('feedbackSent', 1);

            } catch (CakeException $e) {
                $this->set('error', $e->getMessage());
            }
        }
    }
}