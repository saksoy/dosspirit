<?php

class RandomKeyComponent extends Component {

    private $_defaultSettings = array(
        'defaultLength' => 4,
        'caseType' => 'mixed'
    );
    
    public $settings;


    public function generate() {
        $this->_defaultSettings = array_merge($this->_defaultSettings, $this->settings);

        $randomString = '';
        // Small letters ascii range: 97 -> 122
        for ($i = 0; $i < $this->_defaultSettings['defaultLength']; $i++) {
            $character = chr(rand(97, 122));

            switch($this->_defaultSettings['caseType']) {
                case 'mixed':
                    $random = rand(0,100);

                    if (!($random & 1)) {
                        $character = strtoupper($character);
                    }
                    break;

                case 'uppercase':
                        $character = strtoupper($character);
                    break;

                // Do not do anything if the other case types aren't provided
                default:
                case 'none':
                    break;
            }

            $randomString .= $character;
        }

        return $randomString;
    }

}