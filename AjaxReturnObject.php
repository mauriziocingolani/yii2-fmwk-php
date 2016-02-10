<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;
use yii\web\HttpException;
use yii\base\Object;

/**
 * Oggetto restituita da un'azione in risposta a una chiamata AJAX.
 * Espone le proprietà {@link $error}, per capire se si è verificato un errore o meno,
 * e {@link $message} per il messaggio di successo o di errore. La proprietà {$link $attachment}
 * permette di allegare alla richiesta dati di qualsiasi tipo.
 * @property array $data
 * @property boolean $error
 * @property string $message
 * @property mixed $attachment
 * 
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
 */
class AjaxReturnObject extends Object {

    const MODE_NOT_STRING = 0;
    const MODE_TRUE_OR_STRING = 1;

    private $_error = false;
    private $_message = null;
    private $_attachment = null;

    /**
     * Costruisce una nuova istanza della classe. Se viene passato un valore come primo parametro viene impostato
     * lo stato di errore un base a tale valore e alla modalità eventulamente specificata dal secondo parametro.
     * Si veda il metodo {@link setError} per la descrizione delle varie modalità.
     * 
     * @param mixed $result Valore per stabilire se si è verificato un errore.
     * @param integer $mode Modalità di interpretazione del valore
     * @throws HttpException Se l'oggetto viene construito al di fuori di una richiesta AJAX
     */
    public function __construct($result = null, $mode = self::MODE_NOT_STRING) {
        if (!Yii::$app->getRequest()->isAjax)
            throw new HttpException(400, 'Richiesta non valida. Metodo <strong>' . __METHOD__ .
            '</strong> di <strong>' . __CLASS__ .
            '</strong> invocato da contesto non AJAX.');
        if ($result)
            $this->setError($result, $mode);
    }

    /**
     * Restituisce la stringa json-encoded con le proprietà {@link $error} e {@link $message} dell'oggetto.
     * L'encoding viene fatto a partire da un array con elementi <code>error</code> e <code>message</code>.
     * @return string JSON con le proprietà {@link $error} e {@link $message} dell'oggetto
     */
    public function getData() {
        return json_encode(['error' => $this->_error, 'message' => $this->_message, 'attachment' => $this->_attachment]);
    }

    /**
     * Restituisce lo stato di errore dell'oggetto.
     * @return boolean Proprietà {@link $error}
     */
    public function getError() {
        return (bool) $this->_error;
    }

    /**
     * Imposta lo stato della proprietà {@link $error} in base al valore passato come primo parametro e alla
     * modalità, specificata dal secondo parametro (opzionale). In assenza del secondo parametro il valore
     * viene semplicemente convertito in booleano, altrimenti viene utilizzata una modalità tra quelle
     * attualmente implementate:
     * <ul>
     *      <li><strong><code>MODE_NOT_STRING</code></strong>: qualsiasi valore non di tipo stringa viene
     *      interpretato come un errore. Il valore viene anche assegnato alla proprietà {@link $message}.</li>
     * </ul>
     * @param mixexd $value Valore per l'impostazione dello stato di errore.
     * @param integer $mode Modalità di interpretazione del valore (default <code>null</code>)
     */
    public function setError($value, $mode = null) {
        if (isset($mode)) :
            if ($mode == self::MODE_NOT_STRING) :
                $this->_error = is_string($value);
                $this->_message = is_string($value) ? $value : null;
            endif;
        else :
            $this->_error = (bool) $value;
        endif;
    }

    /**
     * Restituisce il valore della proprietà {@link $message}.
     * @return string Messaggio
     */
    public function getMessage() {
        return $this->_message;
    }

    /**
     * Imposta i ll valore della proprietà {@link $message}.
     * @param string $message Contenuto del messaggio
     */
    public function setMessage($message) {
        $this->_message = $message;
    }

    /**
     * Imposta i ll valore della proprietà {@link $attachment}.
     * @param mixed $value Dati da allegare alla richiesta
     */
    public function setAttachment($value) {
        $this->_attachment = $value;
    }

}
