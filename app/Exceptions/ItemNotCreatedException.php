<?php

namespace App\Exceptions;

use Exception;

class ItemNotCreatedException extends Exception
{
    private $itemType;
    private $errMsg;

    public function __construct($itemType, $errMsg = null)
    {
        $this->itemType = $itemType;
        if (! empty($errMsg)) {
            $this->message = $errMsg;
        } else {
            $this->message = 'Item not created';
        }
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
            'message' => $this->message,
            'type' => 'ItemNotCreatedException',
            'data' => $this->itemType]
            , 410); // not created
    }
}
