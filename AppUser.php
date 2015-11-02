<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\web\User;

/**
 * Estende yii\web\User aggiungendo funzionalità e utilità.
 * 
 * @property bool $isSimulated
 * 
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0
 */
class AppUser extends User {

    const ROLE_DEVELOPER = 1;

    /**
     * 
     * @return bool True se l'utente loggato è di classe developer, false altrimenti
     */
    public function isDeveloper() {
        return !Yii::$app->user->isGuest && Yii::$app->user->identity->RoleID == self::ROLE_DEVELOPER;
    }

    /**
     * Verifica se l'utente attuale è simulato in base al contenuto del parametro di sessione <code>user.simulator</code>.
     * Se il valore contenuto è intero (ID dell'utente che sta simulando) viene restituito <code>true</code>, altrimenti
     * <code>false</code>.
     * @return bool <code>True</code> se l'utente attuale è simulato, <code>false</code> altrimenti
     */
    public function getIsSimulated() {
        return is_integer(Yii::$app->session->get('user.simulator'));
    }

    /**
     * All'avvio della simulazione registra nei parametri di sessione <code>user.simulator</code> e <code>user.simulator_session</code>
     * i dati prima del logout e quindi del login come utente da simulare. Questi dati verranno utilizzati, oltre che per 
     * capire se è in corso una simulazione, anche per identificareil record della tabella dei login da aggiornare in caso
     * di logout durante la simulazione, dato che in quel caso si l'ID utente che l'ID di sessione non corrispondono a
     * quelli salvati al momento del login.
     * @param int $simulatorID ID dell'utente che sta simulando
     * @param sting $oldSessionID ID della sessione prima della simulazione
     */
    public function startSimulation($simulatorID, $oldSessionID) {
        Yii::$app->session->set('user.simulator', (int) $simulatorID);
        Yii::$app->session->set('user.simulator_session', $oldSessionID);
    }

    /**
     * Al termine della simulazione, ovvero quando si ritorna all'utente vero e proprio, i due parametri di sessione
     * <code>user.simulator</code> e <code>user.simulator_session</code> vengono svuotati.
     */
    public function endSimulation() {
        Yii::$app->session->set('user.simulator', null);
        Yii::$app->session->set('user.simulator_session', null);
    }

}
