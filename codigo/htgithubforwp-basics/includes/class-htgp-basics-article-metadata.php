<?php
if (!defined('ABSPATH')) { exit; }
final class HTGP_Basics_Article_Metadata {
    // Read only the scalar needed by the plugin; never evaluate YAML tags or objects.
    public static function parse($markdown) {
        $result = array('markdown' => $markdown, 'display_name' => '');
        if (!preg_match('/\A(?:\xEF\xBB\xBF)?---\r?\n(.*?)\r?\n---(?:\r?\n|\z)/s', $markdown, $header)) { return $result; }
        $result['markdown'] = substr($markdown, strlen($header[0]));
        $found = false;
        foreach (preg_split('/\r?\n/', $header[1]) as $line) {
            if (!preg_match('/^display_name:\s*(.*?)\s*$/D', $line, $field)) { continue; }
            if ($found) { $result['display_name'] = ''; return $result; }
            $found = true;
            $value = $field[1];
            if (substr($value, 0, 1) === '"') {
                $value = json_decode($value, true);
            } elseif (preg_match("/^'((?:[^']|'')*)'$/D", $value, $quoted)) {
                $value = str_replace("''", "'", $quoted[1]);
            } else { $value = ''; }
            $result['display_name'] = self::display_name($value);
        }
        return $result;
    }

    public static function display_name($value) {
        if (!is_string($value) || preg_match('/[\x00-\x1f\x7f<>\[\]]/', $value)) { return ''; }
        return trim(sanitize_text_field($value));
    }

}
