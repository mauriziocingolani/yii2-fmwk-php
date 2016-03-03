<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\base\Widget;

/**
 * Genera una <div> con classe "workinprogress" contenente l'immagine
 * (default: "/images/work-in-progress.png") e il messaggio (opzionale) indicati.
 * Parametri assegnabili:
 * <ul>
 * <li><code>folder</code>: percorso (nella root pubblica) che contiene l'immagine da mostrare (default: 'images')</li>
 * <li><code>image</code>: immagine da mostrare (default: 'work-in-progress.png')</li>
 * <li><code>message</code>: messaggio da mostrare (opzionale)</li>
 * </ul>
 * Formato dello snippet HTML generato:
 * 
 * <div class="workinprogress">
 *      <img src="/$folder/$image" alt="work-in-progress.png">
 *      {<p class="workinprogress-message">$message</p>}
 * </div>
 * 
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0
 */
class WorkInProgressWidget extends Widget {

    const DEFAULT_MESSAGE = 'Modifiche in corso. La pagina potrebbe non funzionare correttamente...';

    public $folder = 'images';
    public $image = 'work-in-progress.png';
    public $message;

    public function run() {
        echo Html::beginTag('div', ['class' => 'workinprogress']);
        echo Html::img("/$this->folder/$this->image", ['alt' => 'work-in-progress.png']);
        if ($this->message) :
            echo Html::tag('p', $this->message, ['class' => 'workinprogress-message']);
        endif;
        echo Html::endTag('div');
    }

}
