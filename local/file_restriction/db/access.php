<?php
// db/access.php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'local/file_restriction:manage' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        ),
    ),
);