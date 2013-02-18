<?php

class UserController extends AppController {

    public function beforeRender() {
        $this->layout = 'main';
    }
    public function index($userSlug, $userId) {
        $this->loadModel('User');
        $this->loadModel('Review');

        $profile = $this->User->getActivity($userSlug, $userId, 5);
        $profile['Review'] = $this->Review->getUserReviews($userId, 5);

        $this->set('title_for_layout', __("%s's profile", array($profile['User']['username'])));
        if ($profile) {
            $this->set('profile', $profile);
        } else {
            $this->redirect('/');
        }
    }
}

?>