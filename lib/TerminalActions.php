<?php

/**
 * The controls for my experiments
 */
class TerminalActions {

    /**
     * Get the dimensions for the terminal
     *
     * @return stdClass|bool - The dimensions or false on fail
     */
    public function getDimensions() {

        // Return if we know already
        if(isset($this->_dimensions) && is_object($this->_dimensions)) {
            return $this->_dimensions;
        }

        // Ask the terminal for it's dimensions
        $dimensions = (object) array(
            'lines'   => exec('tput lines'),
            'columns' => exec('tput cols'),
        );

        // Validate we got something we want or return false
        if(!is_numeric($dimensions->lines) ||
           !is_numeric($dimensions->columns)) {

           return false; 
        }
        
        // Set as object variable so we only have to execute once
        $this->_dimensions = $dimensions;

        return $dimensions;
    }

    public function positionCursor($line, $column) {

        echo "\033[{$line};{$column}H";
        return $this;
    }

    /**
     * Moves the cursor up
     *
     * @int $count - The amount to move (defaults to 1)
     */
    public function cursorUp($count = 1) {

        echo "\e[{$count}A";
        return $this;
    }

    /**
     * Moves the cursor down
     *
     * @int $count - The amount to move (defaults to 1)
     */
    public function cursorDown($count = 1) {

        echo "\e[{$count}B";
        return $this;
    }

    /**
     * Moves the cursor left
     *
     * @int $count - The amount to move (defaults to 1)
     */
    public function cursorLeft($count = 1) {

        echo "\e[{$count}D";
        return $this;
    }

    /**
     * Moves the cursor right
     *
     * @int $count - The amount to move (defaults to 1)
     */
    public function cursorRight($count = 1) {

        echo "\e[{$count}C";
        return $this;
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
        return $this;
    }

    /**
     * Rings that bell
     */
    public function ringBell() {

        echo "\x07";
        return $this;
    }

    /**
     * Clears the screen
     */
    public function clearScreen() {

        echo "\033[2J";
        return $this;
    }
}

