<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\base\Exception;
use yii\validators\Validator;

/**
 * Validatore per campi di tipo data-ora. INCOMPLETO
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
 * @see https://github.com/nepstor/yii2-datetime-compare-validator
 */
class DateTimeValidator extends Validator {

    public $compareAttribute;
    public $compareValue;
    public $operator = '=';
    public $allowEmpty = false;
    public $allowEmptyCompare = false;

    /**
     * Controlla che sia stata impostata almeno una proprietà tra compareAttribute e compareValue.
     * @throws Exception Se non è stao assegnato né compareAttribute né compareValue
     */
    public function init() {
        parent::init();
        if ($this->isEmpty($this->compareAttribute) && $this->isEmpty($this->compareValue))
            throw new Exception('Occorre impostare compareAttribute oppure compareValue');
    }

    /**
     * Esegue la validazione confrontando le due date e ora.
     * Il confronto non viene fatto se la proprietà $allowEmpty è true e l'attributo da validare è vuoto,
     * oppure se $allowEmptyCompare è true e l'attributo (o il valore) di confronto è vuoto.
     * @param \yii\base\Model $model Modello da validare
     * @param string $attribute Attributo da validare
     * @throws \yii\base\Exception Exception Se viene specificato un operatore non supportato (=, !=, <, <=, >, >=)
     */
    public function validateAttribute($model, $attribute) {
        $value = $model->$attribute;
        if ($this->allowEmpty && $this->isEmpty($value))
            return;
        $cA = $this->compareAttribute;
        if ($this->allowEmptyCompare && $this->isEmpty($model->$cA))
            return;
        # validazione
        $message = null;
        switch ($this->operator) {
            case '=':
                if (strtotime($value) != strtotime($model->$cA))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve esssere uguale a $ca";
                break;
            case '!=':
                if (strtotime($value) == strtotime($model->$cA))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve esssere diverso da $ca";
                break;
            case '>':
                if (strtotime($value) <= strtotime($model->$cA))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve esssere maggiore di $ca";
                break;
            case '>=':
                if (strtotime($value) < strtotime($model->$cA))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve esssere maggiore o uguale a $ca";
                break;
            case '<':
                if (strtotime($value) >= strtotime($model->$cA))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve esssere minore di $ca";
                break;
            case '<=':
                if (strtotime($value) > strtotime($model->$cA))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve esssere minore o uguale a $ca";
                break;
            default:
                throw new Exception("Operatore non supportato (\"$this->operator\")");
        }
        if (!empty($message))
            $this->addError($model, $attribute, $message);
    }

}
