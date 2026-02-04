<?php
/**
 * Compile .po to .mo (gettext binary). Usage: php po2mo.php [input.po] [output.mo]
 * If no args: compiles chrysoberyl-en_US.po to languages/en_US.mo (WordPress loads {locale}.mo for themes).
 */
$theme_dir = __DIR__;
$po_file   = $argc >= 2 ? $argv[1] : $theme_dir . '/languages/chrysoberyl-en_US.po';
$mo_file   = $argc >= 3 ? $argv[2] : $theme_dir . '/languages/en_US.mo';

if ( ! is_file( $po_file ) ) {
    echo "File not found: $po_file\n";
    exit( 1 );
}

$content = file_get_contents( $po_file );
$entries = array();
$msgid = '';
$msgstr = '';
$in_msgid = false;
$in_msgstr = false;
$lines = explode( "\n", $content );
foreach ( $lines as $line ) {
    if ( preg_match( '/^msgid\s+"(.*)"\s*$/', $line, $m ) ) {
        $msgid = po_unescape( $m[1] );
        $in_msgid = true;
        $in_msgstr = false;
        continue;
    }
    if ( preg_match( '/^msgstr\s+"(.*)"\s*$/', $line, $m ) ) {
        $msgstr = po_unescape( $m[1] );
        $in_msgstr = true;
        $in_msgid = false;
        $entries[] = array( $msgid, $msgstr );
        continue;
    }
    if ( ( $in_msgid || $in_msgstr ) && preg_match( '/^"(.*)"\s*$/', $line, $m ) ) {
        $part = po_unescape( $m[1] );
        if ( $in_msgid ) {
            $msgid .= $part;
        } else {
            $entries[ count( $entries ) - 1 ][1] .= $part;
        }
        continue;
    }
    $in_msgid = false;
    $in_msgstr = false;
}

function po_unescape( $s ) {
    $s = str_replace( array( '\\n', '\\t', '\\r', '\\"' ), array( "\n", "\t", "\r", '"' ), $s );
    $s = str_replace( '\\\\', '\\', $s );
    return $s;
}

$n = count( $entries );
$header_size = 28;
$table_size = $n * 8;
$orig_table_offset = $header_size;
$trans_table_offset = $header_size + $table_size;
$data_start = $header_size + $table_size * 2;

// MO format: string data is raw strings (no length prefix); table has (length, file_offset).
$orig_data = '';
$trans_data = '';
$o = $data_start;
$orig_offsets = array();
foreach ( $entries as $e ) {
    $id = $e[0];
    $len = strlen( $id );
    $orig_offsets[] = array( $len, $o );
    $orig_data .= $id;
    $o += $len;
}
$trans_offsets = array();
foreach ( $entries as $e ) {
    $tr = $e[1];
    $len = strlen( $tr );
    $trans_offsets[] = array( $len, $o );
    $trans_data .= $tr;
    $o += $len;
}

$orig_tbl = '';
foreach ( $orig_offsets as $x ) {
    $orig_tbl .= pack( 'VV', $x[0], $x[1] );
}
$trans_tbl = '';
foreach ( $trans_offsets as $x ) {
    $trans_tbl .= pack( 'VV', $x[0], $x[1] );
}

$mo = pack( 'V', 0x950412de ) . pack( 'V', 0 ) . pack( 'V', $n )
    . pack( 'V', $orig_table_offset ) . pack( 'V', $trans_table_offset )
    . pack( 'V', 0 ) . pack( 'V', 0 )
    . $orig_tbl . $trans_tbl . $orig_data . $trans_data;
file_put_contents( $mo_file, $mo );
echo "Compiled: $mo_file\n";
