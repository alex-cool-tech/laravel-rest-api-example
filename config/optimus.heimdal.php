<?php

use App\Exceptions\Formatters as CustomFormatters;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Optimus\Heimdal\ResponseFactory;
use Symfony\Component\HttpKernel\Exception as SymfonyException;
use Optimus\Heimdal\Formatters;

return [
    'add_cors_headers' => false,

    // Has to be in prioritized order, e.g. highest priority first.
    'formatters' => [
        ValidationException::class => Formatters\UnprocessableEntityHttpExceptionFormatter::class,
        SymfonyException\UnprocessableEntityHttpException::class => Formatters\UnprocessableEntityHttpExceptionFormatter::class,
        SymfonyException\UnauthorizedHttpException::class => CustomFormatters\UnauthorizedHttpExceptionFormatter::class,
        AuthenticationException::class => CustomFormatters\UnauthorizedHttpExceptionFormatter::class,
        SymfonyException\NotFoundHttpException::class => CustomFormatters\NotFoundHttpExceptionFormatter::class,
        ModelNotFoundException::class => CustomFormatters\NotFoundHttpExceptionFormatter::class,
        SymfonyException\HttpException::class => Formatters\HttpExceptionFormatter::class,
        Exception::class => Formatters\ExceptionFormatter::class,
    ],

    'response_factory' => ResponseFactory::class,

    'reporters' => [
        /*'sentry' => [
            'class'  => \Optimus\Heimdal\Reporters\SentryReporter::class,
            'config' => [
                'dsn' => '',
                // For extra options see https://docs.sentry.io/clients/php/config/
                // php version and environment are automatically added.
                'sentry_options' => []
            ]
        ]*/
    ],

    'server_error_production' => 'An error occurred.'
];
