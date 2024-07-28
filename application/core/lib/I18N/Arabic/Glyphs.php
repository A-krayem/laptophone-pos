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
class I18N_Arabic_Glyphs
{
    private $_glyphs = NULL;
    private $_hex = NULL;
    private $_prevLink = NULL;
    private $_nextLink = NULL;
    private $_vowel = NULL;
    public function __construct()
    {
        $this->_prevLink = "،؟؛ـئبتثجحخسشصضطظعغفقكلمنهي";
        $this->_nextLink = "ـآأؤإائبةتثجحخدذرزسشصضطظعغفقكلمنهوىي";
        $this->_vowel = "ًٌٍَُِّْ";
        $this->_glyphs = "ًٌٍَُِّْٰ";
        $this->_hex = "064B064B064B064B064C064C064C064C064D064D064D064D064E064E";
        $this->_hex .= "064E064E064F064F064F064F06500650065006500651065106510651";
        $this->_hex .= "06520652065206520670067006700670";
        $this->_glyphs .= "ءآأؤإئاب";
        $this->_hex .= "FE80FE80FE80FE80FE81FE82FE81FE82FE83FE84FE83FE84FE85FE86";
        $this->_hex .= "FE85FE86FE87FE88FE87FE88FE89FE8AFE8BFE8CFE8DFE8EFE8DFE8E";
        $this->_hex .= "FE8FFE90FE91FE92";
        $this->_glyphs .= "ةتثجحخدذ";
        $this->_hex .= "FE93FE94FE93FE94FE95FE96FE97FE98FE99FE9AFE9BFE9CFE9DFE9E";
        $this->_hex .= "FE9FFEA0FEA1FEA2FEA3FEA4FEA5FEA6FEA7FEA8FEA9FEAAFEA9FEAA";
        $this->_hex .= "FEABFEACFEABFEAC";
        $this->_glyphs .= "رزسشصضطظ";
        $this->_hex .= "FEADFEAEFEADFEAEFEAFFEB0FEAFFEB0FEB1FEB2FEB3FEB4FEB5FEB6";
        $this->_hex .= "FEB7FEB8FEB9FEBAFEBBFEBCFEBDFEBEFEBFFEC0FEC1FEC2FEC3FEC4";
        $this->_hex .= "FEC5FEC6FEC7FEC8";
        $this->_glyphs .= "عغفقكلمن";
        $this->_hex .= "FEC9FECAFECBFECCFECDFECEFECFFED0FED1FED2FED3FED4FED5FED6";
        $this->_hex .= "FED7FED8FED9FEDAFEDBFEDCFEDDFEDEFEDFFEE0FEE1FEE2FEE3FEE4";
        $this->_hex .= "FEE5FEE6FEE7FEE8";
        $this->_glyphs .= "هوىيـ،؟؛";
        $this->_hex .= "FEE9FEEAFEEBFEECFEEDFEEEFEEDFEEEFEEFFEF0FEEFFEF0FEF1FEF2";
        $this->_hex .= "FEF3FEF40640064006400640060C060C060C060C061F061F061F061F";
        $this->_hex .= "061B061B061B061B";
        $this->_glyphs .= "";
        $this->_hex .= "";
        $this->_glyphs .= "لآلألإلا";
        $this->_hex .= "FEF5FEF6FEF5FEF6FEF7FEF8FEF7FEF8FEF9FEFAFEF9FEFAFEFBFEFC";
        $this->_hex .= "FEFBFEFC";
    }
    protected function getGlyphs($char, $type)
    {
        $pos = mb_strpos($this->_glyphs, $char);
        if (49 < $pos) {
            $pos = ($pos - 49) / 2 + 49;
        }
        $pos = $pos * 16 + $type * 4;
        return substr($this->_hex, $pos, 4);
    }
    protected function preConvert($str)
    {
        $crntChar = NULL;
        $prevChar = NULL;
        $nextChar = NULL;
        $output = "";
        $_temp = mb_strlen($str);
        for ($i = 0; $i < $_temp; $i++) {
            $chars[] = mb_substr($str, $i, 1);
        }
        $max = count($chars);
        for ($i = $max - 1; 0 <= $i; $i--) {
            $crntChar = $chars[$i];
            $prevChar = " ";
            if (0 < $i) {
                $prevChar = $chars[$i - 1];
            }
            if ($prevChar && mb_strpos($this->_vowel, $prevChar) !== false) {
                $prevChar = $chars[$i - 2];
                if ($prevChar && mb_strpos($this->_vowel, $prevChar) !== false) {
                    $prevChar = $chars[$i - 3];
                }
            }
            $Reversed = false;
            $flip_arr = ")]>}";
            $ReversedChr = "([<{";
            if ($crntChar && mb_strpos($flip_arr, $crntChar) !== false) {
                $crntChar = $ReversedChr[mb_strpos($flip_arr, $crntChar)];
                $Reversed = true;
            } else {
                $Reversed = false;
            }
            if ($crntChar && !$Reversed && mb_strpos($ReversedChr, $crntChar) !== false) {
                $crntChar = $flip_arr[mb_strpos($ReversedChr, $crntChar)];
            }
            if (ord($crntChar) < 128) {
                $output .= $crntChar;
                $nextChar = $crntChar;
                continue;
            }
            if ($crntChar == "ل" && isset($chars[$i + 1]) && mb_strpos("آأإا", $chars[$i + 1]) !== false) {
                continue;
            }
            if ($crntChar && mb_strpos($this->_vowel, $crntChar) !== false) {
                if (mb_strpos($this->_nextLink, $chars[$i + 1]) !== false && mb_strpos($this->_prevLink, $prevChar) !== false) {
                    $output .= "&#x" . $this->getGlyphs($crntChar, 1) . ";";
                } else {
                    $output .= "&#x" . $this->getGlyphs($crntChar, 0) . ";";
                }
                continue;
            }
            $form = 0;
            if (($prevChar == "لا" || $prevChar == "لآ" || $prevChar == "لأ" || $prevChar == "لإ" || $prevChar == "ل") && mb_strpos("آأإا", $crntChar) !== false) {
                if (mb_strpos($this->_prevLink, $chars[$i - 2]) !== false) {
                    $form++;
                }
                if (mb_strpos($this->_vowel, $chars[$i - 1])) {
                    $output .= "&#x";
                    $output .= $this->getGlyphs($crntChar, $form) . ";";
                } else {
                    $output .= "&#x";
                    $output .= $this->getGlyphs($prevChar . $crntChar, $form) . ";";
                }
                $nextChar = $prevChar;
                continue;
            }
            if ($prevChar && mb_strpos($this->_prevLink, $prevChar) !== false) {
                $form++;
            }
            if ($nextChar && mb_strpos($this->_nextLink, $nextChar) !== false) {
                $form += 2;
            }
            $output .= "&#x" . $this->getGlyphs($crntChar, $form) . ";";
            $nextChar = $crntChar;
        }
        $output = $this->decodeEntities($output, $exclude = array("&"));
        return $output;
    }
    public function a4MaxChars($font)
    {
        $x = 381.6 - 31.57 * $font + 1.182 * pow($font, 2) - 0.02052 * pow($font, 3) + 0.0001342 * pow($font, 4);
        return floor($x - 2);
    }
    public function a4Lines($str, $font)
    {
        $str = str_replace(array("\r\n", "\n", "\r"), "\n", $str);
        $lines = 0;
        $chars = 0;
        $words = explode(" ", $str);
        $w_count = count($words);
        $max_chars = $this->a4MaxChars($font);
        for ($i = 0; $i < $w_count; $i++) {
            $w_len = mb_strlen($words[$i]) + 1;
            if ($chars + $w_len < $max_chars) {
                if (mb_strpos($words[$i], "\n") !== false) {
                    $words_nl = explode("\n", $words[$i]);
                    $nl_num = count($words_nl) - 1;
                    for ($j = 1; $j < $nl_num; $j++) {
                        $lines++;
                    }
                    $chars = mb_strlen($words_nl[$nl_num]) + 1;
                } else {
                    $chars += $w_len;
                }
            } else {
                $lines++;
                $chars = $w_len;
            }
        }
        $lines++;
        return $lines;
    }
    public function utf8Glyphs($str, $max_chars = 50, $hindo = true)
    {
        $str = str_replace(array("\r\n", "\n", "\r"), " \n ", $str);
        $str = str_replace("\t", "        ", $str);
        $lines = array();
        $words = explode(" ", $str);
        $w_count = count($words);
        $c_chars = 0;
        $c_words = array();
        $english = array();
        $en_index = -1;
        $en_words = array();
        $en_stack = array();
        for ($i = 0; $i < $w_count; $i++) {
            $pattern = "/^(\\n?)";
            $pattern .= "[a-z\\d\\/\\@\\#\\\$\\%\\^\\&\\*\\(\\)\\_\\~\\\"'\\[\\]\\{\\}\\;\\,\\|\\-\\.\\:!]*";
            $pattern .= "([\\.\\:\\+\\=\\-\\!،؟]?)\$/i";
            if (preg_match($pattern, $words[$i], $matches)) {
                if ($matches[1]) {
                    $words[$i] = mb_substr($words[$i], 1) . $matches[1];
                }
                if ($matches[2]) {
                    $words[$i] = $matches[2] . mb_substr($words[$i], 0, -1);
                }
                $words[$i] = strrev($words[$i]);
                array_push($english, $words[$i]);
                if ($en_index == -1) {
                    $en_index = $i;
                }
                $en_words[] = true;
            } else {
                if ($en_index != -1) {
                    $en_count = count($english);
                    for ($j = 0; $j < $en_count; $j++) {
                        $words[$en_index + $j] = $english[$en_count - 1 - $j];
                    }
                    $en_index = -1;
                    $english = array();
                    $en_words[] = false;
                } else {
                    $en_words[] = false;
                }
            }
        }
        if ($en_index != -1) {
            $en_count = count($english);
            for ($j = 0; $j < $en_count; $j++) {
                $words[$en_index + $j] = $english[$en_count - 1 - $j];
            }
        }
        if (isset($en_start)) {
            $last = true;
            $from = 0;
            foreach ($en_words as $key => $value) {
                if ($last !== $value) {
                    $to = $key - 1;
                    array_push($en_stack, array($from, $to));
                    $from = $key;
                }
                $last = $value;
            }
            array_push($en_stack, array($from, $key));
            $new_words = array();
            while (list($from, $to) = array_pop($en_stack)) {
                for ($i = $from; $i <= $to; $i++) {
                    $new_words[] = $words[$i];
                }
            }
            $words = $new_words;
        }
        for ($i = 0; $i < $w_count; $i++) {
            $w_len = mb_strlen($words[$i]) + 1;
            if ($c_chars + $w_len < $max_chars) {
                if (mb_strpos($words[$i], "\n") !== false) {
                    $words_nl = explode("\n", $words[$i]);
                    array_push($c_words, $words_nl[0]);
                    array_push($lines, implode(" ", $c_words));
                    $nl_num = count($words_nl) - 1;
                    for ($j = 1; $j < $nl_num; $j++) {
                        array_push($lines, $words_nl[$j]);
                    }
                    $c_words = array($words_nl[$nl_num]);
                    $c_chars = mb_strlen($words_nl[$nl_num]) + 1;
                } else {
                    array_push($c_words, $words[$i]);
                    $c_chars += $w_len;
                }
            } else {
                array_push($lines, implode(" ", $c_words));
                $c_words = array($words[$i]);
                $c_chars = $w_len;
            }
        }
        array_push($lines, implode(" ", $c_words));
        $maxLine = count($lines);
        $output = "";
        for ($j = $maxLine - 1; 0 <= $j; $j--) {
            $output .= $lines[$j] . "\n";
        }
        $output = rtrim($output);
        $output = $this->preConvert($output);
        if ($hindo) {
            $nums = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
            $arNums = array("٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩");
            foreach ($nums as $k => $v) {
                $p_nums[$k] = "/" . $v . "/ui";
            }
            $output = preg_replace($p_nums, $arNums, $output);
            foreach ($arNums as $k => $v) {
                $p_arNums[$k] = "/([a-z-\\d]+)" . $v . "/ui";
            }
            foreach ($nums as $k => $v) {
                $r_nums[$k] = "\${1}" . $v;
            }
            $output = preg_replace($p_arNums, $r_nums, $output);
            foreach ($arNums as $k => $v) {
                $p_arNums[$k] = "/" . $v . "([a-z-\\d]+)/ui";
            }
            foreach ($nums as $k => $v) {
                $r_nums[$k] = $v . "\${1}";
            }
            $output = preg_replace($p_arNums, $r_nums, $output);
        }
        return $output;
    }
    protected function decodeEntities($text, $exclude = array())
    {
        static $table = NULL;
        if (!isset($table)) {
            $table = array_flip(get_html_translation_table(HTML_ENTITIES));
            $table = array_map("utf8_encode", $table);
            $table["&apos;"] = "'";
        }
        $newtable = array_diff($table, $exclude);
        $pieces = explode("&", $text);
        $text = array_shift($pieces);
        foreach ($pieces as $piece) {
            if ($piece[0] == "#") {
                if ($piece[1] == "x") {
                    $one = "#x";
                } else {
                    $one = "#";
                }
            } else {
                $one = "";
            }
            $end = mb_strpos($piece, ";");
            $start = mb_strlen($one);
            $two = mb_substr($piece, $start, $end - $start);
            $zero = "&" . $one . $two . ";";
            $text .= $this->decodeEntities2($one, $two, $zero, $newtable, $exclude) . mb_substr($piece, $end + 1);
        }
        return $text;
    }
    protected function decodeEntities2($prefix, $codepoint, $original, &$table, &$exclude)
    {
        if (!$prefix) {
            if (isset($table[$original])) {
                return $table[$original];
            }
            return $original;
        }
        if ($prefix == "#x") {
            $codepoint = base_convert($codepoint, 16, 10);
        }
        if ($codepoint < 128) {
            $str = chr($codepoint);
        } else {
            if ($codepoint < 2048) {
                $str = chr(192 | $codepoint >> 6) . chr(128 | $codepoint & 63);
            } else {
                if ($codepoint < 65536) {
                    $str = chr(224 | $codepoint >> 12) . chr(128 | $codepoint >> 6 & 63) . chr(128 | $codepoint & 63);
                } else {
                    if ($codepoint < 2097152) {
                        $str = chr(240 | $codepoint >> 18) . chr(128 | $codepoint >> 12 & 63) . chr(128 | $codepoint >> 6 & 63) . chr(128 | $codepoint & 63);
                    }
                }
            }
        }
        if (in_array($str, $exclude)) {
            return $original;
        }
        return $str;
    }
}

?>