<?php
/**
 * Extract translatable strings from theme and generate .pot file.
 * Run from theme root: php makepot.php
 */
$theme_dir = __DIR__;
$out_file  = $theme_dir . '/languages/chrysoberyl.pot';
$domain    = 'chrysoberyl';

$patterns = array(
    "/\b__\s*\(\s*['\"](.+?)['\"]\s*,\s*['\"]" . preg_quote( $domain, '/' ) . "['\"]\s*\)/s",
    "/\b_e\s*\(\s*['\"](.+?)['\"]\s*,\s*['\"]" . preg_quote( $domain, '/' ) . "['\"]\s*\)/s",
    "/\besc_html__\s*\(\s*['\"](.+?)['\"]\s*,\s*['\"]" . preg_quote( $domain, '/' ) . "['\"]\s*\)/s",
    "/\besc_attr__\s*\(\s*['\"](.+?)['\"]\s*,\s*['\"]" . preg_quote( $domain, '/' ) . "['\"]\s*\)/s",
    "/\besc_attr_e\s*\(\s*['\"](.+?)['\"]\s*,\s*['\"]" . preg_quote( $domain, '/' ) . "['\"]\s*\)/s",
);

$entries = array(); // msgid => array( refs => array( "file:line" ) )

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator( $theme_dir, RecursiveDirectoryIterator::SKIP_DOTS )
);
$php_files = array();
foreach ( $it as $f ) {
    $path = $f->getPathname();
    if ( pathinfo( $path, PATHINFO_EXTENSION ) === 'php' && basename( $path ) !== 'makepot.php' ) {
        $php_files[] = $path;
    }
}

foreach ( $php_files as $path ) {
    $rel = str_replace( $theme_dir . DIRECTORY_SEPARATOR, '', $path );
    $rel = str_replace( '\\', '/', $rel );
    $content = file_get_contents( $path );
    $lines = explode( "\n", $content );
    foreach ( $patterns as $pattern ) {
        if ( preg_match_all( $pattern, $content, $m, PREG_OFFSET_CAPTURE ) ) {
            foreach ( $m[1] as $match ) {
                $str = $match[0];
                $str = str_replace( array( '\\"', "\\'" ), array( '"', "'" ), $str );
                $line = 1 + substr_count( substr( $content, 0, $match[1] ), "\n" );
                $ref = $rel . ':' . $line;
                if ( ! isset( $entries[ $str ] ) ) {
                    $entries[ $str ] = array( 'refs' => array() );
                }
                if ( ! in_array( $ref, $entries[ $str ]['refs'], true ) ) {
                    $entries[ $str ]['refs'][] = $ref;
                }
            }
        }
    }
}

function pot_escape( $s ) {
    $s = str_replace( '\\', '\\\\', $s );
    $s = str_replace( '"', '\\"', $s );
    $s = str_replace( "\n", "\\n\n", $s );
    return $s;
}

$out = '';
$out .= '# Copyright (C) 2025 Chrysoberyl' . "\n";
$out .= '# This file is distributed under the same license as the Chrysoberyl theme.' . "\n";
$out .= 'msgid ""' . "\n";
$out .= 'msgstr ""' . "\n";
$out .= '"Project-Id-Version: Chrysoberyl 1.0.0\n"' . "\n";
$out .= '"Report-Msgid-Bugs-To: \n"' . "\n";
$out .= '"POT-Creation-Date: ' . date( 'Y-m-d H:iO' ) . "\\n\"\n";
$out .= '"MIME-Version: 1.0\n"' . "\n";
$out .= '"Content-Type: text/plain; charset=UTF-8\n"' . "\n";
$out .= '"Content-Transfer-Encoding: 8bit\n"' . "\n";
$out .= '"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\n"' . "\n";
$out .= '"Last-Translator: FULL NAME <EMAIL@ADDRESS>\n"' . "\n";
$out .= '"Language-Team: LANGUAGE <LL@li.org>\n"' . "\n";
$out .= '"X-Domain: chrysoberyl\n"' . "\n";
$out .= "\n";

ksort( $entries, SORT_STRING );
foreach ( $entries as $msgid => $data ) {
    foreach ( $data['refs'] as $ref ) {
        $out .= '#: ' . $ref . "\n";
    }
    $out .= 'msgid "' . pot_escape( $msgid ) . '"' . "\n";
    $out .= 'msgstr ""' . "\n";
    $out .= "\n";
}

if ( ! is_dir( $theme_dir . '/languages' ) ) {
    mkdir( $theme_dir . '/languages', 0755, true );
}
file_put_contents( $out_file, $out );
echo "Written " . count( $entries ) . " entries to " . $out_file . "\n";
