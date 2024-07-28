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
class I18N_Arabic_Soundex
{
    private $_asoundexCode = array();
    private $_aphonixCode = array();
    private $_transliteration = array();
    private $_map = array();
    private $_len = 4;
    private $_lang = "en";
    private $_code = "soundex";
    public function __construct()
    {
        $xml = simplexml_load_file(dirname(__FILE__) . "/data/ArSoundex.xml");
        foreach ($xml->asoundexCode->item as $item) {
            $index = $item["id"];
            $value = (string) $item;
            $this->_asoundexCode[(string) $value] = $index;
        }
        foreach ($xml->aphonixCode->item as $item) {
            $index = $item["id"];
            $value = (string) $item;
            $this->_aphonixCode[(string) $value] = $index;
        }
        foreach ($xml->transliteration->item as $item) {
            $index = $item["id"];
            $this->_transliteration[(string) $index] = (string) $item;
        }
        $this->_map = $this->_asoundexCode;
    }
    public function setLen($integer)
    {
        $this->_len = (int) $integer;
        return $this;
    }
    public function setLang($str)
    {
        $str = strtolower($str);
        if ($str == "ar" || $str == "en") {
            $this->_lang = $str;
        }
        return $this;
    }
    public function setCode($str)
    {
        $str = strtolower($str);
        if ($str == "soundex" || $str == "phonix") {
            $this->_code = $str;
            if ($str == "phonix") {
                $this->_map = $this->_aphonixCode;
            } else {
                $this->_map = $this->_asoundexCode;
            }
        }
        return $this;
    }
    public function getLen()
    {
        return $this->_len;
    }
    public function getLang()
    {
        return $this->_lang;
    }
    public function getCode()
    {
        return $this->_code;
    }
    protected function mapCode($word)
    {
        $encodedWord = "";
        $max = mb_strlen($word, "UTF-8");
        for ($i = 0; $i < $max; $i++) {
            $char = mb_substr($word, $i, 1, "UTF-8");
            if (isset($this->_map[(string) $char])) {
                $encodedWord .= $this->_map[(string) $char];
            } else {
                $encodedWord .= "0";
            }
        }
        return $encodedWord;
    }
    protected function trimRep($word)
    {
        $lastChar = NULL;
        $cleanWord = NULL;
        $max = mb_strlen($word, "UTF-8");
        for ($i = 0; $i < $max; $i++) {
            $char = mb_substr($word, $i, 1, "UTF-8");
            if ($char != $lastChar) {
                $cleanWord .= $char;
            }
            $lastChar = $char;
        }
        return $cleanWord;
    }
    public function soundex($word)
    {
        $soundex = mb_substr($word, 0, 1, "UTF-8");
        $rest = mb_substr($word, 1, mb_strlen($word, "UTF-8"), "UTF-8");
        if ($this->_lang == "en") {
            $soundex = $this->_transliteration[$soundex];
        }
        $encodedRest = $this->mapCode($rest);
        $cleanEncodedRest = $this->trimRep($encodedRest);
        $soundex .= $cleanEncodedRest;
        $soundex = str_replace("0", "", $soundex);
        $totalLen = mb_strlen($soundex, "UTF-8");
        if ($this->_len < $totalLen) {
            $soundex = mb_substr($soundex, 0, $this->_len, "UTF-8");
        } else {
            $soundex .= str_repeat("0", $this->_len - $totalLen);
        }
        return $soundex;
    }
}

?>