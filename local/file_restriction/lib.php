<?php
// lib.php

defined('MOODLE_INTERNAL') || die();

/**
 * Observador de eventos para el plugin local_file_restriction.
 */
class local_file_restriction_observer {

    /**
     * Maneja el evento de creación de archivo en el sistema de archivos.
     *
     * @param \core\event\file_created $event El evento de creación de archivo.
     * @throws \moodle_exception Si la extensión del archivo no está permitida.
     */
    public static function file_created(\core\event\file_created $event) {
        global $CFG, $DB, $USER;

        // Obtenemos el registro del archivo creado.
        $file = $event->get_record_snapshot('files', $event->objectid);

        // Solo validar archivos que no sean directorios y que no sean del área de backup.
        if ($file->filename === '.' || $file->component === 'backup') {
            return;
        }

        // Obtenemos el nombre y la extensión del archivo.
        $filename = $file->filename;
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Obtenemos las extensiones permitidas y prohibidas de la configuración del plugin.
        $ext_permitidas = get_config('local_file_restriction', 'extensiones_permitidas');
        $ext_prohibidas = get_config('local_file_restriction', 'extensiones_prohibidas');

        // Convertimos las cadenas en arrays y eliminamos espacios en blanco.
        $ext_permitidas = array_filter(array_map('trim', explode(',', $ext_permitidas)));
        $ext_prohibidas = array_filter(array_map('trim', explode(',', $ext_prohibidas)));

        // Verificamos si el usuario es un docente (editingteacher).
        $context = \context::instance_by_id($file->contextid);
        $roles = get_user_roles($context, $USER->id);
        $is_teacher = false;
        foreach ($roles as $role) {
            if ($role->shortname === 'editingteacher') {
                $is_teacher = true;
                break;
            }
        }

        // Solo aplicamos la restricción a los docentes.
        if ($is_teacher) {
            // Verificamos si la extensión está prohibida o no está en la lista de permitidas.
            if (in_array($extension, $ext_prohibidas) || (!empty($ext_permitidas) && !in_array($extension, $ext_permitidas))) {
                // Eliminamos el archivo creado.
                $fs = get_file_storage();
                $stored_file = $fs->get_file(
                    $file->contextid,
                    $file->component,
                    $file->filearea,
                    $file->itemid,
                    $file->filepath,
                    $file->filename
                );

                if ($stored_file) {
                    $stored_file->delete();
                }

                // Registramos un error en los logs de Moodle.
                debugging("El usuario {$USER->id} intentó subir un archivo con extensión no permitida: {$extension}", DEBUG_DEVELOPER);

                // Lanzamos una excepción con un mensaje de error.
                throw new \moodle_exception('error_extension_no_permitida', 'local_file_restriction');
            }
        }
    }
}