<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;

/**
 * Definisce alcuni metodi utility per la gestione delle password.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0
 */
class PasswordHelper {

    /**
     * Esegue la criptatura della password indicata e predispone la stringa risultate
     * al salvataggio su database MySQL.
     * @param string $password Password da criptare
     * @return string Stringa da salvare nel database
     * @throws \yii\base\InvalidConfigException Se il parametro {@link Yii::$app->params['encryption_key']} non esiste
     */
    public static function DecryptFromMysql($password) {
        if (!isset(Yii::$app->params['encryption_key']))
            throw new \yii\base\InvalidConfigException('Parametro di configurazione mancante: encryption_key');
        return Yii::$app->getSecurity()->decryptByKey(base64_decode($password), Yii::$app->params['encryption_key']);
    }

    /**
     * Decodifica e quindi decritta la password recuperata dal database MySQL.
     * @param string $password Stringa salvata nel database
     * @return string Password decrittata
     * @throws \yii\base\InvalidConfigException Se il parametro {@link Yii::$app->params['encryption_key']} non esiste
     */
    public static function EncryptToMysql($password) {
        if (!isset(Yii::$app->params['encryption_key']))
            throw new \yii\base\InvalidConfigException('Parametro di configurazione mancante: encryption_key');
        return base64_encode(Yii::$app->getSecurity()->encryptByKey($password, Yii::$app->params['encryption_key']));
    }

    /**
     * Genera una password di lunghezza indicata (10 caratteri di default). Attualmente la password è una sottostringa
     * di un sha1, e pertanto contiene solo lettere minuscole e numeri.
     * @param int $length Lunghezza della password (default 10)
     * @return string Password generata
     */
    public static function GeneratePassword($length = 10) {
        if ((int) $length < 10)
            throw new \yii\base\InvalidParamException('La lunghezza della password non può essere inferiore a 10 caratteri');
        return substr(sha1(time()), 0, $length);
    }

}
