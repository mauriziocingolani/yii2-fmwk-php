<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\helpers\FileHelper as Helper;

/**
 * Definisce alcuni metodi utility per la gestione dei files.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0
 */
class FileHelper extends Helper {

    /**
     * @author Jeffrey Sambells
     * @link http://jeffreysambells.com/2012/10/25/human-readable-filesize-php
     * @param integer $bytes Numero di bytes
     * @param integer $decimals Numero di cifre decimali da restituire.
     * @return string Dimensione del file in forma leggibile
     */
    public static function GetHumanReadableFileSize($bytes, $decimals = 2) {
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

}
