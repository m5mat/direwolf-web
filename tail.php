<?php

  function tail($filepath, $lines = 1, $adaptive = true) {
    // Open the file
    $f = @fopen($filepath, "rb");
    if ( $f === false ) return false;

    // Set buffer size according to the number of lines to retrieve
    if (!adaptive) {
      $buffer = 4096;
    } else {
      $buffer = $lines < 2 ? 64 : ($lines < 10 ? 512 : 4096);
    }

    // Jump to last character
    fseek($f, -1, SEEK_END);

    // Read it and adjust line number if necessary, otherwise result would be wrong if the file doesn't end with a blank line.
    if ( fread($f, 1) != "\n" ) {
      $lines -= 1;
    }

    // Start reading
    $output = '';
    $chunk = '';

    while (ftell($f) > 0 && $lines >= 0 ) {
      $seek = min(ftell($f), $buffer);
      fseek($f, -$seek, SEEK_CUR);
      $output = ($chunk = fread($f, $seek)) . $output;
      fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
      $lines -= substr_count($chunk, "\n");
    }

    while ( $lines++ < 0 ) {
      $output = substr($output, strpos($output, "\n") + 1);
    }

    fclose($f);
    return trim($output);
  }
?>
