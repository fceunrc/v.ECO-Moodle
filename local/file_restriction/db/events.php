<?php
// db/events.php

defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname'   => '\core\event\file_created',
        'callback'    => 'local_file_restriction_observer::file_created',
        'includefile' => '/local/file_restriction/lib.php',
        'internal'    => false,
        'priority'    => 1000, // Mayor prioridad para que se ejecute antes que otros observadores
    ),
);