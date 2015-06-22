<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\db\Schema;

/**
 * Aggiunge costanti e funzionalità alla classe yii\db\Migration.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0
 */
class Migration extends \yii\db\Migration {

    public static $tableOptions;
    public static $primaryKey;

    public function init() {
        if (!self::$tableOptions)
            self::$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        if (!self::$primaryKey)
            self::$primaryKey = Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL AUTO_INCREMENT';
    }

    protected static function typeUnsignedInteger($notNull = false) {
        return Schema::TYPE_INTEGER . ' UNSIGNED' . ($notNull === true ? ' NOT NULL' : '');
    }

    protected static function typeVarchar($length, $notNull = false) {
        return "VARCHAR($length)" . ($notNull === true ? ' NOT NULL' : '');
    }

}
