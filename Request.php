<?php

namespace mauriziocingolani\yii2fmwkphp;

/**
 * Estende il componente di sistema con varie funzionalità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
 */
class Request extends \yii\web\Request {

    /** Split su array dei numeri che compongono l'ip utente */
    private $_userIp;

    /**
     * Verifica se l'ip utente è compreso nella lista passata come parametro (array o valore singolo).
     * Oltre agli ip completi (4 numeri) accetta anche la wilcard * per le sottoreti.
     * @param mixed $ips Lista degli ip (array o valore singolo)
     * @return boolean True se l'ip utente è compreso nella lista, false altrimenti
     */
    public function isIpIn($ips) {
        if (!is_array($ips))
            $ips = [$ips];
        foreach ($ips as $ip) :
            if ($this->_checkIp($ip) === true)
                return true;
        endforeach;
        return false;
    }

    private function _checkIp($ip) {
        if (!$this->_userIp) :
            $this->_userIp = preg_split('/\./', $this->userIP);
        endif;
        $split = preg_split('/\./', $ip);
        for ($i = 0; $i < 4; $i++) :
            if (!isset($split[$i])) :# ip da verificare non ha 4 numeri: NOK!
                return false;
            elseif ($split[$i] == '*') : # incontro la wildcard: OK!
                return true;
            elseif ($split[$i] != $this->_userIp[$i]) : # numero non corrispondente: NOK!
                return false;
            endif;
        endfor;
        return true;
    }

}
