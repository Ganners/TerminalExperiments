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
     * An experiment to show what can be done with the keyboard listener..
     */
    public function executeKeyboardDemo() {

        // Get our terminal dimensions
        $dimensions = $this->_terminalActions->getDimensions();

        if(!$dimensions) {
            throw new Exception('Not able to get dimensions');
        }

        // Our poses and animations and stuff...
        $character['idle'] =
            " .---. " . PHP_EOL .
            "/     \ ". PHP_EOL .
            "|(\ /)|" . PHP_EOL .
            "(_ o _)" . PHP_EOL .
            " |===| " . PHP_EOL .
            " '-.-' " . PHP_EOL .
            " --|-- " . PHP_EOL .
            "   |   " . PHP_EOL .
            "  / \  " . PHP_EOL .
            " /   \ ";

        $character['left'][] =
            "  .---.  " . PHP_EOL .
            "/ _/   ' ". PHP_EOL .
            "' )     |" . PHP_EOL .
            "|_  )  / " . PHP_EOL .
            "|== |  | " . PHP_EOL .
            " \___./  " . PHP_EOL .
            " --|     " . PHP_EOL .
            "   |     " . PHP_EOL .
            "   /     " . PHP_EOL .
            " \/      ";

        $character['left'][] =
            "  .---.  " . PHP_EOL .
            "/ _/   ' ". PHP_EOL .
            "' )     |" . PHP_EOL .
            "|_  )  / " . PHP_EOL .
            "|== |  | " . PHP_EOL .
            " \___./  " . PHP_EOL .
            "   |     " . PHP_EOL .
            "  /|     " . PHP_EOL .
            "   /     " . PHP_EOL .
            "  _\     ";

        $character['right'][] =
            " .---. " . PHP_EOL .
            "'   \_ \ ". PHP_EOL .
            "|     ( '" . PHP_EOL .
            " \  (  _|" . PHP_EOL .
            " |  | ==|" . PHP_EOL .
            "  \.___/ " . PHP_EOL .
            "   |--   " . PHP_EOL .
            "   |     " . PHP_EOL .
            "   \     " . PHP_EOL .
            "    \/   ";

        $character['right'][] =
            " .---. " . PHP_EOL .
            "'   \_ \ ". PHP_EOL .
            "|     ( '" . PHP_EOL .
            " \  (  _|" . PHP_EOL .
            " |  | ==|" . PHP_EOL .
            "  \.___/ " . PHP_EOL .
            "   |     " . PHP_EOL .
            "   |\    " . PHP_EOL .
            "   \     " . PHP_EOL .
            "   /_    ";

        $character['jump'] =
            " .---. " . PHP_EOL .
            "/     \ ". PHP_EOL .
            "|(\ /)|" . PHP_EOL .
            "(_ o _)" . PHP_EOL .
            " |===| " . PHP_EOL .
            " '-.-' " . PHP_EOL .
            "  /|\  " . PHP_EOL .
            "  /'\  " . PHP_EOL .
            "  \ /  " . PHP_EOL .
            "     ";

        $character['crouch'] =
            "       " . PHP_EOL . 
            " .---. " . PHP_EOL .
            "/     \ ". PHP_EOL .
            "|(\ /)|" . PHP_EOL .
            "(_ o _)" . PHP_EOL .
            " |===| " . PHP_EOL .
            " '-.-' " . PHP_EOL .
            " --|-- " . PHP_EOL .
            "  /'\  " . PHP_EOL .
            "  \ /  ";

        // Defaults
        $pose = 'idle';
        $characterHeight = 10;
        $characterWidth = 8;

        // Calculate our starting position
        $position = (object) array(
            'line' => $dimensions->lines - $characterHeight,
            'column' => floor($dimensions->columns / 2) - floor($characterWidth / 2)
        );

        // Clear screen before anything...
        $this->_terminalActions->clearScreen();
        $this->_printPose($character['idle'], $position);
        $this->_printGround($dimensions);

        // The magic, start a keyboard listener and use it to handle stuff!
        $this->_terminalActions->keyboardListener(
            function($key, $keycode)
            use($character, $pose, $characterHeight, $position, $dimensions) {

            switch($key) {
                case 'arrow_up':
                    $position->line -= 4;
                    $this->_printPose($character['jump'], $position);
                    $this->_printGround($dimensions);
                    $position->line += 4;
                    $this->_printPose($character['idle'], $position, 200000);
                    $this->_printGround($dimensions);
                    break;
                case 'arrow_down':
                    $this->_printPose($character['crouch'], $position);
                    $this->_printGround($dimensions);
                    $this->_printPose($character['idle'], $position, 200000);
                    $this->_printGround($dimensions);
                    break;
                case 'arrow_left':
                    $position->column -= 2;
                    foreach($character['left'] as $cycle => $pose) {
                        $this->_printPose($pose, $position, 100000);
                        $this->_printGround($dimensions);
                    }
                    break;
                case 'arrow_right':
                    $position->column += 2;
                    foreach($character['right'] as $cycle => $pose) {
                        $this->_printPose($pose, $position, 100000);
                        $this->_printGround($dimensions);
                    }
                    break;
            }


        });
    }

        /**
        * Prints the ground for executeKeyboardDemo
        */
        protected function _printGround($dimensions) {

            // Print ground
            $this->_terminalActions->positionCursor(
                $dimensions->lines, 0);

            // Change colour to green
            echo $this->_terminalActions->getColoredString(
                str_repeat('‾', $dimensions->columns), 'green', 'green');
        }

        /**
        * Prints a character pose for executeKeyboardDemo
        */
        protected function _printPose($pose, $position, $delay = 0) {

            if($delay > 0) {
                usleep($delay);
            }

            // Clear the canvas
            $this->_terminalActions->clearScreen();

            foreach(explode(PHP_EOL, $pose) as $i => $line) {

                // Split up (so we can keep maintain the column)
                $this->_terminalActions->positionCursor(
                    $position->line + $i, $position->column);

                // Print out the individual line
                echo $this->_terminalActions->getColoredString(
                    $line, 'purple');
            }
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

