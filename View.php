<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;
use yii\helpers\Url;

/**
 * Estende la classe View aggiungendo alcune funzionalità.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0.1
 */
class View extends \yii\web\View {

    /**
     * Imposta il valore di default della proprietà {@link View::title} con il valore impostato nel parametro 'title' 
     * dell'applicazione se non è impostato il titolo della view. In questo caso solleva un'eccezione se non è
     * impostatao il parametro dell'applicazione.
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
     * @param type $text Testo del breadcrumb
     * @param type $url Url del breadcrumb (senza slash iniziale)
     */
    public function addBreadcrumb($text = null, $url = null) {
        if ($url) :
            $this->params['breadcrumbs'][] = ['label' => $text ? $text : $this->title, 'url' => [Url::to('/' . $url)]];
        else :
            $this->params['breadcrumbs'][] = $text ? $text : $this->title;
        endif;
    }

}
