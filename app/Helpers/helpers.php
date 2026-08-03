<?php

if (! function_exists('flash_message')) {
    /**
     * Flash a notification message to the session.
     *
     * @param string $message
     * @param string $type ('success', 'error', 'warning', 'info')
     * @return void
     */
    function flash_message(string $message, string $type = 'success'): void
    {
        session()->flash('flash_message', [
            'message' => $message,
            'type' => $type,
        ]);
    }
}

if (! function_exists('is_active_route')) {
    /**
     * Return active CSS class if current route matches target route or pattern.
     *
     * @param string|array $routes
     * @param string $activeClass
     * @param string $inactiveClass
     * @return string
     */
    function is_active_route(string|array $routes, string $activeClass = 'bg-emerald-600 text-white font-medium', string $inactiveClass = 'text-slate-300 hover:bg-slate-800 hover:text-white'): string
    {
        $routes = (array) $routes;

        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return $activeClass;
            }
        }

        return $inactiveClass;
    }
}

if (! function_exists('format_currency')) {
    /**
     * Format a numerical value into currency string.
     *
     * @param float|int $amount
     * @param string $currency
     * @param int $decimals
     * @return string
     */
    function format_currency(float|int $amount, string $currency = 'LKR', int $decimals = 2): string
    {
        return $currency . ' ' . number_format($amount, $decimals);
    }
}

if (! function_exists('format_date')) {
    /**
     * Format date using Carbon.
     *
     * @param mixed $date
     * @param string $format
     * @return string
     */
    function format_date(mixed $date, string $format = 'M d, Y'): string
    {
        if (! $date) {
            return 'N/A';
        }

        return \Illuminate\Support\Carbon::parse($date)->format($format);
    }
}

if (! function_exists('api_response')) {
    /**
     * Return standardized JSON API response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function api_response(mixed $data = null, string $message = 'Success', int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $code >= 200 && $code < 300,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
