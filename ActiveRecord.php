<?php

namespace mauriziocingolani\yii2fmwkphp;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;
use rmrevin\yii\fontawesome\FA;

/**
 * Estende yii\db\ActiveRecord aggiungendo funzionalità e utilità.
 * 
 * @property string $Creato
 * @property integer $CreatoDa
 * @property string $Modificato
 * @property integer $ModificatoDa
 * 
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.11
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord {

    /**
     * Crea un paragrafo (con classe 'created') con le informazioni sulle date e sugli autori
     * della creazione e della ultima modifica dell'oggetto in questione.
     * Presuppone che l'oggetto abbia le seguenti proprietà:
     * <ul>
     * <li>Creato: stringa con data e ora di creazione (formato MySQL)</li>
     * <li>creatore: realazione con la tabella degli utenti
     * <li>Modificato: stringa con data e ora di modifica (formato MySQL)</li>
     * <li>modificatore: realazione con la tabella degli utenti
     * </ul>
     * @param type $isFemale True per indicare che l'oggetto è al femminile
     * @return string Paragrafo con informazioni di creazione e modifica
     */
    public function getCreatedUpdatedParagraph($isFemale = false) {
        $s = 'Creat' . ($isFemale ? 'a' : 'o') . ' il ' . date('d-m-Y', strtotime($this->Creato)) .
                " da <strong>{$this->creatore->UserName}</strong>";
        if (isset($this->Modificato) && $this->Modificato)
            $s .= Html::tag('br') .
                    'Ultima modifica il ' . date('d-m-Y', strtotime($this->Modificato)) .
                    " da parte di <strong>{$this->modificatore->UserName}</strong>";
        return Html::tag('p', $s, ['class' => 'created']);
    }

    /**
     * Crea il blocco HTML standard coni dati di creazione/modifica e il pulsante di eliminazione:
     * 
     * &lt;p class=&quot;created&quot;&gt;Creat... il ... da &lt;strong&gt;...&lt;/strong&gt;&lt;/p&gt;<br />
     * &lt;p&gt;<br />
     * &lt;form id=&quot;...-delete-form&quot; action=&quot;...&quot; method=&quot;post&quot;&gt;<br />
     * &lt;input type=&quot;hidden&quot; name=&quot;_csrf&quot; value=&quot;...&quot;&gt;<br />
     * &lt;input type=&quot;hidden&quot; name=&quot;Delete...[...]&quot; value=&quot;...&quot;&gt;<br />
     * &lt;a class=&quot;btn btn-danger&quot; href=&quot;/&quot;&gt;&lt;i class=&quot;fa fa-trash-o&quot;&gt;&lt;/i&gt; ...&lt;/a&gt;<br />
     * &lt;/form&gt;<br />
     * &lt;/p&gt;
     * 
     * La form ha id "{nome classe minuscolo}-delete-form", il campo nascosto ha nome "{nome classe}[{nome campo pk}]"
     * e valore della chiave primaria del modello.
     * Se viene specificato il parametro $confirmText, il link di eliminazione è in realtà un pulsante di submit della form,
     * previa richiesta di conferma. Questo rende inutile il codice Javascript per gestire il submit della form.
     * 
     * @param string $pkField Nome del campo pk del modello
     * @param string $buttonLabel Testo del pulsante di eliminazione
     * @param boolean $isFemale True per indicare che l'oggetto è al femminile
     * @param string $confirmText Eventuale testo di conferma per il pulsante di eliminazione
     * @return string Blocco HTML
     */
    public function getCreatedUpdatedBlock($pkField, $buttonLabel, $isFemale = false, $confirmText = null) {
        if ($this->isNewRecord)
            return;
        return $this->getCreatedUpdatedParagraph($isFemale) . \PHP_EOL .
                $this->getDeleteParagraph($pkField, $buttonLabel, $confirmText);
    }

    /**
     * Crea il blocco HTML standard con il pulsante di eliminazione.
     * Se è presente il parametro $confirmText, allora invece di un semplice link
     * viene inserito un pulsante di submit con il messaggio di conferma.
     * @param string $pkField Nome del campo pk del modello
     * @param string $buttonLabel Testo del pulsante di eliminazione
     * @prams string $confirmText Testo dell'alert di conferma (opzionale)
     * @return string Blocco HTML
     */
    public function getDeleteParagraph($pkField, $buttonLabel, $confirmText = null) {
        if ($this->isNewRecord)
            return;
        return Html::beginTag('p') . \PHP_EOL .
                Html::beginForm('', 'post', ['id' => strtolower($this->formName()) . '-delete-form']) . \PHP_EOL . # form
                Html::hiddenInput("Delete{$this->formName()}[$pkField]", $this->$pkField) . \PHP_EOL . # input nascosto con id
                ($confirmText ?
                Html::submitButton(FA::icon('trash-o') . ' ' . $buttonLabel, ['class' => 'btn btn-danger', 'data-confirm' => $confirmText]) :
                Html::faa('trash-o', $buttonLabel, ['/'], ['class' => 'btn btn-danger'])) . \PHP_EOL . # pulsante eliminazione
                Html::endForm() . \PHP_EOL .
                Html::endTag('p');
    }

    /**
     * Restituisce l'array con i parametri di configurazione per BlameableBehavior.
     * @param string $createdField Nome del campo da aggiornare in seguito alla creazione
     * @param string $updatedField Nome del campo da aggiornare in seguito alla modifica
     * @return array Configurazione del behavior
     */
    public function getBlameableBehavior($createdField = 'CreatoDa', $updatedField = 'ModificatoDa') {
        return ['class' => BlameableBehavior::className(),
            'attributes' => [
                self::EVENT_BEFORE_INSERT => $createdField,
                self::EVENT_BEFORE_UPDATE => $updatedField,
            ],
        ];
    }

    /**
     * Restituisce l'array con i parametri di configurazione per SluggableBehavior.
     * @param mixed $attributes Nome del campo (o array con i nomi) da cui creare lo slug
     * @param string $slugAttribute Nome del campo con lo slug
     * @return array Configurazione del behavior
     */
    public function getSluggableBehavior($attributes, $slugAttribute, $ensureUnique = false) {
        return ['class' => SluggableBehavior::className(),
            'attribute' => $attributes,
            'slugAttribute' => $slugAttribute,
            'ensureUnique' => $ensureUnique,
        ];
    }

    /**
     * Restituisce l'array con i parametri di configurazione per TimestampBehavior.
     * Di default assegna ai campi il valore "NOW()".
     * @param string $createdField Nome del campo da aggiornare in seguito alla creazione
     * @param string $updatedField Nome del campo da aggiornare in seguito alla modifica
     * @param string $expression (opzionale) Valore da assegnare al campo
     * @return array Configurazione del behavior
     */
    public function getTimestampBehavior($createdField = 'Creato', $updatedField = 'Modificato', $expression = null) {
        return ['class' => TimestampBehavior::className(),
            'attributes' => [
                self::EVENT_BEFORE_INSERT => [$createdField],
                self::EVENT_BEFORE_UPDATE => [$updatedField],
            ],
            'value' => $expression ? $expression : new Expression('NOW()'),
        ];
    }

    /**
     * Converte la data dal formato 'Y-m-d' (o 'Y-m-d H:i:s') a 'd/m/Y'.
     * @param string $mysqlDate Data in formato MySQL
     * @return string Data in formato italiano
     */
    public static function MysqlToItalianDate($mysqlDate) {
        if (strpos($mysqlDate, ' '))
            $mysqlDate = preg_split('/[ ]/', $mysqlDate)[0];
        return join('/', array_reverse(preg_split("/[-]/", $mysqlDate)));
    }

    /**
     * Converte la data dal formato 'd/m/Y' (o 'd/m/Y H:i:s') a 'Y-m-d'.
     * @param string $italianDate Data in formato italiano
     * @return string Data in formato MySQL
     */
    public static function ItalianToMysqlDate($italianDate) {
        if (strpos($italianDate, ' '))
            $italianDate = preg_split('/[ ]/', $italianDate)[0];
        return join('-', array_reverse(preg_split("/[\/]/", $italianDate)));
    }

    /**
     * Converte la data dal formato 'Y-m-d H:i[:s]'  a 'd/m/Y H:i[:s]'.
     * @param string $string Data e ora in formato MySQL
     * @param boolean $showSeconds True per mostrare anche i secondi
     * @return string Data e ora in formato italiano
     */
    public static function MysqlToItalianDateTime($string, $showSeconds = false) {
        $split = preg_split('/[ ]/', $string);
        return self::MysqlToItalianDate($split[0]) . ' ' . substr($split[1], 0, $showSeconds ? 8 : 5);
    }

    /**
     * Converte la data dal formato 'd/m/Y H:i[:s])'  a 'Y-m-d H:i:s'. La presenza dei secondi
     * dipende dalla presenza nella stringa da convertire ('00' in caso di assenza).
     * @param string $string Data e ora in formato italiano
     * @return string Data e ora in formato MySQL
     */
    public static function ItalianToMysqlDateTime($string) {
        $split = preg_split('/[ ]/', $string);
        return self::ItalianToMysqlDate($split[0]) . ' ' . $split[1] . (strlen($split[1]) < 6 ? ':00' : '');
    }

    /**
     * Converte la data dal formato 'Y-m-d H:i[:s]'  a 'd/m/Y H:i[:s]'.
     * @param string $string Data e ora in formato MySQL
     * @param boolean $showSeconds True per mostrare anche i secondi
     * @return string Ora in formato italiano
     */
    public static function MysqlToItalianTime($string, $showSeconds = false) {
        $split = preg_split('/[ ]/', $string);
        return substr($split[1], 0, $showSeconds ? 8 : 5);
    }

    /**
     * Converte la data dal formato 'd/m/Y H:i[:s])'  a 'H:i:s'. La presenza dei secondi
     * dipende dalla presenza nella stringa da convertire ('00' in caso di assenza).
     * @param string $string DAta e ora in formato italiano
     * @return string Ora in formato MySQL
     */
    public static function ItalianToMysqlTime($string) {
        $split = preg_split('/[ ]/', $string);
        return $split[1] . (strlen($split[1]) < 6 ? ':00' : '');
    }

    /**
     * Restituisce una nuova istanza della classe, oppure l'istanza corrispondente al record
     * con chiave primaria indicata. Eventualmente popola le istanze delle relazioni indicate
     * nel parametro $with.
     * In caso di record inesistente viene sollevata una \yii\web\NotFoundHttpException che
     * di default riporta il messaggio "{nome classe} inesistente".
     * @param integer $pk Chiave primaria del record da trovare
     * @param array $with Relazioni da popolare
     * @param string $missingMessage Messaggio da associare all'eccezione sollevata in caso di record inesistente.
     * @return \static
     * @throws NotFoundHttpException Se non esiste un record con la chiave primaria indicata
     */
    public static function FindByPk($pk, array $with = null, $missingMessage = null) {
        if ($pk) :
            $query = static::find()->where(static::primaryKey()[0] . '=:id', ['id' => $pk]);
            if ($with)
                $query->with($with);
            $model = $query->one();
            if (!$model)
                throw new NotFoundHttpException($missingMessage ? $missingMessage : StringHelper::basename(static::className()) . ' inesistente.');
            return $model;
        endif;
        return new static;
    }

    /**
     * Restituisce la lista di valori assegnati a un campo di tipo ENUM.
     * Se esplicitamente richiesto come chiavi dell'array possono essere utilizzati i valori stessi,
     * ad esempio nel caso in cui si voglia popolare una select. 
     * @param string $field Nome del campo ENUM
     * @param boolean $useValuesAsKeys True per restituire un array con chiavi uguali ai valori
     * @param \yii\db\Connection $connection Eventuale connessione diversa da quella principale
     * @return mixed Lista dei valori
     */
    protected static function GetEnumValues($field, $useValuesAsKeys = false, $connection = null) {
        try {
            $data = [];
            $connection = $connection ? $connection : Yii::$app->db;
            $record = $connection->createCommand('SHOW COLUMNS FROM ' . static::tableName() . ' WHERE Field =:field', ['field' => $field])->queryOne();
            preg_match('/^enum\((.*)\)$/', $record['Type'], $matches);
            foreach (explode(',', $matches[1]) as $value) {
                $d = trim($value, "'");
                $useValuesAsKeys ? $data[$d] = $d : $data[] = $d;
            }
            return $data;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

}
