<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;
use yii\base\InvalidParamException;

/**
 * Definisce alcuni metodi utility per la gestione delle password.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
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
     * Genera una password di lunghezza indicata utilizzando lettere minuscole e maiuscole, cifre
     * ed eventualmente i caratteri speciali $@#&%
     * @param int $length Lunghezza della password (da 1 a 255, default 10)
     * @param boolean $allowSpecialCharacters Se true inserisce i caratteri speciali $@#&%
     * @return string Password generata
     * @throws Exception Se la lunghezza indicata è minore di 1 o maggiore di 255
     */
    public static function GeneratePassword($length = 10, $allowSpecialCharacters = false) {
        if ((int) $length <= 0 || (int) $length >= 255)
            throw new InvalidParamException('La lunghezza della password non può essere inferiore a 10 caratteri');
        $alphabet = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        if ($allowSpecialCharacters)
            $alphabet = array_merge($alphabet, array('$', '@', '#', '&', '%'));
        shuffle($alphabet);
        return implode('', array_slice($alphabet, 0, $length));
    }

}
