<?php

namespace App\Exceptions;

use Exception;

class ItemsNotFoundException extends Exception
{
    public function __construct()
    {
        
    }
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'status' => false,
            'message' => 'Items not found',
            'type' => 'ItemsNotFoundException']
            , 404); // not found
    }
}
