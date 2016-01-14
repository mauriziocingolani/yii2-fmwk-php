<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\base\Exception;
use yii\validators\Validator;

/**
 * Validatore per campi di tipo data-ora. INCOMPLETO
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0
 * @see https://github.com/nepstor/yii2-datetime-compare-validator
 */
class DateTimeValidator extends Validator {

    public $compareAttribute;
    public $compareValue;
    public $operator = '=';
    public $allowEmpty = false;
    public $message;

    /**
     * Controlla che sia stata impostata almeno una proprietà tra compareAttribute e compareValue.
     * @throws Exception Se non è stao assegnato né compareAttribute né compareValue
     */
    public function init() {
        parent::init();
        if ($this->isEmpty($this->compareAttribute) && $this->isEmpty($this->compareValue))
            throw new Exception('Occorre impostare compareAttribute oppure compareValue');
    }

    public function validateAttribute($model, $attribute) {
        $value = $model->$attribute;
        if ($this->allowEmpty && $this->isEmpty($value))
            return null;
        $cA = $this->compareAttribute;
        if ($this->isEmpty($model->$cA))
            return null;
        # validazione
        $message = null;
        switch ($this->operator) {
            case '>':
                if (strtotime($value) <= strtotime($model->$cA))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve esssere maggiore di $ca";
                break;
            default:
                throw new Exception("Operatore non supportato (\"$this->operator\")");
        }
        if (!empty($message))
            $this->addError($model, $attribute, $message);
    }

}
