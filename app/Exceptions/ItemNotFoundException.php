<?php

namespace App\Exceptions;

use Exception;

class ItemNotFoundException extends Exception
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
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
            'message' => 'Item not found',
            'type' => 'ItemNotFoundException',
            'data' =>['id' => $this->id]]
            , 404); // not found
    }
}
