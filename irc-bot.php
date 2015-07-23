<?php

define('HOST',      'game.pixelcloud.ch');
define('PORT',      6667);
define('CHANNEL',   '#general');
define('NICKNAME',  'nerdinand-php-bot');

$joined = false;

$callbacks = array();

function irc_send($socket, $command) {
  $command .= "\n";
  echo 'output: ', $command;
  $number_of_bytes = socket_send($socket,  $command, strlen($command), 0);
}

function string_contains($haystack, $needle) {
  return strpos($haystack, $needle) !== false;
}

function message_part($input) {
  $parts = explode(' ', $input);
  return chop($parts[3]);
}

function require_modules() {
  $scan = glob('modules/*');

  foreach ($scan as $path) {
    if (preg_match('/\.php$/', $path)) {
      require_once $path;
    }
  }
}

function register_callback($callback) {
  array_push($GLOBALS['callbacks'], $callback);
}

require_modules();

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
$success = socket_connect($socket, HOST, PORT);

irc_send($socket, 'NICK ' . NICKNAME);
irc_send($socket, 'USER ' . NICKNAME . ' 0 * :' . NICKNAME);

do {
  $input = socket_read($socket, 2048, PHP_NORMAL_READ);

  if ($input === false) {
    break;
  }

  echo 'input: ', $input;

  if (!$joined && string_contains($input, 'MODE')) {
    irc_send($socket, 'JOIN ' . CHANNEL);
  } else {
    foreach ($callbacks as $callback) {
      $callback($socket, $input);
    }
  }
} while(true);
