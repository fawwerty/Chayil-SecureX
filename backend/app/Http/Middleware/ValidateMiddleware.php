<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class ValidateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $formRequest): Response
    {
        // Build the full class name
        $formRequestClass = "App\\Http\\Requests\\{$formRequest}";
        
        // Check if class exists
        if (!class_exists($formRequestClass)) {
            return response()->json(['message' => "Form request class {$formRequestClass} not found"], 500);
        }

        // Create FormRequest instance using Laravel's method
        $formRequestInstance = $formRequestClass::createFromBase($request);
        $formRequestInstance->setContainer(app());
        $formRequestInstance->setRedirector(app('redirect'));
        
        if (!$formRequestInstance instanceof FormRequest) {
            return response()->json(['message' => "{$formRequest} must extend FormRequest"], 500);
        }

        // Check authorization
        if (!$formRequestInstance->authorize()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate using the FormRequest's validator
        // Ensure we have all input data including password_confirmation
        $input = $request->all();
        
        $validator = app('validator')->make(
            $input,
            $formRequestInstance->rules(),
            $formRequestInstance->messages() ?? []
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Replace request with validated data (excludes password_confirmation as expected)
        $request->replace($validator->validated());

        return $next($request);
    }
}

