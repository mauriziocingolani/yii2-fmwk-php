<?php

namespace mauriziocingolani\yii2fmwkphp;

/**
 * Description of Html
 *
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0
 */
class Html extends \yii\helpers\Html {

    public static function faa($icon, $text = null, $url = null, array $options = array()) {
        $t = '<i class="fa fa-' . $icon . '"></i>' . ($text && strlen($text) > 0 ? ' ' . $text : '');
        return self::a($t, $url, $options);
    }

    public static function famailto($icon, $text = null, $email = null, array $options = array()) {
        $t = '<i class="fa fa-' . $icon . '"></i>' . ($text && strlen($text) > 0 ? ' ' . $text : '');
        return self::mailto($t, $email, $options);
    }

}
