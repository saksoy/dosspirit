<?php

class UserActivationCode extends AppModel {
    var $useTable = 'user_activation_code';
    var $belongsTo = 'user';
}