<?php
defined('MOODLE_INTERNAL') || die();

function local_redir_inicio_course_viewed($event) {
    global $DB, $CFG, $USER, $PAGE, $SESSION;

    $courseid = $event->courseid;

    // Si ya se ha visitado la página "Inicio" en esta sesión, no redirigir de nuevo.
    if (!empty($SESSION->has_seen_inicio[$courseid])) {
        return;
    }

    // Verificar si ya estamos en la página "Inicio".
    if ($PAGE->cm && $PAGE->cm->modname == 'page' && $PAGE->cm->instance == $DB->get_field('page', 'id', ['course' => $courseid, 'name' => 'Inicio'])) {
        // Si ya estamos en la página "Inicio", no hacemos nada.
        return;
    }
    
    // Busca una página con el nombre "Inicio" en el curso actual.
    $page = $DB->get_record('page', array('course' => $courseid, 'name' => 'Inicio'));

    if ($page) {
        // Obtiene el coursemodule asociado a la página.
        $cm = $DB->get_record('course_modules', array('course' => $courseid, 'instance' => $page->id, 'module' => $DB->get_field('modules', 'id', array('name' => 'page'))));

        if ($cm) {
            // Marcar que el usuario ya ha visto la página "Inicio".
            $SESSION->has_seen_inicio[$courseid] = true;

            // Redirige a la URL de la página "Inicio" utilizando el ID del módulo de curso (coursemodule ID).
            redirect(new moodle_url('/mod/page/view.php', array('id' => $cm->id)));
        }
    }
}

// Registrar el manejador de eventos.
function local_redir_inicio_extend_navigation_course($navigation, $course, $context) {
    global $PAGE;
    
    if ($PAGE->course->id != SITEID) {
        local_redir_inicio_course_viewed((object)['courseid' => $PAGE->course->id]);
    }
}