<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'App' => [
        'path' => './assets/Js/App.js',
        'entrypoint' => true,
    ],
    'AppBootstrap' => [
        'path' => './assets/Js/AppBootstrap.js',
        'entrypoint' => true,
    ],
    'DashboardBootstrap' => [
        'path' => './assets/Js/DashboardBootstrap.js',
        'entrypoint' => true,
    ],
    'AjaxLogin' => [
        'path' => './assets/Js/AjaxLogin.js',
        'entrypoint' => true,
    ],
    'App-Bootstrap.css' => [
        'path' => './assets/Css/App-Bootstrap.css',
        'type' => 'css',
        'entrypoint' => true,
    ],
    'Dashboard.css' => [
        'path' => './assets/Css/Dashboard.css',
        'type' => 'css',
        'entrypoint' => true,
    ],
    'bootstrap.css' => [
        'path' => './assets/Css/Bootstrap/bootstrap.css',
        'type' => 'css',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'canvas-confetti' => [
        'version' => '1.9.2',
    ],
    'bootstrap' => [
        'version' => '5.3.0',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.0',
        'type' => 'css',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'toastr' => [
        'version' => '2.1.4',
    ],
    'toastr/build/toastr.min.css' => [
        'version' => '2.1.4',
        'type' => 'css',
    ],
    'highlight.js' => [
        'version' => '11.10.0',
    ],
];
