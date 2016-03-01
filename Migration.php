<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\db\Schema;

/**
 * Aggiunge costanti e funzionalità alla classe yii\db\Migration.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.3
 */
class Migration extends \yii\db\Migration {

    const PK = 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT';

    /**
     * Stringa con opzioni per la creazione delle tabelle. DEPRECATO: utilizzare {@link self::TableOptions}
     * @deprecated since version 1.0.2
     */
    public static $tableOptions;

    /**
     * Stringa con la definizione del tipo di campo PK. DEPRECATO: utilizzare {@link self::Pk}
     * @deprecated since version 1.0.2
     */
    public static $primaryKey;

    /**
     * Inizializza le variabili statiche.
     */
    public function init() {
        if (!self::$tableOptions)
            self::$tableOptions = self::TableOptions();
        if (!self::$primaryKey)
            self::$primaryKey = self::PK;
    }

    public static function Pk($column = null) {
        if ($column) :
            return "PRIMARY KEY ($column)";
        else :
            return self::PK;
        endif;
    }

    public static function TableOptions($engine = 'InnoDB', $charset = 'utf8', $collate = 'utf8_unicode_ci') {
        return "CHARACTER SET $charset COLLATE $collate ENGINE=$engine";
    }

    /**
     * Restituisce la definizione di tipo per un char di lunghezza indicata (eventualmente NOT NULL).
     * @param inteerg $length Numero di caratteri del campo
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un char
     */
    protected static function typeChar($length, $notNull = false) {
        return "CHAR($length)" . ($notNull === true ? ' NOT NULL' : '');
    }

    /**
     * Restituisce la definizione di tipo per un date o un datetime (eventualmente NOT NULL).
     * @param boolean $time Se True richede la creazione di un datetime
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un date o un datetime
     */
    protected static function typeDate($time = false, $notNull = false) {
        return 'DATE' . ($time ? 'TIME' : '') . ($notNull === true ? ' NOT NULL' : '');
    }

    /**
     * Restituisce la definizione di tipo per un float  (eventualmente NOT NULL).
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un float
     */
    protected static function typeFloat($notNull = false) {
        return 'FLOAT' . ($notNull === true ? ' NOT NULL' : '');
    }

    /**
     * Restituisce la definizione di tipo per un text  (eventualmente NOT NULL).
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un text
     */
    protected static function typeText($notNull = false) {
        return 'TEXT' . ($notNull === true ? ' NOT NULL' : '');
    }

    /**
     * Restituisce la definizione di tipo per un intero senza segno (eventualmente NOT NULL).
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per intero senza segno
     */
    protected static function typeUnsignedInteger($notNull = false) {
        return Schema::TYPE_INTEGER . ' UNSIGNED' . ($notNull === true ? ' NOT NULL' : '');
    }

    /**
     * Restituisce la definizione di tipo per un varchar di lunghezza indicata (eventualmente NOT NULL).
     * @param type $length Numero di caratteri del campo
     * @param type $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un varchar
     */
    protected static function typeVarchar($length, $notNull = false) {
        return "VARCHAR($length)" . ($notNull === true ? ' NOT NULL' : '');
    }

    /**
     * Fa override del metodo della superclass aggiungendo semplicemente la possibilità di non
     * specificare le colonne di destinazione {@link $refColumns} in caso siano uguali a quelle di 
     * origine {@link $columns}.
     * @param string $name Nome della foreign key
     * @param string $table Tabella
     * @param string $columns Colonne o colonna interessate
     * @param string $refTable Tabella di rifierimento
     * @param string $refColumns Colonne o colonna di riferimento
     * @param string $delete Azione in caso di eliminazione di record con riferimenti
     * @param string $update Azione in caso di modifica di record con riferimenti
     */
    public function addForeignKey($name, $table, $columns, $refTable, $refColumns = null, $delete = null, $update = null) {
        parent::addForeignKey($name, $table, $columns, $refTable, $refColumns ? $refColumns : $columns, $delete, $update);
    }

}
