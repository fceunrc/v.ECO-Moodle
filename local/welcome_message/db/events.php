<?php
defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname'   => '\core\event\user_enrolment_created',
        'callback'    => 'local_welcome_message_observer::user_enrolment_created',
        'includefile' => '/local/welcome_message/lib.php',
    ),
);