<?php

namespace mauriziocingolani\yii2fmwkphp;

/**
 * Estende yii\db\ActiveRecord aggiungendo funzionalità e utilità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord {

    /**
     * Converte la data dal formato 'Y-m-d'  a 'd/m/Y'.
     * @param string $mysqlDate Data in formato MySQL
     * @return string Data in formato italiano
     */
    public static function MysqlToItalianDate($mysqlDate) {
        return join('/', array_reverse(preg_split("/[-]/", $mysqlDate)));
    }

    /**
     * Converte la data dal formato 'd/m/Y'  a 'Y-m-d'.
     * @param string $italianDate Data in formato italiano
     * @return string Data in formato MySQL
     */
    public static function ItalianToMysqlDate($italianDate) {
        return join('-', array_reverse(preg_split("/[\/]/", $italianDate)));
    }

}
