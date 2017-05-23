<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\validators\Validator;

/**
 * Validatore per campi che contengono un codice fiscale.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0
 */
class FiscalCodeValidator extends Validator {

    private $_regex;

    public function init() {
        parent::init();
        $this->_regex = '/^[a-zA-Z]{6}[0-9]{2}[abcdehlmprstABCDEHLMPRST]{1}[0-9]{2}([a-zA-Z]{1}[0-9]{3})[a-zA-Z]{1}$/';
        if (!$this->message)
            $this->message = 'Codice fiscale non valido';
    }

    public function validateAttribute($model, $attribute) {
        if (!preg_match($this->_regex, $model->$attribute))
            $this->addError($model, $attribute, $this->message);
    }

    public function clientValidateAttribute($model, $attribute, $view) {
        return
                "if (value.length>0 && !value.match({$this->_regex})) {" .
                "   messages.push('" . preg_replace('/\'/', "\'", $this->message) . "');" .
                "}";
    }

}
