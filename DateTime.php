<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\base\BaseObject;

/**
 * Utilità per oggetti di classe DateTime.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.10
 */
class DateTime extends BaseObject {

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
     * Restituisce il numero del giorno corrispondente al nome breve indicato.
     * @param string $name Nome breve del giorno (es. lun)
     * @return integer Numero del giorno (1 per lunedì), null se non trovato
     */
    public static function GetDayByShortName($name) {
        return $name ? array_search($name, self::$_italianDaysShort) : null;
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
     * Restituisce la lista dei nomi dei mesi (tutti minuscoli)
     * @param boolean $indexed True per richiedere i numeri dei mesi (1-12) come chiavi dell'array
     * @return array Nomi dei mesi (minuscoli)
     */
    public static function GetMonths($indexed = false) {
        return $indexed ? self::$_italianMonths : array_values(self::$_italianMonths);
    }

    /**
     * Restituisce un array di oggetti \DateTime che rappresentano i giorni della settimana indicata.
     * La lista parte dal lunedì.
     * @param integer $year
     * @param integer $week
     * @return \DateTime[] Lista dei giorni della settimana
     */
    public static function GetWeekDays($year, $week) {
        $dto = new \DateTime();
        $dto->setISODate($year, $week);
        $days[] = clone $dto;
        for ($i = 1; $i <= 6; $i++) :
            $dto->add(new \DateInterval("P1D"));
            $days[] = clone $dto;
        endfor;
        return $days;
    }

    /**
     * Restituisce un oggetto DateTime corrispondente alla data di ieri.
     * @return \DateTime Data di ieri
     */
    public static function GetYesterday() {
        $a = new \DateTime;
        $a->sub(new \DateInterval('P1D'));
        return $a;
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
        if ($diff->d > 0) :
            if ($diff->m > 0 || $diff->y > 0) :
                $s[] = "{$diff->days}d";
            else :
                $s[] = "{$diff->d}d";
            endif;
        endif;
        if ($diff->h > 0)
            $s[] = "{$diff->h}h";
        if ($diff->i > 0)
            $s[] = "{$diff->i}m";
        if ($forceShowSeconds || ($diff->m == 0 && $diff->h == 0 && $diff->i == 0))
            $s[] = "{$diff->s}s";
        return join(' ', $s);
    }

    /**
     * Restituisce la durata dell'intervallo nella forma testuale (es. 6h). Viene mostrata un solo numero,
     * nell'unità di misura principale. Le sottounità vengono ignorate.
     * @param \DateInterval $diff Oggetto DateInterval
     * @return string Stinga che rappresenta la durata.
     */
    public static function GetDateIntervalShortString(\DateInterval $diff) {
        if (is_null($diff))
            return null;
        if ($diff->days == 0 && $diff->h == 0 && $diff->i == 0) :
            return $diff->s . 's';
        elseif ($diff->days == 0 && $diff->h == 0) :
            return $diff->i . 'm';
        elseif ($diff->days == 0) :
            return $diff->h . 'h';
        else :
            return $diff->days . 'd';
        endif;
    }

    /**
     * Restituisce una stringa in formato leggibile hh:mm:ss a partire da un numero di secondi.
     * Se il secondo parametro è true restituisce la stringa in formato XhYmZs.
     * @param integer $seconds Numero di secondi
     * @return string Stringa in formato hh:mm:ss oppure XhYmZs
     */
    public static function GetTimeStringFromSeconds($seconds, $humanReadable = false) {
        $s = '';
        $hours = (int) ($seconds / 3600);
        $minutes = (int) (($seconds % 3600) / 60);
        $seconds = (int) (($seconds % 3600) % 60);
        if ($humanReadable === true) :
            return ($hours > 0 ? $hours . 'h' : null) . ($minutes > 0 ? $minutes . 'm' : null) . ($seconds > 0 ? $seconds . 's' : null);
        else :
            return ($hours > 0 ? sprintf('%02d', $hours) : '00') . ':' . ($minutes > 0 ? sprintf('%02d', $minutes) : '00') . ':' . sprintf('%02d', $seconds);
        endif;
    }

    /**
     * Restituisce una stringa in formato leggibile hh:mm:ss a partire da un numero di secondi.
     * Se il secondo parametro è true restituisce la stringa in formato XhYmZs.
     * @param \DateInterval $diff Oggetto DateInterval
     * @return string Stringa in formato hh:mm:ss oppure XhYmZs
     */
    public static function GetTimeString(\DateInterval $diff, $humanReadable = false) {
        if ($humanReadable === true) :
            return ($diff->h > 0 ? $diff->h . 'h' : null) . ($diff->m > 0 ? $diff->m . 'm' : null) . ($diff->s > 0 ? $diff->s . 's' : null);
        else :
            return ($diff->h > 0 ? sprintf('%02d', $diff->h) : '00') . ':' . ($diff->m > 0 ? sprintf('%02d', $diff->m) : '00') . ':' . sprintf('%02d', $diff->s); 
        endif;
    }

    /**
     * Trasforma una data in formato MySQL (yyyy-mm-dd) in formato italiano (dd/mm/yyyy).
     * @param string $dateString Data in formato MySQL
     * @return string Data in formato italiano
     */
    public static function MySQLToItalian($dateString) {
        return join('/', array_reverse(preg_split('/-/', $dateString)));
    }

    /**
     * Trasforma una data in formato italiano (dd/mm/yyyy) in formato MySQL (yyyy-mm-dd).
     * @param string $dateString Data in formato italiano
     * @return string Data in formato MySQL
     */
    public static function ItalianToMySQL($dateString) {
        return join('-', array_reverse(preg_split('/\//', $dateString)));
    }

}
