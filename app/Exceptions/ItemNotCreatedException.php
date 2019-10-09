<?php

namespace App\Exceptions;

use Exception;

class ItemNotCreatedException extends Exception
{
    private $itemType;

    public function __construct($itemType)
    {
        $this->itemType = $itemType;
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
            'message' => 'Item not created',
            'type' => 'ItemNotCreatedException',
            'data' => $this->itemType]
            , 410); // not created
    }
}
