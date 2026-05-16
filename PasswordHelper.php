<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;
use yii\base\{
    InvalidArgumentException,
    InvalidConfigException
};

/**
 * Definisce alcuni metodi utility per la gestione delle password.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.2
 */
class PasswordHelper {

    /**
     * Decodifica e quindi decritta la password recuperata dal database MySQL.
     * @param string $password Stringa salvata nel database
     * @return string Password decrittata
     * @throws \yii\base\InvalidConfigException Se il parametro {@link Yii::$app->params['encryption_key']} non esiste
     */
    public static function DecryptFromMysql($password) {
        if (!isset(Yii::$app->params['encryption_key']))
            throw new InvalidConfigException('Parametro di configurazione mancante: encryption_key');
        $key = Yii::$app->params['encryption_key'];
        if (!is_string($key) || strlen($key) < 16)
            throw new \yii\base\InvalidConfigException('encryption_key non valida (minimo 16 caratteri).');
        return Yii::$app->getSecurity()->decryptByKey(base64_decode($password), Yii::$app->params['encryption_key']);
    }

    /**
     * Esegue la criptatura della password indicata e predispone la stringa risultate
     * al salvataggio su database MySQL.
     * @param string $password Password da criptare
     * @return string Stringa da salvare nel database
     * @throws \yii\base\InvalidConfigException Se il parametro {@link Yii::$app->params['encryption_key']} non esiste
     */
    public static function EncryptToMysql($password) {
        if (!isset(Yii::$app->params['encryption_key']))
            throw new InvalidConfigException('Parametro di configurazione mancante: encryption_key');
        $key = Yii::$app->params['encryption_key'];
        if (!is_string($key) || strlen($key) < 16)
            throw new \yii\base\InvalidConfigException('encryption_key non valida (minimo 16 caratteri).');
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
            throw new InvalidArgumentException('La lunghezza della password deve essere compresa tra 1 e 254 caratteri.');
        $alphabet = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        if ($allowSpecialCharacters)
            $alphabet = array_merge($alphabet, array('$', '@', '#', '&', '%'));
        $max = count($alphabet) - 1;
        $password = '';
        for ($i = 0; $i < $length; $i++) :
            $password .= $alphabet[random_int(0, $max)];
        endfor;
        return $password;
    }
}
