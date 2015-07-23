<?php

register_callback(
  function($socket, $input) {
    if (message_part($input) === ':.sadness') {
      irc_send($socket, 'PRIVMSG ' . CHANNEL . ' http://phpsadness.com/sad/' . rand(1, 52));
    }
  }
);
