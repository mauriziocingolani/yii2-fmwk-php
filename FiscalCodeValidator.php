<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\validators\Validator;

/**
 * Validatore per campi che contengono un codice fiscale.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.8
 * @link http://blog.marketto.it/2016/01/regex-validazione-codice-fiscale-con-omocodia/ Fonte regex di validazione
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
        $this->_regex = '/^(?:[A-Z][AEIOU][AEIOUX]|[B-DF-HJ-NP-TV-Z]{2}[A-Z]){2}(?:[\dLMNP-V]{2}(?:[A-EHLMPR-T](?:[04LQ][1-9MNP-V]|[15MR][\dLMNP-V]|[26NS][0-8LMNP-U])|[DHPS][37PT][0L]|[ACELMRT][37PT][01LM]|[AC-EHLMPR-T][26NS][9V])|(?:[02468LNQSU][048LQU]|[13579MPRTV][26NS])B[26NS][9V])(?:[A-MZ][1-9MNP-V][\dLMNP-V]{2}|[A-M][0L](?:[1-9MNP-V][\dLMNP-V]|[0L][1-9MNP-V]))[A-Z]$/i';
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
                $sum += ($i % 2 == 0 ? self::ODD[strtoupper($code[$i])] : self::EVEN[strtoupper($code[$i])]);
            endfor;
            if (strtoupper($code[15]) != (self::REST[$sum % 26]))
                $this->addError($model, $attribute, $this->controlCharacterMessage);
        endif;
    }

    public function validate($value, &$error = null) {
        if (!preg_match($this->_regex, $value)) :
            return false;
        else :
            $code = $value;
            $sum = 0;
            for ($i = 0; $i < 15; $i++) :
                $sum += ($i % 2 == 0 ? self::ODD[strtoupper($code[$i])] : self::EVEN[strtoupper($code[$i])]);
            endfor;
            if (strtoupper($code[15]) != (self::REST[$sum % 26]))
                return false;
        endif;
        return true;
    }

    public function clientValidateAttribute($model, $attribute, $view) {
        $even = json_encode(self::EVEN);
        $odd = json_encode(self::ODD);
        $rest = json_encode(self::REST);
        return <<<JS
                if(value.length>0) {
                        if (!value.match({$this->_regex})) {
                                messages.push("$this->message");
                        } else {
                                var sum=0,even=$even,odd=$odd,rest=$rest;
                                for(i=0;i<15;i++) {
                                        sum+= (i%2==0 ? odd[value[i].toUpperCase()]:even[value[i].toUpperCase()]);
                                }
                                if(rest[sum%26]!=value[15].toUpperCase()) {
                                        messages.push("$this->controlCharacterMessage");
                                }  
                        }
                }
        JS;
    }

}
