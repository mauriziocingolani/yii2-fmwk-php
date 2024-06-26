<?php

namespace mauriziocingolani\yii2fmwkphp;

use yii\base\BaseObject;
use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Apre un file Excel come oggetto PHPSpreadsheet e gestisce la lettura sequenziale delle righe
 * implementando l'interfaccia {@link Iterator}. Ogni riga viene restituita sotto forma di un
 * oggetto le cui proprietà hanno lo stesso nome delle intestazioni di colonna del foglio in questione.
 * Si presuppone che ogni foglio abbia nella prima riga le intestazioni di colonna, e che ciascuna di esse
 * sia in formato compatibile con le regole di denominazione delle proprietà di oggetti PHP (si veda 
 * {@see http://php.net/manual/en/language.variables.basics.php}.
 * 
 * @property PhpSpreadsheet $_objPHPExcel Oggetto PHPExcel
 * @property integer $_nsheets Numero di fogli del file
 * @property array $headers Intestazioni delle colonne
 * @property integer $_maxColumn Numero di colonne
 * @property integer $_maxRow Numero di righe
 * @property Worksheet $_sheet Foglio attivo
 * @property integer $_position Riga attuale
 * 
 * Getters
 * @property integer $sheetCount
 * @property array $headers
 * 
 * @author Maurizio Cingolani
 * @version 1.0.2
 */
class ExcelFile extends BaseObject implements \Iterator {

    private $_spreadsheet;
    private $_nsheets;
    private $_headers;
    private $_maxColumn;
    private $_maxRow;
    private $_sheet;
    private $_position;

    /**
     * Costruisce l'oggetto {@link ExcelFile::$_objPHPExcel} e inizializza il primo foglio come attivo.
     * @param string $filePath Percorso del file Excel
     */
    public function __construct($filePath) {
        $this->_spreadsheet = IOFactory::load($filePath);
        $this->_nsheets = $this->_spreadsheet->getSheetCount();
        $this->_initSheet(0);
    }

    /**
     * Restituisce {@link ExcelFile::$_headers}.
     * @return array Intestazioni di colonna
     */
    public function getHeaders() {
        return $this->_headers;
    }

    /**
     * Restituisce {@link ExcelFile::$_nsheets}.
     * @return integer Numero di fogli presenti nel file
     */
    public function getSheetCount() {
        return $this->_nsheets;
    }

    /**
     * Imposta il foglio indicato (se esite) come attivo.
     * @param integer $pIndex Indice del foglio (0,1,...)
     */
    public function setCurrentSheet($pIndex) {
        if ($pIndex >= 0 && $pIndex < $this->_nsheets)
            $this->_initSheet($pIndex);
    }

    /**
     * Restituisce la riga attuale del foglio Excel sotto forma di un oggetto le cui proprietà hanno lo
     * stesso nome delle intestazioni di  colonna.
     * @return stdClass Oggetto che rappresenta la riga del file Excel
     */
    public function current(): mixed {
        $c = new \stdClass();
        for ($column = 1; $column <= $this->_maxColumn; $column++) :
            $cell = $this->_sheet->getCellByColumnAndRow($column, $this->_position);
            $prop = $this->_headers[$column];
            if ($prop != null)
                $c->$prop = $cell->getValue();
        endfor;
        return $c;
    }

    /**
     * Restituisce la riga attuale.
     * @return integer Riga attuale
     */
    public function key(): mixed {
        return $this->_position;
    }

    /**
     * Passa alla riga successiva.
     */
    public function next(): void {
        ++$this->_position;
    }

    /**
     * Ritorna all'inizio del foglio attuale (ovvero alla seconda riga).
     */
    public function rewind(): void {
        $this->_position = 2;
    }

    /**
     * Verifica che la riga attuale sia valida, ovvero che il suo indice non abbia superato
     * il numero totale di righe.
     * @return boolean true se la riga attuale è valida
     */
    public function valid(): bool {
        return $this->_position <= $this->_maxRow;
    }

    /**
     * Rende attivo il foglio indicato, reinizializzando le proprietà {@link ExcelFile::$_maxColumn},
     * {@link ExcelFile::$_maxRow}, {@link ExcelFile::$_headers} e riportando la posizione all'inizio
     * (ovvero alla seconda riga).
     * @param integer $pIndex Indice del foglio da rendere attivo
     */
    private function _initSheet($pIndex) {
        $this->_sheet = $this->_spreadsheet->getSheet($pIndex);
        $this->_maxRow = (int) $this->_sheet->getHighestRow();
        $this->_maxColumn = (int) Coordinate::columnIndexFromString($this->_sheet->getHighestColumn());
        $this->_headers = array();
        for ($column = 1; $column <= $this->_maxColumn; $column++) :
            $cell = $this->_sheet->getCellByColumnAndRow($column, 1);
            $this->_headers[$column] = $cell->getValue();
        endfor;
        $this->rewind();
    }
}
