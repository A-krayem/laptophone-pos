<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 5.6
 * @ Decoder version: 1.0.4
 * @ Release: 02/06/2020
 *
 * @ ZendGuard Decoder PHP 5.6
 */

// Decoded file for php version 53.
class I18N_Arabic_Query
{
    private $_fields = array();
    private $_lexPatterns = array();
    private $_lexReplacements = array();
    public function __construct()
    {
        $xml = simplexml_load_file(dirname(__FILE__) . "/data/ArQuery.xml");
        foreach ($xml->xpath("//preg_replace[@function='__construct']/pair") as $pair) {
            array_push($this->_lexPatterns, (string) $pair->search);
            array_push($this->_lexReplacements, (string) $pair->replace);
        }
    }
    public function setArrFields($arrConfig)
    {
        if (is_array($arrConfig)) {
            $this->_fields = $arrConfig;
        }
        return $this;
    }
    public function setStrFields($strConfig)
    {
        if (is_string($strConfig)) {
            $this->_fields = explode(",", $strConfig);
        }
        return $this;
    }
    public function setMode($mode)
    {
        if (in_array($mode, array("0", "1"))) {
            $this->mode = $mode;
        }
        return $this;
    }
    public function getMode()
    {
        return $this->mode;
    }
    public function getArrFields()
    {
        $fields = $this->_fields;
        return $fields;
    }
    public function getStrFields()
    {
        $fields = implode(",", $this->_fields);
        return $fields;
    }
    public function getWhereCondition($arg)
    {
        $sql = "";
        $arg = mysql_escape_string($arg);
        $phrase = explode("\"", $arg);
        if (2 < count($phrase)) {
            $arg = "";
            for ($i = 0; $i < count($phrase); $i++) {
                $subPhrase = $phrase[$i];
                if ($i % 2 == 0 && $subPhrase != "") {
                    $arg .= $subPhrase;
                } else {
                    if ($i % 2 == 1 && $subPhrase != "") {
                        $this->wordCondition[] = $this->getWordLike($subPhrase);
                    }
                }
            }
        }
        $words = preg_split("/\\s+/", trim($arg));
        foreach ($words as $word) {
            $exclude = array("(", ")", "[", "]", "{", "}", ",", ";", ":", "?", "!", "،", "؛", "؟");
            $word = str_replace($exclude, "", $word);
            $this->wordCondition[] = $this->getWordRegExp($word);
        }
        if (!empty($this->wordCondition)) {
            if ($this->mode == 0) {
                $sql = "(" . implode(") OR (", $this->wordCondition) . ")";
            } else {
                if ($this->mode == 1) {
                    $sql = "(" . implode(") AND (", $this->wordCondition) . ")";
                }
            }
        }
        return $sql;
    }
    protected function getWordRegExp($arg)
    {
        $arg = $this->lex($arg);
        $sql = " REPLACE(" . implode(", 'ـ', '') REGEXP '" . $arg . "' OR REPLACE(", $this->_fields) . ", 'ـ', '') REGEXP '" . $arg . "'";
        return $sql;
    }
    protected function getWordLike($arg)
    {
        $sql = implode(" LIKE '" . $arg . "' OR ", $this->_fields) . " LIKE '" . $arg . "'";
        return $sql;
    }
    public function getOrderBy($arg)
    {
        $phrase = explode("\"", $arg);
        if (2 < count($phrase)) {
            $arg = "";
            for ($i = 0; $i < count($phrase); $i++) {
                if ($i % 2 == 0 && $phrase[$i] != "") {
                    $arg .= $phrase[$i];
                } else {
                    if ($i % 2 == 1 && $phrase[$i] != "") {
                        $wordOrder[] = $this->getWordLike($phrase[$i]);
                    }
                }
            }
        }
        $words = explode(" ", $arg);
        foreach ($words as $word) {
            if ($word != "") {
                $wordOrder[] = "CASE WHEN " . $this->getWordRegExp($word) . " THEN 1 ELSE 0 END";
            }
        }
        $order = "((" . implode(") + (", $wordOrder) . ")) DESC";
        return $order;
    }
    protected function lex($arg)
    {
        $arg = preg_replace($this->_lexPatterns, $this->_lexReplacements, $arg);
        return $arg;
    }
    protected function allWordForms($word)
    {
        $wordForms = array($word);
        $postfix1 = array("كم", "كن", "نا", "ها", "هم", "هن");
        $postfix2 = array("ين", "ون", "ان", "ات", "وا");
        $len = mb_strlen($word);
        if (mb_substr($word, 0, 2) == "ال") {
            $word = mb_substr($word, 2);
        }
        $wordForms[] = $word;
        $str1 = mb_substr($word, 0, -1);
        $str2 = mb_substr($word, 0, -2);
        $str3 = mb_substr($word, 0, -3);
        $last1 = mb_substr($word, -1);
        $last2 = mb_substr($word, -2);
        $last3 = mb_substr($word, -3);
        if (6 <= $len && $last3 == "تين") {
            $wordForms[] = $str3;
            $wordForms[] = $str3 . "ة";
            $wordForms[] = $word . "ة";
        }
        if (6 <= $len && ($last3 == "كما" || $last3 == "هما")) {
            $wordForms[] = $str3;
            $wordForms[] = $str3 . "كما";
            $wordForms[] = $str3 . "هما";
        }
        if (5 <= $len && in_array($last2, $postfix2)) {
            $wordForms[] = $str2;
            $wordForms[] = $str2 . "ة";
            $wordForms[] = $str2 . "تين";
            foreach ($postfix2 as $postfix) {
                $wordForms[] = $str2 . $postfix;
            }
        }
        if (5 <= $len && in_array($last2, $postfix1)) {
            $wordForms[] = $str2;
            $wordForms[] = $str2 . "ي";
            $wordForms[] = $str2 . "ك";
            $wordForms[] = $str2 . "كما";
            $wordForms[] = $str2 . "هما";
            foreach ($postfix1 as $postfix) {
                $wordForms[] = $str2 . $postfix;
            }
        }
        if (5 <= $len && $last2 == "ية") {
            $wordForms[] = $str1;
            $wordForms[] = $str2;
        }
        if (4 <= $len && ($last1 == "ة" || $last1 == "ه" || $last1 == "ت") || 5 <= $len && $last2 == "ات") {
            $wordForms[] = $str1;
            $wordForms[] = $str1 . "ة";
            $wordForms[] = $str1 . "ه";
            $wordForms[] = $str1 . "ت";
            $wordForms[] = $str1 . "ات";
        }
        if (4 <= $len && $last1 == "ى") {
            $wordForms[] = $str1 . "ا";
        }
        $trans = array("أ" => "ا", "إ" => "ا", "آ" => "ا");
        foreach ($wordForms as $word) {
            $normWord = strtr($word, $trans);
            if ($normWord != $word) {
                $wordForms[] = $normWord;
            }
        }
        $wordForms = array_unique($wordForms);
        return $wordForms;
    }
    public function allForms($arg)
    {
        $wordForms = array();
        $words = explode(" ", $arg);
        foreach ($words as $word) {
            $wordForms = array_merge($wordForms, $this->allWordForms($word));
        }
        $str = implode(" ", $wordForms);
        return $str;
    }
}

?>