<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;

/**
 * Estende la classe Controller aggiungendo alcune funzionalità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.2
 */
class Controller extends \yii\web\Controller {

    /**
     * Implementazione base delle regole di accesso: tutto consentito.
     * @return array Behaviors
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [['allow' => true]],
            ],
        ];
    }

    /**
     * Questo metodo deve rappresentare il valore restituito dal metodo {@link behaviors()} dei controller
     * che implementano regole restrittive di accesso. L'array passato come parametro va a sovrascrivere
     * l'elemento ['access']['rules'] della proprietà {@link $_behaviors} del controller.
     * @param array $rules Regole di accesso
     * @return array Regole di accesso (come behavior)
     */
    public function accessRules(array $rules) {
        $default = self::behaviors();
        $default['access']['rules'] = $rules;
        return $default;
    }

    /**
     * Verifica se il debug è attivo e se l'ip dell'utente attuale rientra in quelli consentiti per debug.
     * @return boolean True se l'applicazione ha il debug attivato
     */
    public function getIsDebug() {
        return isset(Yii::$app->modules['debug']) && in_array(Yii::$app->request->userIP, Yii::$app->modules['debug']->allowedIPs);
    }

}
