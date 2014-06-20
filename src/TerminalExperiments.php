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
     * An experiment to make a little RPG perhaps in the terminal?
     */
    public function executeMouseClickDemo() {

        $this->_terminalActions->keyboardListener(function($key, $keycode) {

        });
    }

    /**
     * This function will print a spinning halo in the terminal. The idea here
     * is to test:
     *
     *  - Retrieving dimensions of a terminal
     *  - Clearing the page
     *  - Positioning the cursor at various positions and printing
     *  - General fps we can achieve (pretty good!)
     *
     * Creates an infinite loop, C c to quit
     */
    public function executeFullScreenAnimation() {

        // FPS (microsends)
        $fps = 50000;
        $speed = 0.2;

        // What to print in the middle
        $wordToPrint = '“Halo, it’s over.” - Cortana';

        // Get the dimensions of the terminal and calculate center points
        $dimensions = $this->_terminalActions->getDimensions();
        $midX = floor($dimensions->columns / 2);
        $midY = floor($dimensions->lines / 2);

        if(!$dimensions) {
            throw new Exception('Not able to get dimensions');
        }

        // Maximum radius is the minimum line or column minus some padding
        $maxRadius = floor(min($dimensions->lines, $dimensions->columns)) - 6;
        $minRadius = 15; // Minimum radius to go to

        $radiusStep = 2; // Radius step per frame
        $step = 2 * (pi() / 60); // Step to increase theta

        $direction = 1; // Direction (1 or -1)
        $frames = 0; // Current frame number

        // Each frame
        for($i = $minRadius; ;$i += $direction) {
            $this->_terminalActions->clearScreen();

            $radius = ($i * $speed + $radiusStep) % $maxRadius;

            if($direction < 0 && $radius <= $minRadius) {
                $direction = abs($direction);
            } else if ($direction > 0 && $radius >= $maxRadius - $radiusStep) {
                $direction = $direction * -1;
            }

            // Build something interesting...
            for($theta = 0; $theta < 2 * pi(); $theta += $step) {

                // Draw a bunch of circles with alternating radiuses (I.e.
                // circles within circles)
                for($circleI = 0; $circleI < 5; ++$circleI) {

                    $y = ceil($midY - (0.6 * $radius - $circleI) *
                         sin($theta + $frames / 80));
                    $x = ceil($midX + ($radius - $circleI) *
                         cos($theta + $frames / 10));

                    $this->_terminalActions->positionCursor($y, $x);
                    echo 'X';
                }
            }

            // Print something in the center
            $this->_terminalActions->positionCursor(
                $midY, ($midX - ceil(strlen($wordToPrint) / 2)));
            echo $wordToPrint;

            ++$frames;
            usleep($fps);
        }
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

