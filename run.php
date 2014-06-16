<?php

require_once(__DIR__ . '/lib/TerminalActions.php');
require_once(__DIR__ . '/src/TerminalExperiments.php');

$terminalExperiments = new TerminalExperiments(new TerminalActions);

// Play a beautiful melody
$terminalExperiments->executeBellExperiment();
