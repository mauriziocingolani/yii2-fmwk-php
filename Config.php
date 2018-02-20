<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;

/**
 * @property array $catchAll
 * @property string $language
 * @property string $name
 * @property string $timezone
 * @property string $version
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.19
 */
class Config extends \yii\base\Object {

    private $_id;
    private $_basePath;
    private $_bootstrap;
    private $_catchall;
    private $_configFolder;
    private $_aliases;
    private $_catchAll;
    private $_components;
    private $_language;
    private $_modules;
    private $_name;
    private $_onBeforeRequest;
    private $_params;
    private $_sourceLanguage;
    private $_timeZone;
    private $_version;

    /**
     * Costruisce una nuova istanza della classe e imposta le proprietà fondamentali.
     * @param type $id Id applicazione
     * @param type $basePath Cartella root dell'applicazione
     * @param type $filesSubfolder Sottocartella per i files di configurazione
     */
    public function __construct($id, $basePath, $filesSubfolder = null) {
        $this->_id = $id;
        $this->_basePath = $basePath;
        $this->_configFolder = dirname(__FILE__) . '/../../../config/' . ($filesSubfolder ? $filesSubfolder . '/' : '');
        parent::__construct();
    }

    /**
     * Inizializza tutte le proprietà con i valori di default e con i componenti standard.
     */
    public function init() {
        $this->_aliases = [
            '@modules' => '@app/modules',
        ];
        $this->_bootstrap = [];
        if (YII_DEBUG)
            $this->_bootstrap[] = 'debug';
        $this->_components = [
            'assetManager' => [
                'bundles' => [
                    'yii\web\JqueryAsset' => [
                        'sourcePath' => null, // do not publish the bundle
                        'js' => [
                            '//code.jquery.com/jquery-1.11.3.min.js',
                        ]
                    ],
                ],
            ],
            'errorHandler' => [
                'errorAction' => 'site/error',
            ],
            'request' => [
                'class' => Request::className(),
                'cookieValidationKey' => require $this->_configFolder . 'cookies.php',
            ],
            'urlManager' => [
                'class' => 'yii\web\UrlManager',
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'enableStrictParsing' => false,
                'rules' => require $this->_configFolder . 'rules.php',
            ],
            'view' => [
                'class' => 'mauriziocingolani\yii2fmwkphp\View',
            ],
        ];
        $this->_language = 'it-IT';
        $this->_sourceLanguage = 'en-EN';
        $this->_modules = [
            'migrate' => [
                'class' => 'app\modules\migrate\migrate',
            ]
        ];
        if (YII_DEBUG)
            $this->_modules['debug'] = [
                'class' => 'yii\debug\Module',
                'allowedIPs' => ['*'],
            ];
        if (OFFLINE)
            $this->_catchAll = [
                'site/offline',
            ];
        $this->_params = require $this->_configFolder . 'params.php';
        $this->_name = 'My Application';
        $this->_timeZone = 'Europe/Rome';
        $this->_version = '1.0';
    }

    /* Componenti */

    /**
     * Aggiunge il componente per l'autenticazione tramite servizi esterni.
     * @param array $clients Meccanismi di autenticazione esterni (Facebook, Google, etc)
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addAuthComponent(array $clients) {
        $this->_components['authClientCollection'] = [
            'class' => 'yii\authclient\Collection',
            'clients' => $clients,
        ];
        return $this;
    }

    /**
     * Aggiunge il componente per la cache su database.
     * @param string $tableName Nome della tabella per i dati di cache (default 'YiiCache')
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addCacheComponent($tableName = null) {
        $this->_components['cache'] = [
            'class' => 'yii\caching\DbCache',
            'cacheTable' => $tableName ? $tableName : 'YiiCache',
        ];
        return $this;
    }

    /**
     * Aggiunge all'applicazione il componente specificato.
     * @param array $component Componente
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     * @throws Exception Se il parametro passato non è un array
     */
    public function addComponent(array $component) {
        if (!is_array($component))
            throw new Exception(__CLASS__ . ' -> ' . __FUNCTION__ . ': parametro non valido');
        $this->_components = array_merge($this->_components, $component);
        return $this;
    }

    /**
     * Aggiunge il componente per la connessione al db. I parametri di configurazione 
     * vengono caricati dal file 'db.php' (presente nella cartella dei files di configurazione).
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addDbComponent() {
        $this->_components['db'] = require $this->_configFolder . 'db.php';
        return $this;
    }

    /**
     * Aggiunge il componente per la traduzione dei testi al db. Di default viene impostata la cartella
     * 'messages' per il files delle traduzioni.
     * @param string $basePath Cartella che contiene le traduzioni
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addI18NComponent($basePath = null) {
        if (!$basePath)
            $basePath = $_SERVER['DOCUMENT_ROOT'] . '/../messages';
        $this->_components['i18n'] = [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => $basePath,
                ]
            ],
        ];
        return $this;
    }

    /**
     * Aggiunge il componente per l'invio delle mail. I parametri di configurazione 
     * vengono caricati dal file 'mail.php' (presente nella cartella dei files di configurazione).
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addMailComponent() {
        $this->_components['mailer'] = require $this->_configFolder . 'mail.php';
        return $this;
    }

    /**
     * Aggiunge il componente per il salvataggio su database dei dati di sessione.
     * Tramite l'array dei parametri è possibile impostare il nome della tabella (default 'YiiSessions')
     * e la durata (in secondi) della sessione.
     * @param array $params Parametri di configurazione ('sessionTable','timeout')
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addSessionComponent(array $params = null) {
        if (is_array($params))
            $this->_components['session'] = [
                'class' => 'yii\web\DbSession',
                'sessionTable' => is_array($params) && isset($params['sessionTable']) ? $params['sessionTable'] : 'YiiSessions',
                'timeout' => is_array($params) && isset($params['timeout']) ? $params['timeout'] : 1440,
            ];
        return $this;
    }

    /**
     * Aggiunge il componente per l'invio di SMS tramite servizio sms-mobile.it..
     * I parametri di configurazione vengono caricati dal file 'smsmobile.php' (presente nella cartella dei files di configurazione).
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addSmsMobileComponent() {
        $this->_components['smsmobile'] = require $this->_configFolder . 'smsmobile.php';
        return $this;
    }

    /**
     * Aggiunge il componente per l'interazione con il Telegram Bot relativo all'applicazione.
     * I parametri di configurazione vengono caricati dal file 'telegram.php' (presente nella cartella dei files di configurazione).
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addTelegramComponent() {
        $this->_components['telegram'] = require $this->_configFolder . 'telegram.php';
        return $this;
    }

    /**
     * Aggiunge il componente per la visualizzazione dei tweet relativi all'applicazione.
     * I parametri di configurazione vengono caricati dal file 'twitter.php' (presente nella cartella dei files di configurazione).
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addTwitterComponent() {
        $this->_components['twitter'] = require $this->_configFolder . 'twitter.php';
        return $this;
    }

    /**
     * Aggiunge il componente per la gestione del login utente. 
     * Presuppone che esista la classie app\components\AppUser se non viene specificato il 
     * parametro $identityClass.
     * @param string $identityClass Nome della classe che rappresenta gli utenti
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addUserComponent($identityClass = 'app\modules\user\models\User') {
        $this->_components['user'] = [
            'class' => 'app\components\AppUser',
            'enableAutoLogin' => true,
            'identityClass' => $identityClass,
            'loginUrl' => ['/login'],
        ];
        return $this;
    }

    /* Renderers */

    /**
     * Aggiunge al componente 'view' il renderer per Twig.
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addTwigRenderer() {
        $this->_components['view']['renderers']['twig'] = [
            'class' => 'yii\twig\ViewRenderer',
            'cachePath' => '@runtime/Twig/cache',
            'options' => [
                'auto_reload' => true,
            ],
            'globals' => [
                'html' => '\yii\helpers\Html',
            ],
            'uses' => ['yii\bootstrap'],
        ];
        return $this;
    }

    /* Moduli */

    /**
     * Aggiunge un modulo all'applicazione.
     * @param array $module Array diu configurazione del modulo
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addModule($module) {
        $this->_modules = array_merge($this->_modules, $module);
        return $this;
    }

    /**
     * Aggiunge il modulo per il tracciamento di Google Analytics (kartik\social).
     * Presuppone che siano stati impostati i due parametri di configurazione:
     * <ul>
     * <li>['googleAnalytics']['id']</il>
     * <li>['googleAnalytics']['domain']</il>
     * </ul>
     */
    public function addGoogleAnalyticsModule() {
        $this->_modules['social'] = [
            'class' => 'kartik\social\Module',
            'googleAnalytics' => [
                'id' => $this->getParam('googleAnalytics', 'id'),
                'domain' => $this->getParam('googleAnalytics', 'domain'),
                'anonymizeIp' => $this->getParam('googleAnalytics', 'anonymizeIp'),
            ],
        ];
    }

    /**
     * Aggiunge il modulo utente, con le classi di gestione database e le funzionalità base (login, logout,
     * gestione ruoli e utenti).
     * Di default la classe che rappresenta un utente dell'applicazione è 'app\modules\user\User'; per modificarla
     * si usa l'elemento 'class' dell'array di configurazione.
     * @param array $options Opzioni di configurazione 
     * @param boolean $new True per utilizzare il nuovo modulo
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function addUserModule($options = array(), $new = false) {
        if (!isset($options['class']))
            $options['class'] = $new ? 'app\modules\usernew\UserNew' : 'app\modules\user\User';
        $this->_modules[$new ? 'usernew' : 'user'] = $options;
        return $this;
    }

    /* Metodi */

    /**
     * Abilita Gii per gli ip indicati.
     * @param array $allowedIps Lista degli ip abilitati
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function enableGii(array $allowedIps) {
        $this->_bootstrap[] = 'gii';
        $this->_modules['gii'] = [
            'class' => 'yii\gii\Module',
            'allowedIPs' => $allowedIps,
        ];
        return $this;
    }

    /**
     * Restituisce l'array di configurazione per l'applicazione.
     * @return array Array di configurazione
     */
    public function getConfig() {
        $conf = [
            # required
            'id' => $this->_id,
            'basePath' => $this->_basePath,
            # info
            'sourceLanguage' => $this->_sourceLanguage,
            'language' => $this->_language,
            'name' => $this->_name,
            'version' => $this->_version,
            'timeZone' => $this->_timezone,
            # componenti
            'aliases' => $this->_aliases,
            'bootstrap' => $this->_bootstrap,
            'catchAll' => $this->_catchAll,
            'components' => $this->_components,
            'modules' => $this->_modules,
            'params' => $this->_params,
            'catchAll' => $this->_catchAll,
        ];
        if ($this->_onBeforeRequest)
            $conf['on beforeRequest'] = $this->_onBeforeRequest;
        return $conf;
    }

    /**
     * Restiruisce il valore del parametro di configurazione specificato. Per parametri su più
     * livelli vanno passati tanti parametri quanti sono i livelli da raggiungere all'interno dell'array
     * di configurazione, specificando l'intero percorso del parametro.
     * @return mixed Valore del parametro di configurazione
     * @throws \yii\base\InvalidConfigException
     */
    public function getParam() {
        $args = func_get_args();
        $a = $this->_params;
        foreach ($args as $arg) :
            if (isset($a[$arg])) :
                $a = $a[$arg];
            else :
                throw new \yii\base\InvalidConfigException("Elemento <code>$arg</code> non presente nell'array dei parametri. ");
            endif;
        endforeach;
        return $a;
    }

    /* Setters */

    /**
     * Assegna gli alias specificati;
     * @param array $alias Array di coppie alias-percorso
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function setAlias(array $alias) {
        foreach ($alias as $alias => $path) :
            $this->_aliases[$alias] = $path;
        endforeach;
        return $this;
    }

    /**
     * Imposta la route (controller/action) che intercetta tutte le chimate.
     * @param string $catchAll Valore della proprietà {@link $catchAll}
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function setCatchAll(array $catchAll) {
        $this->_catchAll = $catchAll;
        return $this;
    }

    /**
     * Imposta la proprietà 'allowedIps' del modulo Debug, in modo da impedire la 
     * visualizzazione della barra di debug agli utenti non autorizzati.
     * @param array $ips Lista degli ip abilitati a vedere la barra di debug
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function setDebugIps(array $ips) {
        if (isset($this->_modules['debug']))
            $this->_modules['debug']['allowedIPs'] = $ips;
        return $this;
    }

    /**
     * Assegna a EVENT_BEFORE_REQUEST una funzione che ridirige su 
     * https in caso di connessioni non sicure.
     */
    public function setHttps() {
        $this->setOnBeforeRequest(function($event) {
            if (!Yii::$app->request->isSecureConnection) {
                Yii::$app->getResponse()->redirect(str_replace('http:', 'https:', Yii::$app->request->getAbsoluteUrl()));
                Yii::$app->end();
            }
        });
    }

    /**
     * Imposta il linguaggio dell'applicazione.
     * @param string $language Valore della proprietà {@link $language}
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function setLanguage($language) {
        $this->_language = $language;
        return $this;
    }

    /**
     * Imposta il nome dell'applicazione.
     * @param string $name Valore della proprietà {@link $name}
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    /**
     * Imposta EVENT_BEFORE_REQUEST con la funzione indicata.
     * @param callable $function Funzione da eseguire
     */
    public function setOnBeforeRequest(callable $function) {
        $this->_onBeforeRequest = $function;
    }

    /**
     * Imposta il linguaggio di default dell'applicazione.
     * @param string $language Valore della proprietà {@link $sourceLanguage}
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function setSourceLanguage($language) {
        $this->_sourceLanguage = $language;
        return $this;
    }

    /**
     * Imposta la timezone dell'applicazione.
     * @param string $timeZone Valore della proprietà {@link $timeZone}
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function setTImezone($timeZone) {
        $this->_timeZone = $timeZone;
        return $this;
    }

    /**
     * Imposta la versione dell'applicazione.
     * @param string $version Valore della proprietà {@link $version}
     * @return \mauriziocingolani\yii2fmwkphp\Config Oggetto corrente (per concatenamento)
     */
    public function setVersion($version) {
        $this->_version = $version;
        return $this;
    }

}
