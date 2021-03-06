<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\db\Schema;

/**
 * Aggiunge costanti e funzionalità alla classe yii\db\Migration.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.12
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

    public static function TableOptionsLatin($engine = 'InnoDB', $charset = 'latin1', $collate = 'latin1_swedish_ci') {
        return "CHARACTER SET $charset COLLATE $collate ENGINE=$engine";
    }

    /**
     * Restituisce la definizione di tipo per un boolean (eventualmente NOT NULL).
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un boolean
     */
    protected static function typeBoolean($notNull = false) {
        return 'boolean' . ($notNull === true ? ' NOT NULL' : '');
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
     * Restituisce la definizione di tipo per un double  (eventualmente NOT NULL).
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un double
     */
    protected static function typeDouble($notNull = false) {
        return 'DOUBLE' . ($notNull === true ? ' NOT NULL' : '');
    }

    /**
     * Restituisce la definizione di tipo per un enum (eventualmente NOT NULL)
     * con le opzioni specificate.
     * La gestione degli apostrofi sulle singole opzioni viene fatta internamente,
     * quindi non è necessario provvedere a monte.
     * @param array $options Lista dei possibili valori del campo
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @param string $defaultValue Valore di default da impostare in caso di valore nullo
     * @return string Definizione di tipo per un enum
     */
    protected static function typeEnum(array $options, $notNull = false, $defaultValue = null) {
        return "ENUM('" . implode("','", array_map(function($s) {
                            return preg_replace('/\'/', "''", $s);
                        }, $options)) . "')" .
                ($notNull === true ? ' NOT NULL' : '') .
                (!is_null($defaultValue) ? " DEFAULT '" . preg_replace('/\'/', "''", $defaultValue) . "'" : '');
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
     * Restituisce la definizione di tipo per un float unsigned (eventualmente NOT NULL).
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un float unsigned
     */
    protected static function typeUnsignedFloat($notNull = false) {
        return 'FLOAT UNSIGNED' . ($notNull === true ? ' NOT NULL' : '');
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
     * Restituisce la definizione di tipo per un time (eventualmente NOT NULL).
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un time
     */
    protected static function typeTime($notNull = false) {
        return 'TIME' . ($notNull === true ? ' NOT NULL' : '');
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
     * Restituisce la definizione di tipo per un intero big senza segno (eventualmente NOT NULL).
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per intero big senza segno
     */
    protected static function typeUnsignedBigInteger($notNull = false) {
        return Schema::TYPE_BIGINT . ' UNSIGNED' . ($notNull === true ? ' NOT NULL' : '');
    }

    /**
     * Restituisce la definizione di tipo per un intero small senza segno (eventualmente NOT NULL).
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per intero small senza segno
     */
    protected static function typeUnsignedSmallInteger($notNull = false) {
        return Schema::TYPE_SMALLINT . ' UNSIGNED' . ($notNull === true ? ' NOT NULL' : '');
    }

    /**
     * Restituisce la definizione di tipo per un intero tiny senza segno (eventualmente NOT NULL).
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per intero tiny senza segno
     */
    protected static function typeUnsignedTinyInteger($notNull = false) {
        return 'TINYINT UNSIGNED' . ($notNull === true ? ' NOT NULL' : '');
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
     * Restituisce la definizione di tipo per un year (eventualmente NOT NULL).
     * @param type $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un year
     */
    protected static function typeYear($notNull = false) {
        return "YEAR(4)" . ($notNull === true ? ' NOT NULL' : '');
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
