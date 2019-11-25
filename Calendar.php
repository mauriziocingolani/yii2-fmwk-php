<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\base\BaseObject;

/**
 * Questa classe permette di costruire un calendario in html.
 * Fornisce i dati relativi ai giorni e alla settimane.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
 */
class Calendar extends BaseObject {

    private $_year;
    private $_month;
    private $_firstDayOfMonth;
    private $_lastDayOfMonth;
    private $_firstWeek;
    private $_firstWeekYear;
    private $_firstDayOfFirstWeek;
    private $_lastWeek;
    private $_lastWeekYear;
    private $_lastDayOfLastWeek;
    private $_numberOfWeeks;

    public function __construct($year, $month) {
        $this->_year = $year;
        $this->_month = $month;
    }

    /* Eventi */
    /* Metodi */
    /* Getters-Setters */

    /**
     * Restituisce il primo giorno del mese come oggetto DateTime.
     * Specificando il formato viene restituita la stringa corrispondente.
     * @param string $format Formato della data (opzionale)
     * @return \DateTime|string Primo giorno del mese
     */
    public function getFirstDayOfMonth(string $format = null) {
        if (is_null($this->_firstDayOfMonth))
            $this->_firstDayOfMonth = new \DateTime(date(sprintf('%d-%02d-01', $this->_year, $this->_month)));
        if ($format)
            return $this->_firstDayOfMonth->format($format);
        return clone $this->_firstDayOfMonth;
    }

    /**
     * Restituisce l'ultimo giorno del mese come oggetto DateTime.
     * Specificando il formato viene restituita la stringa corrispondente.
     * @param string $format Formato della data (opzionale)
     * @return \DateTime|string Ultimo giorno del mese
     */
    public function getLastDayOfMonth(string $format = null) {
        if (is_null($this->_lastDayOfMonth))
            $this->_lastDayOfMonth = new \DateTime(date('Y-m-t', strtotime($this->getFirstDayOfMonth('Y-m-d'))));
        if ($format)
            return $this->_lastDayOfMonth->format($format);
        return clone $this->_lastDayOfMonth;
    }

    /**
     * Restituisce il numero della prima settimana del mese.
     * @return integer Prima settimana
     */
    public function getFirstWeek(): int {
        if (is_null($this->_firstWeek))
            $this->_firstWeek = (int) $this->getFirstDayOfMonth('W');
        return $this->_firstWeek;
    }

    /**
     * Restituisce l'anno della prima settimana del mese.
     * @return integer Anno della prima settimana
     */
    public function getFirstWeekYear(): int {
        if (is_null($this->_firstWeekYear))
            $this->_firstWeekYear = (int) $this->getFirstDayOfMonth('Y');
        return $this->_firstWeekYear;
    }

    /**
     * Restituisce il numero dell'ultima settimana del mese.
     * @return integer Ultima settimana
     */
    public function getLastWeek(): int {
        if (is_null($this->_lastWeek))
            $this->_lastWeek = (int) $this->getLastDayOfMonth('W');
        return $this->_lastWeek;
    }

    /**
     * Restituisce il numero dell'ultima settimana del mese da usare oer il loop sulle settimane.
     * Se l'ultima settimana di dicembre è la prima di gennaio viene restituito 53 in modo da non compromettere
     * il funzionamento del loop sulle settimane del mese.
     * @return integer Ultima settimana per loop
     */
    public function getLastWeekForLoop(): int {
        if ($this->_month == 12 && $this->getLastWeek() == 1)
            return 53;
        return $this->getLastWeek();
    }

    /**
     * Restituisce l'anno dell'ultima settimana del mese.
     * @return integer Anno dell'ultima settimana
     */
    public function getLastWeekYear(): int {
        if (is_null($this->_lastWeekYear))
            $this->_lastWeekYear = (int) ($this->getLastWeek() == 1 ? $this->_year + 1 : $this->_year);
        return $this->_lastWeekYear;
    }

    /**
     * Restituisce il primo giorno della prima settimana del mese come oggetto DateTime.
     * Specificando il formato viene restituita la stringa corrispondente.
     * @param string $format Formato della data (opzionale)
     * @return \DateTime|string Primo giorno della prima settimana del mese
     */
    public function getFirstDayOfFirstWeek(string $format = null) {
        if (is_null($this->_firstDayOfFirstWeek))
            $this->_firstDayOfFirstWeek = (new \DateTime)->setISODate($this->getFirstWeekYear(), $this->getFirstWeek());
        if ($format)
            return $this->_firstDayOfFirstWeek->format($format);
        return clone $this->_firstDayOfFirstWeek;
    }

    /**
     * Restituisce l'ultimo giorno dell'ultima settimana del mese come oggetto DateTime.
     * Specificando il formato viene restituita la stringa corrispondente.
     * @param string $format Formato della data (opzionale)
     * @return \DateTime|string Ultimo giorno dell'ultima settimana del mese
     */
    public function getLastDayOfLastWeek(string $format = null) {
        if (is_null($this->_lastDayOfLastWeek)) :
            $date = (new \DateTime)->setISODate($this->getLastWeekYear(), $this->getLastWeek());
            $this->_lastDayOfLastWeek = $date->add(new \DateInterval('P6D'));
        endif;
        if ($format)
            return $this->_lastDayOfLastWeek->format($format);
        return clone $this->_lastDayOfLastWeek;
    }

    /* Metodi statici */
}
