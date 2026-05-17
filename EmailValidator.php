<?php

namespace mauriziocingolani\yii2fmwkphp;

/**
 * Estende EmailValidator e aggiunge (se richiesto) il controllo dell'esistenza del dominio.
 *
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0
 */
class EmailValidator extends \yii\validators\EmailValidator {

    /** Valore restituito dal metodo {@link validateValueWithResponse} se l'indirizzo è valido */
    const ERROR_NONE = 'ok';

    /** Valore restituito dal metodo {@link validateValueWithResponse} se l'indirizzo è sintatticamente errato */
    const ERROR_SYNTAX = 'syntax';

    /** Valore restituito dal metodo {@link validateValueWithResponse} se il dominio dell'indirizzo non esiste */
    const ERROR_DOMAIN = 'domain';

    /** Cache statica per evitare di interrogare lo stesso dominio due volte nella stessa richiesta. */
    private static $_domainCache = [];

    /**
     * Esegue la validazione utilizzando il metodo della superclass, ma in più (se richiesto esplicitamente 
     * tramite il parametro $checkDomain) verifica l'esistenza del dominio utilizzando la funzione checkdnsrr().
     * Se la mail da validare è null o stringa vuota la validazione viene considerata superata.
     * @param string $value Email da validare
     * @param boolean $checkDomain Se true richiede che venga verificata anche l'esistenza del dominio
     * @return boolean Risultato della validazione
     */
    public function validateValue($value, $checkDomain = false) {
        if ($value === null || trim((string) $value) === '')
            return true;
        if (parent::validateValue($value) !== null)
            return false;
        if ($checkDomain !== true)
            return true;
        $parts = explode('@', (string) $value);
        if (count($parts) !== 2 || $parts[1] === '')
            return false;
        return $this->_domainExists($parts[1]);
    }

    /**
     * Implementa le stesse funzionalità del metodo {@link validateValue}, ma restituisce
     * il motivo per cui la validazione è fallita:
     * <ul>
     * <li>{@link EmailValidator::ERROR_SYNTAX}: sintassi errata</li>
     * <li>{@link EmailValidator::ERROR_DOMAIN}: dominio inesistente (se il controllo è richiesto esplicitamente)</li>
     * <li>{@link EmailValidator::ERROR_NONE}: indirizzo valido</li>
     * </ul>
     * Se la mail da validare è null o stringa vuota la validazione viene considerata superata.
     * @param string $value Email da validare
     * @param boolean $checkDomain Se true richiede che venga verificata anche l'esistenza del dominio
     * @return string Errore di validazione (vedi costanti della classe)
     */
    public function validateValueWithResponse($value, $checkDomain = false) {
        if ($value === null || trim((string) $value) === '')
            return self::ERROR_NONE;
        if (parent::validateValue($value) !== null)
            return self::ERROR_SYNTAX;
        if ($checkDomain !== true)
            return self::ERROR_NONE;
        $parts = explode('@', (string) $value);
        if (count($parts) !== 2 || $parts[1] === '')
            return self::ERROR_SYNTAX;
        return $this->_domainExists($parts[1]) ? self::ERROR_NONE : self::ERROR_DOMAIN;
    }

    private function _domainExists(string $host): bool {
        $host = strtolower($host);
        if (array_key_exists($host, self::$_domainCache))
            return self::$_domainCache[$host];
        try {
            $exists = checkdnsrr($host, 'MX') || checkdnsrr($host, 'A');
        } catch (\Throwable $e) {
            $exists = false;
        }
        return self::$_domainCache[$host] = $exists;
    }
}
