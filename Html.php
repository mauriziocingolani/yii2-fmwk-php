<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;

/**
 * Estende la classe yii\helpers\Html aggiungendo metodi e funzionalità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.4
 */
class Html extends \yii\helpers\Html {

    /**
     * Genera un tag <a> con i parametri assegnati. E' un wrapper per il metodo {@link yii\helpers\Html::a}
     * che aggiunge l'icona specificata prima del testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome
     * @param string $text Testo del link
     * @param string $url Url del link
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function faa($icon, $text = null, $url = null, array $options = array()) {
        $t = '<i class="fa fa-' . $icon . '"></i>' . ($text && strlen($text) > 0 ? ' ' . $text : '');
        return self::a($t, $url, $options);
    }

    /**
     * Genera un tag <a> con i parametri assegnati. E' un wrapper per il metodo {@link yii\helpers\Html::a}
     * che aggiunge l'icona specificata (tipo Brand) prima del testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome (Brand)
     * @param string $text Testo del link
     * @param string $url Url del link
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function faba($icon, $text = null, $url = null, array $options = array()) {
        $t = '<i class="fab fa-' . $icon . '"></i>' . ($text && strlen($text) > 0 ? ' ' . $text : '');
        return self::a($t, $url, $options);
    }
    
        /**
     * Genera un tag <a> con i parametri assegnati. E' un wrapper per il metodo {@link yii\helpers\Html::a}
     * che aggiunge l'icona specificata (tipo Brand) dopo il testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome (Brand)
     * @param string $text Testo del link
     * @param string $url Url del link
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function fabar($icon, $text, $url = null, array $options = array()) {
        $t = $text . ' <i class="fab fa-' . $icon . '"></i>';
        return self::a($t, $url, $options);
    }

    /**
     * Genera un tag <a> con i parametri assegnati. E' un wrapper per il metodo {@link yii\helpers\Html::a}
     * che aggiunge l'icona specificata (tipo Light) prima del testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome (Light)
     * @param string $text Testo del link
     * @param string $url Url del link
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function fala($icon, $text = null, $url = null, array $options = array()) {
        $t = '<i class="fal fa-' . $icon . '"></i>' . ($text && strlen($text) > 0 ? ' ' . $text : '');
        return self::a($t, $url, $options);
    }
    
        /**
     * Genera un tag <a> con i parametri assegnati. E' un wrapper per il metodo {@link yii\helpers\Html::a}
     * che aggiunge l'icona specificata (tipo Light) dopo il testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome (Light)
     * @param string $text Testo del link
     * @param string $url Url del link
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function falar($icon, $text, $url = null, array $options = array()) {
        $t = $text . ' <i class="fal fa-' . $icon . '"></i>';
        return self::a($t, $url, $options);
    }

    /**
     * Genera un tag <a> con i parametri assegnati. E' un wrapper per il metodo {@link yii\helpers\Html::a}
     * che aggiunge l'icona specificata (tipo Regular) prima del testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome (Regular)
     * @param string $text Testo del link
     * @param string $url Url del link
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function fara($icon, $text = null, $url = null, array $options = array()) {
        $t = '<i class="far fa-' . $icon . '"></i>' . ($text && strlen($text) > 0 ? ' ' . $text : '');
        return self::a($t, $url, $options);
    }
    
        /**
     * Genera un tag <a> con i parametri assegnati. E' un wrapper per il metodo {@link yii\helpers\Html::a}
     * che aggiunge l'icona specificata (tipo Regular) dopo il testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome (Regular)
     * @param string $text Testo del link
     * @param string $url Url del link
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function farar($icon, $text, $url = null, array $options = array()) {
        $t = $text . ' <i class="far fa-' . $icon . '"></i>';
        return self::a($t, $url, $options);
    }

    /**
     * Genera un tag <a> con i parametri assegnati. E' un wrapper per il metodo {@link yii\helpers\Html::a}
     * che aggiunge l'icona specificata (tipo Solid) prima del testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome (Solid)
     * @param string $text Testo del link
     * @param string $url Url del link
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function fasa($icon, $text = null, $url = null, array $options = array()) {
        $t = '<i class="fas fa-' . $icon . '"></i>' . ($text && strlen($text) > 0 ? ' ' . $text : '');
        return self::a($t, $url, $options);
    }

    /**
     * Genera un tag <a> con i parametri assegnati. E' un wrapper per il metodo {@link yii\helpers\Html::a}
     * che aggiunge l'icona specificata (tipo Solid) dopo il testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome (Solid)
     * @param string $text Testo del link
     * @param string $url Url del link
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function fasar($icon, $text, $url = null, array $options = array()) {
        $t = $text . ' <i class="fas fa-' . $icon . '"></i>';
        return self::a($t, $url, $options);
    }

    /**
     * Genera un tag <a> con href di tipo 'mailto:'. E' un wrapper per il metodo {@link yii\helpers\Html::mailto}
     * che aggiunge l'icona specificata prima del testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome
     * @param string $text Testo del link
     * @param string $email Indirizzo email
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function famailto($icon, $text = null, $email = null, array $options = array()) {
        $t = '<i class="fa fa-' . $icon . '"></i>' . ($text && strlen($text) > 0 ? ' ' . $text : '');
        return self::mailto($t, $email, $options);
    }

    /**
     * Genera un tag <input> di tipo hidden con il token csfr da inserire nelle form "manuali".
     * @return string Tag generato
     */
    public static function csrfInput() {
        return self::hiddenInput('_csrf', Yii::$app->getRequest()->getCsrfToken());
    }

}
