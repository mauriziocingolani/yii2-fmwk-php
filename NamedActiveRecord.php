<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\db\ActiveRecord;

/**
 * Rappresenta una tabella di database con nome e cognome.
 * @property string $FirstName Nome della persona
 * @property string $LastName Cognome della persona
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0
 */
abstract class NamedActiveRecord extends ActiveRecord {

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
