<?php
// Archivo: local/welcome_message/lib.php

defined('MOODLE_INTERNAL') || die();

class local_welcome_message_observer {

    /**
     * Observador para el evento de creación de matriculación de usuario.
     *
     * @param \core\event\user_enrolment_created $event El evento de matriculación.
     */
    public static function user_enrolment_created(\core\event\user_enrolment_created $event) {
        global $DB, $CFG;

        // Obtener IDs del usuario y del curso.
        $userid = $event->relateduserid;
        $courseid = $event->courseid;

        // Verificar que los IDs existen.
        if (!$userid || !$courseid) {
            return;
        }

        // Obtener registros del usuario y del curso.
        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

        // Obtener los ajustes configurados.
        $configsubject = get_config('local_welcome_message', 'subject');
        $configmessage = get_config('local_welcome_message', 'message');

        // Preparar variables para reemplazar en el mensaje.
        $a = new stdClass();
        $a->fullname = fullname($user);
        $a->firstname = $user->firstname;
        $a->lastname = $user->lastname;
        $a->coursefullname = format_string($course->fullname);
        $a->courseshortname = format_string($course->shortname);

        // Reemplazar las variables en el asunto y el mensaje.
        $subject = str_replace(
            ['{$a->fullname}', '{$a->firstname}', '{$a->lastname}', '{$a->coursefullname}', '{$a->courseshortname}'],
            [$a->fullname, $a->firstname, $a->lastname, $a->coursefullname, $a->courseshortname],
            $configsubject
        );

        $messagehtml = str_replace(
            ['{$a->fullname}', '{$a->firstname}', '{$a->lastname}', '{$a->coursefullname}', '{$a->courseshortname}'],
            [$a->fullname, $a->firstname, $a->lastname, $a->coursefullname, $a->courseshortname],
            $configmessage
        );

        // Convertir el mensaje HTML a texto plano.
        $messagetext = html_to_text($messagehtml);

        // Crear el objeto de mensaje.
        $eventdata = new \core\message\message();
        $eventdata->component         = 'moodle'; // Usamos el componente 'moodle' genérico.
        $eventdata->name              = 'instantmessage'; // Nombre del mensaje genérico.

        // Modificación para cambiar el remitente a "v.Soporte FCE"
        $userfrom = clone \core_user::get_support_user();
        $userfrom->firstname = 'v.Soporte FCE';
        $userfrom->lastname = '';
        $eventdata->userfrom = $userfrom;

        $eventdata->userto            = $user;
        $eventdata->subject           = $subject;
        $eventdata->fullmessage       = $messagetext; // Mensaje en texto plano.
        $eventdata->fullmessageformat = FORMAT_HTML;
        $eventdata->fullmessagehtml   = $messagehtml; // Mensaje en HTML.
        $eventdata->smallmessage      = ''; // Resumen corto (opcional).
        $eventdata->notification      = 1; // Es una notificación (aparece en la campanita).
        $eventdata->contexturl        = $CFG->wwwroot . '/course/view.php?id=' . $course->id;
        $eventdata->contexturlname    = format_string($course->fullname);

        // Enviar el mensaje.
        $result = message_send($eventdata);

        // Verificar si el mensaje fue enviado correctamente.
        if (!$result) {
            debugging('No se pudo enviar el mensaje de bienvenida al usuario con ID ' . $user->id, DEBUG_DEVELOPER);
        }
    }
}