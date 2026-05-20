<?php

namespace mauriziocingolani\yii2fmwkphp;

use mauriziocingolani\yii2fmwkphp\ActiveRecord;

/**
 * Rappresenta una tabella di database con nome e cognome.
 * Presuppone che il nome degli attributi che rappresentano nome
 * e cognome siano 'FirstName' e 'LastName' rispettivamente.
 * Per modificare il nome degli attributi va sovrascritto il metodo {@link GetFields}.
 * @property string $FirstName Nome della persona
 * @property string $LastName Cognome della persona
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.5
 */
abstract class NamedActiveRecord extends ActiveRecord {

    /**
     * Restituisce il nome completo della persona (nome e/o cognome)
     * oppure <code>null</code> se nessuno dei due è definito.
     * @return string Nome completo
     */
    public function getCompleteName() {
        list($f, $l) = static::GetFields();
        $first = (string) ($this->$f ?? '');
        $last = (string) ($this->$l ?? '');
        $name = trim($first . ' ' . $last);
        return $name !== '' ? $name : null;
    }

    /**
     * Restituisce il nome completo della persona (cognome e/o nome)
     * oppure <code>null</code> se nessuno dei due è definito.
     * @return string Nome completo (con cognome prima del nome)
     */
    public function getCompleteNameReversed() {
        list($f, $l) = static::GetFields();
    $first = (string) ($this->$f ?? '');
        $last = (string) ($this->$l ?? '');
        $name = trim($last . ' ' . $first);
        return $name !== '' ? $name : null;
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
