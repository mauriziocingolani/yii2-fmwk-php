<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;
use yii\helpers\Url;
use mauriziocingolani\yii2fmwkphp\Html;

/**
 * Estende la classe View aggiungendo alcune funzionalità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.19
 */
class View extends \yii\web\View {

    /** Titolo della pagina. */
    public $pageTitle;

    /** Descrizione della pagina. */
    public $pageDescription;

    /** Keywords della pagina. */
    public $pageKeywords;

    /** Proprietà per impedire la visualizzazione di banner. */
    public $noBanner = false;

    /** Proprietà utilizzabile per assegnare una classe al tag body */
    public $bodyClass;

    /**
     * Imposta il titolo della pagina secondo i seguenti criteri:
     * <ul>
     * <li>Se la proprietà <code>$pageTitle</code> non è impostata viene utilizzato il parametro
     * <code>'title'</code> dell'applicazione. Se nemmeno quest'ultimo è impostato viene sollevata
     * un'eccezione.</li>
     * <li>Se la proprietà <code>$pageTitle</code> è assegnata, allora viene utilizzato il parametro
     * <code>'title'</code> dell'applicazione come prefisso, sempre che sia stato impostato. Il separatore
     * tra il prefisso e il titolo della pagina può essere specificato tramite il parametro <code>'titleSeparator'</code>
     * dell'applicazione. Altrimenti viene utilizzato di default il carattere '-'.</li>
     * </ul>
     * Quindi imposta la descrizione della pagina secondo i seguenti criteri:
     * <ul>
     * <li>Se la proprietà <code>$pageDescription</code> non è assegnata esplicitamente viene utilizzato
     *  il parametro <code>'description'</code> dell'applicazione.</li>
     * <li>Se nemmeno il parametro <code>'description'</code> è assegnato viene sollevata un'eccezione</li>
     * </ul>
     * Le impostazioni vengono fatte in questo metodo per consentire l'assegnazione del valore
     * alla proprietà <code>$pageTitle</code> e <code>$pageDescription</code> nella view invece che nel controller.
     * @throws \yii\base\InvalidConfigException Se nessun titolo o nessuna descrizione sono stati impostati
     */
    public function afterRender($viewFile, $params, &$output) {
        parent::afterRender($viewFile, $params, $output);
        # titolo
        if ($this->pageTitle) :
            if (isset(Yii::$app->params['title'])) :
                $this->title = Yii::$app->params['title'] . ' ' . (isset(Yii::$app->params['titleSeparator']) ? Yii::$app->params['titleSeparator'] : '-') . ' ' . $this->pageTitle;
            endif;
        else :
            if (isset(Yii::$app->params['title'])) :
                $this->title = Yii::$app->params['title'];
            else :
                throw new \yii\base\InvalidConfigException('Il parametro \'title\' non è stato impostato.');
            endif;
        endif;
        # descrizione
        if (!$this->pageDescription) :
            if (isset(Yii::$app->params['description'])) :
                $this->pageDescription = Yii::$app->params['description'];
            else :
                throw new \yii\base\InvalidConfigException('Il parametro \'description\' non è stato impostato.');
            endif;
        else :
        endif;
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
        return $text ? $text : $this->title;
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
        # analizzo i messaggi flash uno a uno
        foreach (Yii::$app->session->allFlashes as $type => $message) :
            if (isset($allowedTypes) && array_search($type, $allowedTypes) === false)
                continue;# non visualizzo il flash se non è compreso tra quelli consentiti
            if (($i = strpos($type, '_')) !== false)
                $type = substr($type, 0, $i);
            echo Html::tag('div', $message, ['class' => 'alert alert-' . $type]);
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

    /**
     * Restituisce l'array di configurazione per il pager di default della Gridview, con i quattro pulsanti (<<,<,>,>>) e
     * l'allineamento a destra.
     * @return array Pager per la Gridview
     */
    public function getGridviewPager() {
        return [
            'firstPageLabel' => '&lt;&lt;',
            'prevPageLabel' => 'Prec.',
            'nextPageLabel' => 'Succ.',
            'lastPageLabel' => '&gt;&gt;',
            'options' => ['class' => 'pagination'],
        ];
    }

    /**
     * Restituisce il tag <div> con il sommario della Gridview nel formato "{$objsName} {begin}-{end} di {totalCount}".
     * @param string $objsName Nome degli oggetti contenuti
     * @return string Sommario per la Gridview
     */
    public function getGridviewSummary($objsName) {
        return Html::tag('div', $objsName . ' <strong>{begin}-{end}</strong> di <strong>{totalCount}</strong>', ['style' => 'text-align: right']);
    }

    /**
     * Restituisce la stringa di layout per posizionare il pager sulla destra.
     * @return string Layout per la gridview
     */
    public function getGridviewLayout() {
        return "{summary}\n{items}\n<div class='text-right'>{pager}</div>";
    }

    /**
     * Corregge l'errore di visualizzazione dei pulsanti in Bootstrap4.
     * @return array Opzioni per visualizzazione
     */
    public function getGridviewCssOptions() {
        return [
            'linkOptions' => [
                'class' => 'page-link',
            ],
            'linkContainerOptions' => [
                'class' => 'paginate_button page-item',
            ],
            'disabledPageCssClass' => 'page-link',
        ];
    }

    /**
     * Fornisce la stringa template da utilizzare per un singolo campo su una riga.
     * Il campo viene racchiuso in una riga con classe "col-sm-x" se il parametro fornito
     * è di tipo intero, altrimenti con la classe specificata se è una stringa.
     * @param mixed $nColumns Numero di colonne (sm) oppure classe per la diov esterna
     * @return string Template per il campo */
    public function getFormFieldColumnTemplate($nColumns) {
        if (is_string($nColumns)) :
            return '{label} <div class="row"><div class="' . $nColumns . '">{input}{error}{hint}</div></div>';
        elseif (is_integer($nColumns)) :
            return '{label} <div class="row"><div class="col-sm-' . $nColumns . '">{input}{error}{hint}</div></div>';
        endif;
    }

    /**
     * Esegue il render della view indicata, cercandola nella cartella 'views-include' anziché in 'views'.
     * @param string $viewFile Il file della view
     * @param array $params Parametri
     * @param object $context Controller (attuale se nullo)
     * @return string Il risultato da visualizzare
     * @throws ViewNotFoundException Se il file della view non esiste
     */
    public function renderInclude($view, $params = [], $context = null) {
        if (strtoupper(substr(PHP_OS, 0, 3) === 'WIN')) :
            $viewFile = preg_replace('/\\\views\\\/', '\views-include\\', $this->findViewFile($view, $context));
        else :
            $viewFile = preg_replace('/\/views\//', '/views-include/', $this->findViewFile($view, $context));
        endif;
        return $this->renderFile($viewFile, $params, $context);
    }

}
