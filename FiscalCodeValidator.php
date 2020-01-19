<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\validators\Validator;

/**
 * Validatore per campi che contengono un codice fiscale.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
 */
class FiscalCodeValidator extends Validator {

    const ODD = [
        '0' => 1, '9' => 21, 'I' => 19, 'R' => 8,
        '1' => 0, 'A' => 1, 'J' => 21, 'S' => 12,
        '2' => 5, 'B' => 0, 'K' => 2, 'T' => 14,
        '3' => 7, 'C' => 5, 'L' => 4, 'U' => 16,
        '4' => 9, 'D' => 7, 'M' => 18, 'V' => 10,
        '5' => 13, 'E' => 9, 'N' => 20, 'W' => 22,
        '6' => 15, 'F' => 13, 'O' => 11, 'X' => 25,
        '7' => 17, 'G' => 15, 'P' => 3, 'Y' => 24,
        '8' => 19, 'H' => 17, 'Q' => 6, 'Z' => 23,
    ];
    const EVEN = [
        '0' => 0, '9' => 9, 'I' => 8, 'R' => 17,
        '1' => 1, 'A' => 0, 'J' => 9, 'S' => 18,
        '2' => 2, 'B' => 1, 'K' => 10, 'T' => 19,
        '3' => 3, 'C' => 2, 'L' => 11, 'U' => 20,
        '4' => 4, 'D' => 3, 'M' => 12, 'V' => 21,
        '5' => 5, 'E' => 4, 'N' => 13, 'W' => 22,
        '6' => 6, 'F' => 5, 'O' => 14, 'X' => 23,
        '7' => 7, 'G' => 6, 'P' => 15, 'Y' => 24,
        '8' => 8, 'H' => 7, 'Q' => 16, 'Z' => 25,
    ];
    const REST = [
        0 => 'A', 7 => 'H', 14 => 'O', 21 => 'V',
        1 => 'B', 8 => 'I', 15 => 'P', 22 => 'W',
        2 => 'C', 9 => 'J', 16 => 'Q', 23 => 'X',
        3 => 'D', 10 => 'K', 17 => 'R', 24 => 'Y',
        4 => 'E', 11 => 'L', 18 => 'S', 25 => 'Z',
        5 => 'F', 12 => 'M', 19 => 'T',
        6 => 'G', 13 => 'N', 20 => 'U',
    ];

    public $controlCharacterMessage;
    private $_regex;

    public function init() {
        parent::init();
        $this->_regex = '/^[a-zA-Z]{6}[0-9]{2}[abcdehlmprstABCDEHLMPRST]{1}[0-9]{2}([a-zA-Z]{1}[0-9]{3})[a-zA-Z]{1}$/';
        if (!$this->message)
            $this->message = 'Codice fiscale non valido';
        if (!$this->controlCharacterMessage)
            $this->controlCharacterMessage = 'Carattere di controllo non valido';
    }

    public function validateAttribute($model, $attribute) {
        if (!preg_match($this->_regex, $model->$attribute)) :
            $this->addError($model, $attribute, $this->message);
        else :
            $code = $model->$attribute;
            $sum = 0;
            for ($i = 0; $i < 15; $i++) :
                $sum += ($i % 2 == 0 ? self::ODD[$code[$i]] : self::EVEN[$code[$i]]);
            endfor;
            if ($code[15] != (self::REST[$sum % 26]))
                $this->addError($model, $attribute, $this->controlCharacterMessage);
        endif;
    }

    public function clientValidateAttribute($model, $attribute, $view) {
        return
                "if (value.length>0 && !value.match({$this->_regex})) {" .
                "   messages.push('" . preg_replace('/\'/', "\'", $this->message) . "');" .
                "}";
    }

}
