<?php

namespace mauriziocingolani\yii2fmwkphp;

/**
 * Estende la classe Controller aggiungendo alcune funzionalità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
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

}
