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
class I18N_Arabic_AutoSummarize
{
    private $_normalizeAlef = array("أ", "إ", "آ");
    private $_normalizeDiacritics = array("َ", "ً", "ُ", "ٌ", "ِ", "ٍ", "ْ", "ّ");
    private $_commonChars = array("ة", "ه", "ي", "ن", "و", "ت", "ل", "ا", "س", "م", "e", "t", "a", "o", "i", "n", "s");
    private $_separators = array(".", "\n", "،", "؛", "(", "[", "{", ")", "]", "}", ",", ";");
    private $_commonWords = array();
    private $_importantWords = array();
    public function __construct()
    {
        $words = file(dirname(__FILE__) . "/data/ar-stopwords.txt");
        $en_words = file(dirname(__FILE__) . "/data/en-stopwords.txt");
        $words = array_merge($words, $en_words);
        $words = array_map("trim", $words);
        $this->_commonWords = $words;
        $words = file(dirname(__FILE__) . "/data/important-words.txt");
        $words = array_map("trim", $words);
        $this->_importantWords = $words;
    }
    public function loadExtra()
    {
        $extra_words = file(dirname(__FILE__) . "/data/ar-extra-stopwords.txt");
        $extra_words = array_map("trim", $extra_words);
        $this->_commonWords = array_merge($this->_commonWords, $extra_words);
    }
    protected function summarize($str, $keywords, $int, $mode, $output, $style = NULL)
    {
        preg_match_all("/[^\\.\n\\،\\؛\\,\\;](.+?)[\\.\n\\،\\؛\\,\\;]/u", $str, $sentences);
        $_sentences = $sentences[0];
        if ($mode == "rate") {
            $str = preg_replace("/\\s{2,}/u", " ", $str);
            $totalChars = mb_strlen($str);
            $totalSentences = count($_sentences);
            $maxChars = round($int * $totalChars / 100);
            $int = round($int * $totalSentences / 100);
        } else {
            $maxChars = 99999;
        }
        $summary = "";
        $str = strip_tags($str);
        $normalizedStr = $this->doNormalize($str);
        $cleanedStr = $this->cleanCommon($normalizedStr);
        $stemStr = $this->draftStem($cleanedStr);
        preg_match_all("/[^\\.\n\\،\\؛\\,\\;](.+?)[\\.\n\\،\\؛\\,\\;]/u", $stemStr, $sentences);
        $_stemmedSentences = $sentences[0];
        $wordRanks = $this->rankWords($stemStr);
        if ($keywords) {
            $keywords = $this->doNormalize($keywords);
            $keywords = $this->draftStem($keywords);
            $words = explode(" ", $keywords);
            foreach ($words as $word) {
                $wordRanks[$word] = 1000;
            }
        }
        $sentencesRanks = $this->rankSentences($_sentences, $_stemmedSentences, $wordRanks);
        list($sentences, $ranks) = $sentencesRanks;
        $minRank = $this->minAcceptedRank($sentences, $ranks, $int, $maxChars);
        $totalSentences = count($ranks);
        for ($i = 0; $i < $totalSentences; $i++) {
            if ($minRank <= $sentencesRanks[1][$i]) {
                if ($output == "summary") {
                    $summary .= " " . $sentencesRanks[0][$i];
                } else {
                    $summary .= "<span class=\"" . $style . "\">" . $sentencesRanks[0][$i] . "</span>";
                }
            } else {
                if ($output == "highlight") {
                    $summary .= $sentencesRanks[0][$i];
                }
            }
        }
        if ($output == "highlight") {
            $summary = str_replace("\n", "<br />", $summary);
        }
        return $summary;
    }
    public function doSummarize($str, $int, $keywords)
    {
        $summary = $this->summarize($str, $keywords, $int, "number", "summary", $style);
        return $summary;
    }
    public function doRateSummarize($str, $rate, $keywords)
    {
        $summary = $this->summarize($str, $keywords, $rate, "rate", "summary", $style);
        return $summary;
    }
    public function highlightSummary($str, $int, $keywords, $style)
    {
        $summary = $this->summarize($str, $keywords, $int, "number", "highlight", $style);
        return $summary;
    }
    public function highlightRateSummary($str, $rate, $keywords, $style)
    {
        $summary = $this->summarize($str, $keywords, $rate, "rate", "highlight", $style);
        return $summary;
    }
    public function getMetaKeywords($str, $int)
    {
        $patterns = array();
        $replacements = array();
        $metaKeywords = "";
        array_push($patterns, "/\\.|\\n|\\،|\\؛|\\(|\\[|\\{|\\)|\\]|\\}|\\,|\\;/u");
        array_push($replacements, " ");
        $str = preg_replace($patterns, $replacements, $str);
        $normalizedStr = $this->doNormalize($str);
        $cleanedStr = $this->cleanCommon($normalizedStr);
        $str = preg_replace("/(\\W)ال(\\w{3,})/u", "\\1\\2", $cleanedStr);
        $str = preg_replace("/(\\W)وال(\\w{3,})/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})هما(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})كما(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})تين(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})هم(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})هن(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})ها(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})نا(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})ني(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})كم(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})تم(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})كن(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})ات(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})ين(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})تن(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})ون(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})ان(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})تا(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})وا(\\W)/u", "\\1\\2", $str);
        $str = preg_replace("/(\\w{3,})ة(\\W)/u", "\\1\\2", $str);
        $stemStr = preg_replace("/(\\W)\\w{1,3}(\\W)/u", "\\2", $str);
        $wordRanks = $this->rankWords($stemStr);
        arsort($wordRanks, SORT_NUMERIC);
        $i = 1;
        foreach ($wordRanks as $key => $value) {
            if ($this->acceptedWord($key)) {
                $metaKeywords .= $key . "، ";
                $i++;
            }
            if ($int < $i) {
                break;
            }
        }
        $metaKeywords = mb_substr($metaKeywords, 0, -2);
        return $metaKeywords;
    }
    protected function doNormalize($str)
    {
        $str = str_replace($this->_normalizeAlef, "ا", $str);
        $str = str_replace($this->_normalizeDiacritics, "", $str);
        $str = strtr($str, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz");
        return $str;
    }
    public function cleanCommon($str)
    {
        $str = str_replace($this->_commonWords, " ", $str);
        return $str;
    }
    protected function draftStem($str)
    {
        $str = str_replace($this->_commonChars, "", $str);
        return $str;
    }
    protected function rankWords($str)
    {
        $wordsRanks = array();
        $str = str_replace($this->_separators, " ", $str);
        $words = preg_split("/[\\s,]+/u", $str);
        foreach ($words as $word) {
            if (isset($wordsRanks[$word])) {
                $wordsRanks[$word]++;
            } else {
                $wordsRanks[$word] = 1;
            }
        }
        foreach ($wordsRanks as $wordRank => $total) {
            if (mb_substr($wordRank, 0, 1) == "و") {
                $subWordRank = mb_substr($wordRank, 1, mb_strlen($wordRank) - 1);
                if (isset($wordsRanks[$subWordRank])) {
                    unset($wordsRanks[$wordRank]);
                    $wordsRanks[$subWordRank] += $total;
                }
            }
        }
        return $wordsRanks;
    }
    protected function rankSentences($sentences, $stemmedSentences, $arr)
    {
        $sentenceArr = array();
        $rankArr = array();
        $max = count($sentences);
        for ($i = 0; $i < $max; $i++) {
            $sentence = $sentences[$i];
            $w = 0;
            $first = mb_substr($sentence, 0, 1);
            $last = mb_substr($sentence, -1, 1);
            if ($first == "\n") {
                $w += 3;
            } else {
                if (in_array($first, $this->_separators)) {
                    $w += 2;
                } else {
                    $w += 1;
                }
            }
            if ($last == "\n") {
                $w += 3;
            } else {
                if (in_array($last, $this->_separators)) {
                    $w += 2;
                } else {
                    $w += 1;
                }
            }
            foreach ($this->_importantWords as $word) {
                if ($word != "") {
                    $w += mb_substr_count($sentence, $word);
                }
            }
            $sentence = mb_substr(mb_substr($sentence, 0, -1), 1);
            if (!in_array($first, $this->_separators)) {
                $sentence = $first . $sentence;
            }
            $stemStr = $stemmedSentences[$i];
            $stemStr = mb_substr($stemStr, 0, -1);
            $words = preg_split("/[\\s,]+/u", $stemStr);
            $totalWords = count($words);
            if (4 < $totalWords) {
                $totalWordsRank = 0;
                foreach ($words as $word) {
                    if (isset($arr[$word])) {
                        $totalWordsRank += $arr[$word];
                    }
                }
                $wordsRank = $totalWordsRank / $totalWords;
                $sentenceRanks = $w * $wordsRank;
                array_push($sentenceArr, $sentence . $last);
                array_push($rankArr, $sentenceRanks);
            }
        }
        $sentencesRanks = array($sentenceArr, $rankArr);
        return $sentencesRanks;
    }
    protected function minAcceptedRank($str, $arr, $int, $max)
    {
        $len = array();
        foreach ($str as $line) {
            $len[] = mb_strlen($line);
        }
        rsort($arr, SORT_NUMERIC);
        $totalChars = 0;
        for ($i = 0; $i <= $int; $i++) {
            if (!isset($arr[$i])) {
                $minRank = 0;
                break;
            }
            $totalChars += $len[$i];
            if ($max <= $totalChars) {
                $minRank = $arr[$i];
                break;
            }
            $minRank = $arr[$i];
        }
        return $minRank;
    }
    protected function acceptedWord($word)
    {
        $accept = true;
        if (mb_strlen($word) < 3) {
            $accept = false;
        }
        return $accept;
    }
}

?>