<?php
    /*
     * Functions to format Dates and/or Times from the database
     * http://php.net/manual/en/function.date.php for a full list of format characters
     *
     * @param string $v   		The database value (ie. 2016-03-07 09:30:00)
     * @return string           The formatted Date and/or Time
     */
    function dateFormat($v)
    {
        $theDate = date('F d, Y', strtotime($v));

        return $theDate;                                // March 07, 2016
    }
    function shortMonthFormat($v)
    {
        $theDate = date('M d, Y', strtotime($v));

        return $theDate;                                // Mar 07, 2016
    }
    function shortMonthTimeFormat($v)
    {
        $theDate = date('M d, Y  H:i', strtotime($v));

        return $theDate;                                // Mar 07, 2016 09:30
    }
    function dbDateFormat($v)
    {
        $theDate = date('Y-m-d', strtotime($v));

        return $theDate;                                // 2016-03-07
    }

    /*
     * Function to ellipse-ify text to a specific length
     *
     * @param string $text      The text to be ellipsified
     * @param int    $max       The maximum number of characters (to the word) that should be allowed
     * @param string $append    The text to append to $text
     * @return string           The shortened text
     */
    function ellipsis($text, $max = '', $append = '&hellip;')
    {
        if (strlen($text) <= $max) {
            return $text;
        }

        $replacements = [
            '|<br /><br />|' => ' ',
            '|&nbsp;|'       => ' ',
            '|&rsquo;|'      => '\'',
            '|&lsquo;|'      => '\'',
            '|&ldquo;|'      => '"',
            '|&rdquo;|'      => '"',
        ];

        $patterns = array_keys($replacements);
        $replacements = array_values($replacements);

        // Convert double newlines to spaces.
        $text = preg_replace($patterns, $replacements, $text);

        // Remove any HTML. We only want text.
        $text = strip_tags($text);

        $out = substr($text, 0, $max);
        if (strpos($text, ' ') === false) {
            return $out.$append;
        }

        return preg_replace('/(\W)&(\W)/', '$1&amp;$2', (preg_replace('/\W+$/', ' ', preg_replace('/\w+$/', '', $out)))).$append;
    }

    /*
     * Functions to Encode/Decode data
     *
     * @param string	$value		The text to be Encoded/Decoded
     * @return						The Encoded/Decoded text
     */
    // Encode Function
    function encodeIt($value)
    {
        return trim(
            base64_encode(
                mcrypt_encrypt(
                    MCRYPT_RIJNDAEL_256,
                    PEPPER,
                    $value,
                    MCRYPT_MODE_ECB,
                    mcrypt_create_iv(
                        mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB),
                        MCRYPT_RAND
                    )
                )
            )
        );
    }

    // Decode Function
    function decodeIt($value)
    {
        return trim(
            mcrypt_decrypt(
                MCRYPT_RIJNDAEL_256,
                PEPPER,
                base64_decode($value),
                MCRYPT_MODE_ECB,
                mcrypt_create_iv(
                    mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB),
                    MCRYPT_RAND
                )
            )
        );
    }

    /*
     * Function to only allow Alphanumeric characters and dash (-)
     *
     * @param string	$val		The string to be stripped
     * @return						The stripped text
     */
    function alphaNum($val)
    {
        $val = trim($val);
        $val = html_entity_decode($val);
        $val = strip_tags($val);
        $val = preg_replace('~[^ a-zA-Z0-9_.]~', ' ', $val);
        $val = preg_replace('~ ~', '-', $val);
        $val = preg_replace('~-+~', '-', $val);

        return $val;
    }
