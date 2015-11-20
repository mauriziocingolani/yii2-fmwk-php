<?php

namespace mauriziocingolani\yii2fmwkphp;

/**
 * Estende yii\db\ActiveRecord aggiungendo funzionalità e utilità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
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

    /**
     * Converte la data dal formato 'Y-m-d H:i[:s]'  a 'd/m/Y H:i[:s]'.
     * @param string $string Data e ora in formato MySQL
     * @param boolean $showSeconds True per mostrare anche i secondi
     * @return string Data e ora in formato italiano
     */
    public static function MysqlToItalianDateTime($string, $showSeconds = false) {
        $split = preg_split('/[ ]/', $string);
        return self::MysqlToItalianDate($split[0]) . ' ' . substr($split[1], 0, $showSeconds ? 8 : 5);
    }

    /**
     * Converte la data dal formato 'd/m/Y H:i[:s])'  a 'Y-m-d H:i:s'. La presenza dei secondi
     * dipende dalla presenza nella stringa da convertire ('00' in caso di assenza).
     * @param string $string Data e ora in formato italiano
     * @return string Data in formato MySQL
     */
    public static function ItalianToMysqlDateTime($string) {
        $split = preg_split('/[ ]/', $string);
        return self::ItalianToMysqlDate($split[0]) . ' ' . $split[1] . (count($split) == 3 ? ':' . $split[2] : ':00');
    }

}
