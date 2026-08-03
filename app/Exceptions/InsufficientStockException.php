<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InsufficientStockException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'error' => 'insufficient_stock',
            ], 422);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->getMessage());
    }
}
