<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        $message = $e->getMessage();

        $response = [
            'message' => $message,
            'messageEn' => $message, // Customize as needed for English message
            'messageAr' => $this->translateToArabic($message), // Customize this function for translation
            'errors' => $errors,
        ];

        return response()->json($response, $e->status);
    }

    /**
     * Translate the error message to Arabic (stub function for demonstration).
     * You can use a localization package or manual translation mapping here.
     */
    protected function translateToArabic($message)
    {
        // Example translation logic (replace with your actual implementation)
        $translations = [
            'The country code field is required.' => 'حقل رمز الدولة مطلوب.',
            'The email field is required.' => 'حقل البريد الإلكتروني مطلوب.',
        ];

        foreach ($translations as $key => $value) {
            $message = str_replace($key, $value, $message);
        }

        return $message;
    }
}
