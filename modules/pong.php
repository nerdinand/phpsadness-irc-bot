<?php

function react_to_ping($socket, $input) {
  $parts = explode(' ', $input);
  irc_send($socket, 'PONG ' . $parts[1]);
}

register_callback(
  function($socket, $input) {
    if (string_contains($input, 'PING')) {
      react_to_ping($socket, $input);
    }
  }
);
