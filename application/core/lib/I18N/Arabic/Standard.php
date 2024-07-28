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
class I18N_Arabic_Standard
{
    public function __construct()
    {
    }
    public static function standard($text)
    {
        $patterns = array();
        $replacements = array();
        array_push($patterns, "/\\r\\n/u", "/([^\\@])\\n([^\\@])/u", "/\\r/u");
        array_push($replacements, "\n@@@\n", "\\1\n&&&\n\\2", "\n###\n");
        array_push($patterns, "/\\s*([\\.\\،\\؛\\:\\!\\؟])\\s*/u");
        array_push($replacements, "\\1 ");
        array_push($patterns, "/(\\. ){2,}/u");
        array_push($replacements, "...");
        array_push($patterns, "/\\s*([\\(\\{\\[])\\s*/u");
        array_push($replacements, " \\1");
        array_push($patterns, "/\\s*([\\)\\}\\]])\\s*/u");
        array_push($replacements, "\\1 ");
        array_push($patterns, "/\\s*\\\"\\s*(.+)((?<!\\s)\\\"|\\s+\\\")\\s*/u");
        array_push($replacements, " \"\\1\" ");
        array_push($patterns, "/\\s*\\-\\s*(.+)((?<!\\s)\\-|\\s+\\-)\\s*/u");
        array_push($replacements, " -\\1- ");
        array_push($patterns, "/\\sو\\s+([^و])/u");
        array_push($replacements, " و\\1");
        array_push($patterns, "/\\s+(\\w+)\\s*(\\d+)\\s+/");
        array_push($replacements, " <span dir=\"ltr\">\\2 \\1</span> ");
        array_push($patterns, "/\\s+(\\d+)\\s*(\\w+)\\s+/");
        array_push($replacements, " <span dir=\"ltr\">\\1 \\2</span> ");
        array_push($patterns, "/\\s+(\\d+)\\s*\\%\\s+/u");
        array_push($replacements, " %\\1 ");
        array_push($patterns, "/\\n?@@@\\n?/u", "/\\n?&&&\\n?/u", "/\\n?###\\n?/u");
        array_push($replacements, "\r\n", "\n", "\r");
        $text = preg_replace($patterns, $replacements, $text);
        return $text;
    }
}

?>