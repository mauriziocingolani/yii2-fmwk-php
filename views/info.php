<?php

use yii\helpers\ArrayHelper;

/**
 * Elenco delle proprietà di sistema del server.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0
 */
?>

<h3>Server</h3>
<table class="info table table-responsive">
    <tbody>
        <tr>
            <td>IP</td>
            <th><?= $_SERVER['SERVER_ADDR']; ?></th>
        </tr>
        <tr>
            <td>WebServer</td>
            <th><?= $_SERVER['SERVER_SOFTWARE']; ?></th>
        </tr>
        <tr>
            <td>PHP</td>
            <th><?= PHP_VERSION; ?></th>
        </tr>
    </tbody>
</table>

<h3>Framework</h3>
<table class="info table table-responsive">
    <tbody>
        <tr>
            <td>Yii</td>
            <th><?= Yii::getVersion(); ?></th>
        </tr>
    </tbody>
</table>

<?php
if (Yii::$app->db->isActive) :
    $variables = ArrayHelper::map(Yii::$app->db->createCommand("SHOW GLOBAL VARIABLES")->queryAll(), 'Variable_name', 'Value');
endif;
?>
<h3>Database</h3>
<table class = "info table table-responsive">
    <tbody>
        <tr>
            <td>Server</td>
            <th><?= $variables['version_comment']; ?></th>
        </tr>
        <tr>
            <td>Versione</td>
            <th><?= $variables['version']; ?></th>
        </tr>
        <tr>
            <td>OS</td>
            <th><?= $variables['version_compile_os']; ?></th>
        </tr>
        <tr>
            <td>Macchina</td>
            <th><?= $variables['version_compile_machine']; ?></th>
        </tr>
    </tbody>
</table>

<h3>Client</h3>
<table class = "info table table-responsive">
    <tbody>
        <tr>
            <td>IP</td>
            <th><?= Yii::$app->request->userIP; ?></th>
        </tr>
        <tr>
            <td>Browser</td>
            <th><?= Yii::$app->request->userAgent; ?></th>
        </tr>
    </tbody>
</table>