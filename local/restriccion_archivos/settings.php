<?php
// settings.php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Creamos una nueva página de ajustes para el plugin.
    $settings = new admin_settingpage('local_restriccion_archivos', get_string('pluginname', 'local_restriccion_archivos'));

    // Añadimos la página de ajustes al árbol de administración.
    $ADMIN->add('localplugins', $settings);

    // Extensiones permitidas.
    $settings->add(new admin_setting_configtextarea(
        'local_restriccion_archivos/extensiones_permitidas',
        get_string('extensiones_permitidas', 'local_restriccion_archivos'),
        get_string('extensiones_permitidas_desc', 'local_restriccion_archivos'),
        'png, jpg, jpeg'
    ));

    // Extensiones prohibidas.
    $settings->add(new admin_setting_configtextarea(
        'local_restriccion_archivos/extensiones_prohibidas',
        get_string('extensiones_prohibidas', 'local_restriccion_archivos'),
        get_string('extensiones_prohibidas_desc', 'local_restriccion_archivos'),
        'pdf, docx, xlsx, pptx, csv'
    ));
}