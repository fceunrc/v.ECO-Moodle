<?php
defined('MOODLE_INTERNAL') || die();

function local_page_redirect_course_viewed($event) {
    global $DB, $CFG, $USER, $PAGE, $SESSION;

    $courseid = $event->courseid;

    // Si ya se ha visitado la página configurada en esta sesión, no redirigir de nuevo.
    if (!empty($SESSION->has_seen_page[$courseid])) {
        return;
    }

    // Obtener el nombre de la página desde los ajustes.
    $pagename = get_config('local_page_redirect', 'pagename');
    if (empty($pagename)) {
        $pagename = 'Inicio'; // Valor por defecto si no está configurado
    }

    // Verificar si ya estamos en la página configurada.
    if ($PAGE->cm && $PAGE->cm->modname == 'page' && $PAGE->cm->instance == $DB->get_field('page', 'id', ['course' => $courseid, 'name' => $pagename])) {
        // Si ya estamos en la página configurada, no hacemos nada.
        return;
    }
    
    // Busca una página con el nombre configurado en el curso actual.
    $page = $DB->get_record('page', array('course' => $courseid, 'name' => $pagename));

    if ($page) {
        // Obtiene el coursemodule asociado a la página.
        $cm = $DB->get_record('course_modules', array('course' => $courseid, 'instance' => $page->id, 'module' => $DB->get_field('modules', 'id', array('name' => 'page'))));

        if ($cm) {
            // Marcar que el usuario ya ha visto la página configurada.
            $SESSION->has_seen_page[$courseid] = true;

            // Redirige a la URL de la página configurada utilizando el ID del módulo de curso (coursemodule ID).
            redirect(new moodle_url('/mod/page/view.php', array('id' => $cm->id)));
        }
    }
}

// Registrar el manejador de eventos.
function local_page_redirect_extend_navigation_course($navigation, $course, $context) {
    global $PAGE;
    
    if ($PAGE->course->id != SITEID) {
        local_page_redirect_course_viewed((object)['courseid' => $PAGE->course->id]);
    }
}