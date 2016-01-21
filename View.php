<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;
use yii\bootstrap\Alert;
use yii\helpers\Url;

/**
 * Estende la classe View aggiungendo alcune funzionalità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.5
 */
class View extends \yii\web\View {

    /**
     * Imposta il valore di default della proprietà {@link View::title} con il valore impostato nel parametro 'title' 
     * dell'applicazione se non è impostato il titolo della view. In questo caso solleva un'eccezione se non è
     * impostato il parametro dell'applicazione.
     */
    public function init() {
        parent::init();
        if (!$this->title && !isset(\Yii::$app->params['title']))
            throw new \yii\base\InvalidConfigException('Il parametro \'title\' non è stato impostato.');
        if (!$this->title)
            $this->title = Yii::$app->params['title'];
    }

    /**
     * Aggiunge un breadcrumb alla catena, con i senza link (in base al parametro {@link $url}.
     * Se il parametro {@link $text} non è assegnato viene utilizzata la proprietà {@link View::title}.
     * Restituisce il testo del breadcrumb.
     * @param string $text Testo del breadcrumb
     * @param string $url Url del breadcrumb (senza slash iniziale)
     * @return string Description Testo del breadcrumb
     */
    public function addBreadcrumb($text = null, $url = null) {
        if ($url) :
            $bc = ['label' => $text ? $text : $this->title, 'url' => [Url::to('/' . $url)]];
        else :
            $bc = $text ? $text : $this->title;
        endif;
        $this->params['breadcrumbs'][] = $bc;
        return $bc;
    }

    /**
     * Restituisce il tag HTML per aggiungere la favicon.
     * Di default il percorso è <code>'/images/favicon.ico'</code>.
     * @param string $path Immagine (percorso assoluto)
     * @return sting Tag HTML della favicon
     */
    public function addFavicon($path = '/images/favicon.ico') {
        return '<link href="' . $path . '" rel="icon" type="image/x-icon" />';
    }

    /**
     * Crea un alert Bootstrap per ogni messaggio flash presente.
     * Si presuppone che il nome del flash da mostrare sia 'success', 'danger', oppure una stringa del tipo
     * 'success_...' o 'danger_...'. In questo caso, segnalato dalla presenza dell'underscore, viene utilizzata
     * come classe dell'alert il testo antecedente il primo underscore.
     * Il parametro (opzionale) permette di specificare i nomi dei flash che verranno mostrati, in modo da poter
     * utilizzare il metodo per due diversi blocchi di messaggi nella stessa pagina.
     * @param string[] $allowedTypes Nomi dei flash consentiti
     */
    public function addFlashDivs(array $allowedTypes = null) {
        # creao un array con i valori come chiavi per fare la ricerca
        $keys = null;
        if (isset($allowedTypes)) :
            foreach ($allowedTypes as $k => $v) :
                $keys[$v] = null;
            endforeach;
        endif;
        # analizzo i messaggi flash uno a uno
        foreach (Yii::$app->session->allFlashes as $type => $message) :
            if (isset($allowedTypes) && array_search($type, $keys) == false)
                continue;# non visualizzo il flash se non è compreso tra quelli consentiti
            if (($i = strpos($type, '_')) !== false)
                $type = substr($type, 0, $i);
            echo Alert::widget([
                'closeButton' => false,
                'options' => [
                    'class' => 'alert-' . $type,
                ],
                'body' => $message,
            ]);
        endforeach;
    }

    /**
     * Registra lo script per rispondere al click sulla checkbox mostrando o nascondendo
     * i caratteri del campo password (tramite attributo 'type' del campo).
     * @param string $checkboxId Id della checkbox (default 'reveal-password')
     * @param string $passwordFieldId Id del campo password (default 'loginform-password')
     */
    public function registerShowPasswordScript($checkboxId = 'reveal-password', $passwordFieldId = 'loginform-password') {
        $this->registerJs("jQuery('#$checkboxId').change(function(){jQuery('#$passwordFieldId').attr('type',this.checked?'text':'password');})");
    }

}
