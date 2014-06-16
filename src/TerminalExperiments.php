<?php

/**
 * A series of experiments on the terminal to learn me some stuff that might
 * one day amount to something of use.
 */
class TerminalExperiments {

    /**
     * Our object with all of the know-how about doing terminal things
     *
     * @type TerminalActions
     */
    protected $_terminalActions;

    public function __construct(TerminalActions $terminalActions) {

        $this->_terminalActions = $terminalActions;
    }

    /**
     * Plays the syllables to 'Another one bites the dust'. It's the first song
     * that came into my head for this experiment!
     */
    public function executeBellExperiment() {

        $song = "
            // dun dun dun
            R8R8R86

            // another one breaks the dust (pause)
            R2R2R2R4R4R2R88

            // dun dun dun
            R8R8R86

            // another one breaks the dust (pause)
            R2R2R2R4R4R2R86

            // and another one does and another one does
            R2R2R2R2R4R4 R2R2R2R2R4R6

            // another one breaks the dust (pause)
            R2R2R2R4R4R2R88
            ";

        for($index = 0; $index < strlen($song); ++$index) {

            $character = $song[$index];

            if(is_numeric($character)) {

                // If numeric we convert it to 100'th of a second
                $sleepDuration = $character * 100000;
                $reduction = 0.85; // Speed up by lowering this, there's a limit
                                  // though!

                usleep($sleepDuration * $reduction);

            } else if($character === 'R') {

                // Ring if it's an R
                $this->_terminalActions->ringBell();
            } else {

                // Skip everything else (so we can comment)
                continue;
            }
        }
    }
}

