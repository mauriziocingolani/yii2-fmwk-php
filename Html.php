<?php

namespace mauriziocingolani\yii2fmwkphp;

/**
 * Estende la classe yii\helpers\Html aggiungendo metodi e funzionalità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
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
     * che aggiunge l'icona specificata dopo il testo.
     * Presuppone che venga caricato il file css di FontAwesome.
     * @param string $icon Icona FontAwesome
     * @param string $text Testo del link
     * @param string $url Url del link
     * @param array $options Opzioni HTML
     * @return string Tag generato
     */
    public static function faar($icon, $text, $url = null, array $options = array()) {
        $t = $text . ' <i class="fa fa-' . $icon . '"></i>';
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

}
