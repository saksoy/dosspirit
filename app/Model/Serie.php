<?php

class Serie extends AppModel {
    public $useTable = 'serie';

    public $validate = array(
        'name' => array(
            'isUnique' => array(
            'rule' => 'isUnique',
            'message' => 'This serie name exists. Each name must be unique.',
            'required' => true
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Serie name cannot be empty'
            )
    ));

    public function beforeSave($options) {
        // Create a slug for the company name before saving.
        if ($this->data['Serie']['name']) {
            $this->data['Serie']['slug'] = mb_convert_case(Inflector::slug($this->data['Serie']['name'], '-'), MB_CASE_LOWER, 'utf-8');
            return true;
        }
    }
}
?>