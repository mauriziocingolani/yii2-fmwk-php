<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\db\ActiveRecord;

/**
 * Rappresenta una tabella di database con nome e cognome.
 * @property string $FirstName Nome della persona
 * @property string $LastName Cognome della persona
 */
abstract class NamedActiveRecord extends ActiveRecord {

//    public $FirstName;
//    public $LastName;

    /**
     * Restituisce il nome completo della persona (nome e cognome).
     * @return string Nome completo
     */
    public function getCompleteName() {
        return $this->FirstName . ' ' . $this->LastName;
    }

    /**
     * Restituisce il nome completo della persona (cognome e nome).
     * @return string Nome completo (con cognome prima del nome)
     */
    public function getCompleteNameReversed() {
        return $this->LastName . ' ' . $this->FirstName;
    }

}
