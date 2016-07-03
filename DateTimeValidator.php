<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\base\Exception;
use yii\validators\Validator;

/**
 * Validatore per campi di tipo data-ora. INCOMPLETO
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.2
 * @see https://github.com/nepstor/yii2-datetime-compare-validator
 */
class DateTimeValidator extends Validator {

    const MODE_ITALIAN_DATE = 'italian_date';

    public $compareAttribute;
    public $compareValue;
    public $operator = '=';
    public $allowEmpty = false;
    public $allowEmptyCompare = false;
    public $mode = '';

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
        # mode
        $compare = $model->$cA;
        if ($this->mode == self::MODE_ITALIAN_DATE) :
            $value = DateTime::ItalianToMySQL($value);
            $compare = DateTime::ItalianToMySQL($model->$cA);
        endif;
        # validazione
        $message = null;
        switch ($this->operator) {
            case '=':
                if (strtotime($value) != strtotime($compare))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve essere uguale a $cA";
                break;
            case '!=':
                if (strtotime($value) == strtotime($compare))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve essere diverso da $cA";
                break;
            case '>':
                if (strtotime($value) <= strtotime($compare))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve essere maggiore di $cA";
                break;
            case '>=':
                if (strtotime($value) < strtotime($compare))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve essere maggiore o uguale a $cA";
                break;
            case '<':
                if (strtotime($value) >= strtotime($compare))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve essere minore di $cA";
                break;
            case '<=':
                if (strtotime($value) > strtotime($compare))
                    $message = $this->message !== null ? $this->message : "Il valore di $attribute deve essere minore o uguale a $cA";
                break;
            default:
                throw new Exception("Operatore non supportato (\"$this->operator\")");
        }
        if (!empty($message))
            $this->addError($model, $attribute, $message);
    }

}
