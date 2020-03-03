<?php

/**
 * Globals is simply a class with a public array, to enable use of 
 * "global variables" when needed. Use judiciously!!
 *
 * @author John Brooking
 */

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Globals extends Component
{
    public $var = array();
    
    /*
     * Take a SQL date string and format it according to
     * https://www.php.net/manual/en/function.date.php
     * i.e. 'n/j/y g:i A' => 1/12/19 3:43 PM
     * 
     * If the format string is blank, the above defaults will be used.
     */
    public function formatSQLDate($MySQLDateString, $format) {
        // Try the different format for datetime, date only, or time only
        $oDateDB = \DateTime::createFromFormat('Y-m-d H:i:s', $MySQLDateString);
        $sDefaultFmt = 'n/j/y g:i A';
        if(!$oDateDB) {
            $oDateDB = \DateTime::createFromFormat('Y-m-d', $MySQLDateString);
            $sDefaultFmt = 'n/j/y';
        }
        if(!$oDateDB) {
            $oDateDB = \DateTime::createFromFormat('H:i:s', $MySQLDateString);
            $sDefaultFmt = 'g:i A';
        }
        
        if(!$format){
            $format = $sDefaultFmt;
        }
        return $oDateDB ? $oDateDB->format($format) : '';
    }
    
    /*
     * Take a date (only) display string and reformat it to yyyy-mm-dd 
     * for MySQL. If an explicit format is not passed, we assume some 
     * variation of m/d/y, although we have to be a little careful here.
     * The parser does not recognize a 4-digit year if we tell it to
     * expect a 2-digit one, so check that.
     */
    public function formatDateForSQL($AppDateString, $format) {
        if(!$format) {
            $format = ( strlen(substr( $AppDateString, strrpos($AppDateString, '/')+1 )) == 2
                        ? 'n/j/y' : 'n/j/Y'
                        ) ;
        }
        $oDateDB = \DateTime::createFromFormat($format, $AppDateString);
        return $oDateDB->format('Y-m-d');
    }
    
    /*
     * Format a 7 or 10-digit numeric string as a phone number.
     * Formats:
     *    (   (207)831-2735
     *    -   207-831-2735
     *    .   207.831.2735
     */
    
    function formatPhone($phone, $fmtChar = '(')
    {
        $returnCode = $areaCode = $prefix = $digits = '';
        
        if(strlen($phone) == 10) {
            $areaCode = substr($phone, 0, 3);
            $prefix = substr($phone, 3, 3);
            $digits = substr($phone, 6, 4);
        }
        else {
            $prefix = substr($phone, 0, 3);
            $digits = substr($phone, 3, 4);            
        }
        
        switch($fmtChar) {
            case '(':
                $returnCode = ($areaCode ? "($areaCode) $prefix-$digits" : "$prefix-$digits");
                break;
            case '-':
                $returnCode = ($areaCode ? "$areaCode-$prefix-$digits" : "$prefix-$digits");
                break;
            case '.':
                $returnCode = ($areaCode ? "$areaCode.$prefix.$digits" : "$prefix.$digits");
                break;
            default:
        }
        
        return $returnCode;
    }
    
    /*
     * Takes parallel label and value arrays and outputs an html table. 
     * The paramaters are as they are so that a model's attributeLabels() array 
     * can be used as the second. (If not for that, yes, a plain associative
     * array would be simpler.)
     * 
     * An optional class name may be provided, which will be attached to the 
     * table tag. Also, alternating rows will be tagged with class names "row0"
     * and "row1", in case the caller wants to style alternating rows.
     * 
     */
    function formatAsHTMLTable(array $arrValues, array $arrLabels, $classname = '')
    {
        $return = ($classname ? "<table class='$classname'>" : '<table>');
        $row = 0;
        foreach(array_keys($arrValues) as $key) {
            $return .= "<tr class='row$row'><td><b>{$arrLabels[$key]}</b></td><td>{$arrValues[$key]}</td></tr>";            
            $row = ++$row % 2;
        }
        return $return . '</table>';
    }

    // Wrap HTML content in a Bootstrap "col-sm-x" column class
    function wrapInColumn($cols, $content) {
        return "<div class='col-sm-$cols'>$content</div>";
    }
}
