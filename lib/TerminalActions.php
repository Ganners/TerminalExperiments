<?php

/**
 * The controls for my experiments
 */
class TerminalActions {

    /**
     * Moves the cursor up
     *
     * @int $count - The amount to move (defaults to 1)
     */
    public function cursorUp($count = 1) {

        echo "\e[{$count}A";
    }

    /**
     * Moves the cursor down
     *
     * @int $count - The amount to move (defaults to 1)
     */
    public function cursorDown($count = 1) {

        echo "\e[{$count}B";
    }

    /**
     * Moves the cursor left
     *
     * @int $count - The amount to move (defaults to 1)
     */
    public function cursorLeft($count = 1) {

        echo "\e[{$count}D";
    }

    /**
     * Moves the cursor right
     *
     * @int $count - The amount to move (defaults to 1)
     */
    public function cursorRight($count = 1) {

        echo "\e[{$count}C";
    }

    /**
     * Performs 'Backspace' 'Delete' 
     *
     * @int $count - The amount to move (defaults to 1)
     */
    public function backspaceDelete($count = 1) {

        for($i = 0; $i < $count; ++$i) {

            // Probably a better way to do this but I'm a bit lazy
            echo "\x08\x7F";
        }
    }

    /**
     * Rings that bell
     */
    public function ringBell() {
        echo "\x07";
    }
}

