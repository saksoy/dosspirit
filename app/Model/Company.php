<?php
class Company extends AppModel {
    var $useTable = 'company';

    public $validate = array(
    'name' => array(
        'isUnique' => array(
            'rule' => 'isUnique',
            'message' => 'This company name exists. Each name must be unique.',
            'required' => true
        ),
        'notEmpty' => array(
            'rule' => 'notEmpty',
            'message' => 'Company name cannot be empty'
        )
    ));
    public function beforeSave($options) {
        // Create a slug for the company name before saving.
        if ($this->data['Company']['name']) {
            $this->data['Company']['slug'] = mb_convert_case(Inflector::slug($this->data['Company']['name'], '-'), MB_CASE_LOWER, 'utf-8');
            return true;
        }
    }
}
?>