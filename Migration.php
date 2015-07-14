<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\db\Schema;

/**
 * Aggiunge costanti e funzionalità alla classe yii\db\Migration.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0.1
 */
class Migration extends \yii\db\Migration {

    /** Stringa con opzioni per la creazione delle tabelle */
    public static $tableOptions;

    /** Stringa con la definizione del tipo di campo PK */
    public static $primaryKey;

    /**
     * Inizializza le variabili statiche.
     */
    public function init() {
        if (!self::$tableOptions)
            self::$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        if (!self::$primaryKey)
            self::$primaryKey = Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL AUTO_INCREMENT';
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
     * @param boolean $length Se True richede la creazione di un datetime
     * @param boolean $notNull True per richiedere che il campo sia NOT NULL
     * @return string Definizione di tipo per un date o un datetime
     */
    protected static function typeDate($time = false, $notNull = false) {
        return "DATE" . ($time ? 'TIME' : '') . ($notNull === true ? ' NOT NULL' : '');
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

}
