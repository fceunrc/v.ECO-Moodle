<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) { // Asegurarse de que solo los administradores puedan acceder.

    $settings = new admin_settingpage('local_welcome_message', get_string('pluginname', 'local_welcome_message'));

    // Agregar la página de ajustes al menú de plugins locales.
    $ADMIN->add('localplugins', $settings);

    // Campo para el asunto del mensaje.
    $settings->add(new admin_setting_configtext(
        'local_welcome_message/subject',
        get_string('subject', 'local_welcome_message'),
        get_string('subject_desc', 'local_welcome_message'),
        '¡Has sido matriculado en "{$a->coursefullname}"!'
    ));

    // Campo para el cuerpo del mensaje.
    $settings->add(new admin_setting_configtextarea(
        'local_welcome_message/message',
        get_string('message', 'local_welcome_message'),
        get_string('message_desc', 'local_welcome_message'),
        '<p>Hola {$a->fullname}!</p>
<p>Has sido matriculado correctamente en el curso "<strong>{$a->coursefullname} - {$a->courseshortname}</strong>" del Ecosistema Virtual de la Facultad de Ciencias Económicas.</p>
<p>Ingresando a la pestaña "Mis cursos", puedes comenzar con las actividades.</p>
<p>¡Éxitos!</p>'
    ));
}