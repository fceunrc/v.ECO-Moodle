<?php
// db/events.php

defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname'   => '\core\event\file_created',
        'callback'    => 'local_restriccion_archivos_observer::file_created',
        'includefile' => '/local/restriccion_archivos/lib.php',
        'internal'    => false,
        'priority'    => 1000, // Mayor prioridad para que se ejecute antes que otros observadores
    ),
);