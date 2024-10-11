<?php
// db/access.php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'local/restriccion_archivos:manage' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        ),
    ),
);