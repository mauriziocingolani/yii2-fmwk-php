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

    /**
     * Esegue la validazione utilizzando il metodo della superclass, ma in più (se richiesto esplicitamente 
     * tramite il parametro $checkDomain) verifica l'esistenza del dominio utilizzando la funzione checkdnsrr().
     * Se la mail da validare è null o stringa vuota la validazione viene considerata superata.
     * @param string $value Email da validare
     * @param boolean $checkDomain Se true richiede che venga verificata anche l'esistenza del dominio
     * @return boolean Risultato della validazione
     */
    public function validateValue($value, $checkDomain = false) {
        if ($value == null || strlen(trim($value)) == 0)
            return true;
        if (parent::validateValue($value) === null) :
            if ($checkDomain === true) :
                list($address, $host) = explode('@', $value);
                return checkdnsrr($host);
            endif;
            return true;
        endif;
        return false;
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
        if ($value == null || strlen(trim($value)) == 0)
            return self::ERROR_NONE;
        if (parent::validateValue($value) === null) :
            if ($checkDomain === true) :
                list($address, $host) = explode('@', $value);
                return checkdnsrr($host) ? self::ERROR_NONE : self::ERROR_DOMAIN;
            endif;
            return self::ERROR_NONE;
        endif;
        return self::ERROR_SYNTAX;
    }

}
