<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // If it's an AJAX/JSON request, let Laravel handle it normally (usually returns JSON)
        if ($request->expectsJson()) {
            return parent::render($request, $e);
        }

        // Don't interfere with validation or authentication exceptions
        if ($e instanceof \Illuminate\Validation\ValidationException || 
            $e instanceof \Illuminate\Auth\AuthenticationException) {
            return parent::render($request, $e);
        }

        // For all other exceptions in web requests, redirect back with error message
        // EXCEPT if we're on a homepage/GET request where redirecting back could cause a loop
        if ($request->method() === 'GET' && ($request->path() === '/' || preg_match('/^[a-z]{2}$/', $request->path()))) {
             return parent::render($request, $e);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', [
                'heading' => 'System Error',
                'message' => $e->getMessage()
            ]);
    }
}
