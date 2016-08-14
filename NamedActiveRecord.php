<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\db\ActiveRecord;

/**
 * Rappresenta una tabella di database con nome e cognome.
 * Presuppone che il nome degli attributi che rappresentano nome
 * e cognome siano 'FirstName' e 'LastName' rispettivamente.
 * Per modificare il nome degli attributi va sovrascritto il metodo {@link GetFields}.
 * @property string $FirstName Nome della persona
 * @property string $LastName Cognome della persona
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.2
 */
abstract class NamedActiveRecord extends ActiveRecord {

    /**
     * Restituisce il nome completo della persona (nome e/o cognome)
     * oppure <code>null</code> se nessuno dei due è definito.
     * @return string Nome completo
     */
    public function getCompleteName() {
        list($f, $l) = static::GetFields();
        if ($this->$f || $this->$l) :
            return (strlen($this->$f) > 0 ? $this->$f : '') .
                    (strlen($this->$f) > 0 && strlen($this->$l) > 0 ? ' ' : '') .
                    (strlen($this->$l) > 0 ? $this->$l : '');
        endif;
        return null;
    }

    /**
     * Restituisce il nome completo della persona (cognome e/o nome)
     * oppure <code>null</code> se nessuno dei due è definito.
     * @return string Nome completo (con cognome prima del nome)
     */
    public function getCompleteNameReversed() {
        list($f, $l) = static::GetFields();
        if ($this->$f || $this->$l) :
            return (strlen($this->$l) > 0 ? $this->$l : '') .
                    (strlen($this->$f) > 0 && strlen($this->$l) > 0 ? ' ' : '') .
                    (strlen($this->$f) > 0 ? $this->$l : '');
        endif;
        return null;
    }

    /**
     * Restituisce un array con due elementi stringa:
     * <ul>
     * <li>Nome della proprietà che rappresenta il nome
     * <li>Nome della proprietà che rappresenta il congome
     * </ul>
     * Può essere sovrascritto per modificare il nome di default delle due proprietà.
     * @return array Nomi degii attributi nome e cognome
     */
    public static function GetFields() {
        return ['FirstName', 'LastName'];
    }

}
