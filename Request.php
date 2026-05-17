<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\helpers\IpHelper;

/**
 * Estende il componente di sistema con varie funzionalità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.2
 */
class Request extends \yii\web\Request {

    /**
     * Verifica se l'ip utente è compreso nella lista passata come parametro (array o valore singolo).
     * Oltre agli IP completi accetta wildcard '*' per gli ottetti IPv4 e notazione CIDR (es. 192.168.1.0/24).
     * Supporta sia IPv4 sia IPv6 (per IPv6 solo match esatto o CIDR).
     * @param string|string[] $ips Lista degli ip
     * @return boolean True se l'ip utente è compreso nella lista, false altrimenti
     */
    public function isIpIn($ips): bool {
        if (!is_array($ips))
            $ips = [$ips];
        $userIp = $this->userIP;
        if ($userIp === null)
            return false;
        foreach ($ips as $ip) :
            if ($this->_matches($userIp, $ip))
                return true;
        endforeach;
        return false;
    }

    private function _matches(string $userIp, string $rule): bool {
        // CIDR (192.168.1.0/24, 2001:db8::/32)
        if (strpos($rule, '/') !== false)
            return IpHelper::inRange($userIp, $rule);
        // Wildcard IPv4 (1.*.3.4, 192.168.*.*)
        if (strpos($rule, '*') !== false && strpos($userIp, '.') !== false) :
            $userParts = explode('.', $userIp);
            $ruleParts = explode('.', $rule);
            if (count($userParts) !== 4 || count($ruleParts) !== 4)
                return false;
            for ($i = 0; $i < 4; $i++) :
                if ($ruleParts[$i] !== '*' && $ruleParts[$i] !== $userParts[$i])
                    return false;
            endfor;
            return true;
        endif;
        // Match esatto (vale per IPv4 e IPv6)
        return $userIp === $rule;
    }
}