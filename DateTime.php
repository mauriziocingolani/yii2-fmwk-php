<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\base\Object;

/**
 * Utilità per oggetti di classe DateTime.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0
 */
class DateTime extends Object {

    private static $_italianMonths = [
        1 => 'gennaio',
        2 => 'febbraio',
        3 => 'marzo',
        4 => 'aprile',
        5 => 'maggio',
        6 => 'giugno',
        7 => 'luglio',
        8 => 'agosto',
        9 => 'settembre',
        10 => 'ottobre',
        11 => 'novembre',
        12 => 'dicembre',
    ];
    private static $_italianDays = [
        1 => 'luned&igrave;',
        2 => 'marted&igrave;',
        3 => 'mercoled&igrave;',
        4 => 'gioved&igrave;',
        5 => 'venerd&igrave;',
        6 => 'sabato',
        7 => 'domenica',
    ];
    private static $_italianMonthsShort = [
        1 => 'gen',
        2 => 'feb',
        3 => 'mar',
        4 => 'apr',
        5 => 'mag',
        6 => 'giu',
        7 => 'lug',
        8 => 'ago',
        9 => 'set',
        10 => 'ott',
        11 => 'nov',
        12 => 'dic',
    ];
    private static $_italianDaysShort = [
        1 => 'lun',
        2 => 'mar',
        3 => 'mer',
        4 => 'gio',
        5 => 'ven',
        6 => 'sab',
        7 => 'dom',
    ];

    /**
     * Restituisce il nome (eventualmente breve) del giorno corrispondente al numero indicato (1 per lunedì).
     * @param integer $index Numero del giorno (lunedì=1)
     * @param boolean $short Se <code>true</code> restituisce il nome breve (3 lettere)
     * @return string Nome del giorno
     */
    public static function GetDay($index, $short = false) {
        return $short === true ? self::$_italianDaysShort[(int) $index] : self::$_italianDays[(int) $index];
    }

    /**
     * Restituisce il nome (eventualemente breve) del mese corrispondente al numero indicato (1 per gennaio).
     * @param integer $index Numero del mese (gennaio=1)
     * @param boolean $short Se <code>true</code> restituisce il nome breve (3 lettere)
     * @return string Nome del mese
     */
    public static function GetMonth($index, $short = false) {
        return $short === true ? self::$_italianMonthsShort[(int) $index] : self::$_italianMonths[(int) $index];
    }

    /**
     * Restituisce l'indice corrispondente al nome del mese (1 per gennaio).
     * @param string $monthString Nome del mese
     * @return integer Numero del mese
     */
    public static function GetMonthNumber($monthString) {
        return array_search(strtolower($monthString), self::$_italianMonths);
    }

    /**
     * Restituisce la durata dell'intervallo nella forma testuale (es. 1d 6h 34m 12s). I secondi vengono
     * mostrati solo se la durata è inferiore al minuto, oppure se esplicitamente richiesto tramite il 
     * parametro {@link $forceShowSeconds}.
     * @param \DateInterval $diff Oggetto DateInterval
     * @param type $showSeconds <code>true</code> per comprendere anche i secondi
     * @return string Stringa che rappresenta la durata
     */
    public static function GetDateIntervalString(\DateInterval $diff = null, $forceShowSeconds = false) {
        if ($diff == null)
            return null;
        $s = [];
        if ($diff->d > 0)
            $s[] = "{$diff->d}d";
        if ($diff->h > 0)
            $s[] = "{$diff->h}h";
        if ($diff->i > 0)
            $s[] = "{$diff->i}m";
        if ($forceShowSeconds || ($diff->m == 0 && $diff->h == 0 && $diff->i == 0))
            $s[] = "{$diff->s}s";
        return join(' ', $s);
    }

}