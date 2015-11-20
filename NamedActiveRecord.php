<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\db\ActiveRecord;

/**
 * Rappresenta una tabella di database con nome e cognome.
 * @property string $FirstName Nome della persona
 * @property string $LastName Cognome della persona
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
 */
abstract class NamedActiveRecord extends ActiveRecord {

    /**
     * Restituisce il nome completo della persona (nome e/o cognome)
     * oppure <code>null</code> se nessuno dei due è definito.
     * @return string Nome completo
     */
    public function getCompleteName() {
        if ($this->FirstName || $this->LastName) :
            return (strlen($this->FirstName) > 0 ? $this->FirstName : '') .
                    (strlen($this->FirstName) > 0 && strlen($this->LastName) > 0 ? ' ' : '') .
                    (strlen($this->LastName) > 0 ? $this->LastName : '');
        endif;
        return null;
    }

    /**
     * Restituisce il nome completo della persona (cognome e/o nome)
     * oppure <code>null</code> se nessuno dei due è definito.
     * @return string Nome completo (con cognome prima del nome)
     */
    public function getCompleteNameReversed() {
        if ($this->FirstName || $this->LastName) :
            return (strlen($this->LastName) > 0 ? $this->LastName : '') .
                    (strlen($this->FirstName) > 0 && strlen($this->LastName) > 0 ? ' ' : '') .
                    (strlen($this->FirstName) > 0 ? $this->LastName : '');
        endif;
        return null;
    }

}
