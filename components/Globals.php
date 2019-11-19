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
     * Takes parallel label and value arrays, keyed by sequential number, and outputs html table
     */
    function formatAsHTMLTable(array $arrLabels, array $arrValues)
    {
        $return = '';
        foreach(array_keys($arrLabels) as $key)
            $return .= "<tr valign='top'><td><b>{$arrLabels[$key]}</b></td><td>{$arrValues[$key]}</td></tr>";
        return "<table>$return</table>";
    }

}
