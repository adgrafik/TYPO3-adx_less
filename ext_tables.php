<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// Add static TypoScript
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Common/', 'ad: LESS – Common');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Example/', 'ad: LESS – Example');

?>
