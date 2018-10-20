<?php
namespace Dinamiko\DKPDF;

if ( ! defined( 'DKPDF_PLUGIN_DIR' ) ) {
    define( 'DKPDF_PLUGIN_DIR', rtrim( dirname( dirname( __DIR__ ) ), '/' ) );
}
require_once DKPDF_PLUGIN_DIR . '/vendor/autoload.php';
