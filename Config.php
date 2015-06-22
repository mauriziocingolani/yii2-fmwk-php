<?php

namespace mauriziocingolani\yii2fmwkphp;

/**
 * @property array $catchAll
 * @property string $language
 * @property string $name
 * @property string $timezone
 * @property string $version
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0.3
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
    private $_params;
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
        $this->_configFolder = dirname(__FILE__) . '/../../config/' . ($filesSubfolder ? $filesSubfolder . '/' : '');
        parent::__construct();
    }

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
                            '//code.jquery.com/jquery-1.11.2.min.js',
                        ]
                    ],
                ],
            ],
            'errorHandler' => [
                'errorAction' => 'site/error',
            ],
            'request' => [
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
                'class' => 'app\components\framework\View',
            ],
        ];
        $this->_language = 'it-IT';
        $this->_modules = [
            'migrate' => [
                'class' => 'app\modules\migrate\migrate',
            ]
        ];
        if (YII_DEBUG)
            $this->_modules['debug'] = [
                'class' => 'yii\debug\Module',
                'allowedIPs' => ['*']
            ];
        if (OFFLINE)
            $this->_catchAll = [
                'site/offline',
            ];
        $this->_name = 'My Application';
        $this->_timeZone = 'Europe/Rome';
        $this->_version = '1.0';
    }

    public function addAuthComponent(array $clients) {
        $this->_components['authClientCollection'] = [
            'class' => 'yii\authclient\Collection',
            'clients' => $clients,
        ];
    }

    public function addModule($module) {
        array_merge($this->_modules, $module);
    }

    public function addDbComponent() {
        $this->_components['db'] = require $this->_configFolder . 'db.php';
        return $this;
    }

    public function addMailComponent() {
        $this->_components['mailer'] = [
            'class' => 'yii\swiftmailer\Mailer',
        ];
    }

    public function addSessionComponent($tableName = 'YiiSessions') {
        $this->_components['session'] = [
            'class' => 'yii\web\DbSession',
            'sessionTable' => $tableName,
        ];
    }

    public function addUserComponent() {
        $this->_components['user'] = [
            'class' => 'app\components\AppUser',
            'enableAutoLogin' => true,
            'identityClass' => 'app\modules\user\models\User',
            'loginUrl' => ['/login'],
        ];
        return $this;
    }

    public function addUserModule($class = 'app\modules\user\User') {
        $this->_modules['user'] = [
            'class' => $class,
        ];
        return $this;
    }

    public function enableGii(array $allowedIps) {
        $this->_bootstrap[] = 'gii';
        $this->_modules['gii'] = [
            'class' => 'yii\gii\Module',
            'allowedIPs' => $allowedIps,
        ];
        return $this;
    }

    public function getConfig() {
        return [
            # required
            'id' => $this->_id,
            'basePath' => $this->_basePath,
            # info
            'language' => $this->_language,
            'name' => $this->_name,
            'version' => $this->_version,
            # componenti
            'aliases' => $this->_aliases,
            'bootstrap' => $this->_bootstrap,
            'catchAll' => $this->_catchAll,
            'components' => $this->_components,
            'modules' => $this->_modules,
            'params' => require $this->_configFolder . 'params.php',
            'catchAll' => $this->_catchAll,
        ];
    }

    /* Setters */

    public function setCatchAll(array $catchAll) {
        $this->_catchAll = $catchAll;
    }

    public function setLanguage($language) {
        $this->_language = $language;
    }

    public function setName($name) {
        $this->_name = $name;
    }

    public function setTImezone($timeZone) {
        $this->_timeZone = $timeZone;
    }

    public function setVersion($version) {
        $this->_version = $version;
    }

}
