<?php

/**
 * The controls for my experiments
 */
class TerminalActions {

    /**
     * Listens for keyboard input and will pass the key to the $eventHandler
     * when it hears something.
     *
     * @param function $eventHandler ( (string) key, (int) keycode )
     *                 - If returns TRUE it will break out of loop
     *
     */
    public function keyboardListener($eventHandler) {

        stream_set_blocking(STDIN, false);

        // This is the magic that means we won't have to return after each
        // character input
        readline_callback_handler_install('', function() { });

        while(true) {

            $read = array(STDIN);
            $write = NULL;
            $except = NULL;
            $tv_sec = NULL;

            $stream = stream_select($read, $write, $except, $tv_sec);
            if($stream && in_array(STDIN, $read)) {

                // Get the input from stream
                $input = stream_get_contents(STDIN, 32);

                $keycode = '';
                $key = '';

                // Translate the input into a key
                switch(true) {
                    case $input === "\e[A":
                        // Arrow up escape sequence
                        $key = "arrow_up";
                        $keycode = 38;
                        break;

                    case $input === "\e[B":
                        // Arrow down escape sequence
                        $key = "arrow_down";
                        $keycode = 40;
                        break;

                    case $input === "\e[D":
                        // Arrow left escape sequence
                        $key = "arrow_left";
                        $keycode = 37;
                        break;

                    case $input === "\e[C":
                        // Arrow right escape sequence
                        $key = "arrow_right";
                        $keycode = 39;
                        break;

                    case ord($input) === 127:
                        // Backspace
                        $key = "backspace";
                        $keycode = 8;
                        break;

                    case ord($input) === 8:
                        // Backspace
                        $key = "delete";
                        $keycode = 46;
                        break;

                    case $input === "\e":
                        // Escape
                        $key = "escape";
                        $keycode = 27;
                        break;

                    case $input === "\t":
                        // Tab
                        $key = "tab";
                        $keycode = 9;
                        break;

                    case $input === "\n" || $input === "\r" || $input === "\r\r":
                        // Newline
                        $key = "enter";
                        $keycode = 13;
                        break;

                    case preg_match("/[a-zA-Z]{1}/", $input):
                        // If it's a letter then it's a letter, duh!
                        $key = strtolower($input);
                        $keycode = ord(strtolower($input)) - 32;
                        break;

                    case preg_match("/[0-9]{1}/", $input):
                        // If it's a number
                        $key = (int) $input;
                        $keycode = ord($input);
                        break;

                    default:
                        throw new Exception("Key not supported for {$input}");
                        break;
                }

                if(is_callable($eventHandler)) {
                    
                    // Call the event handler with the key and keycode
                    $response = $eventHandler($key, $keycode);

                    if($response) {
                        break;
                    }
                } else {

                    throw new Exception("First argument must be a function");
                }

            }
        }
    }

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

