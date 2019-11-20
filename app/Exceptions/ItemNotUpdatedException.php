<?php

namespace App\Exceptions;

use Exception;

class ItemNotUpdatedException extends Exception
{
    private $itemType;
    private $errMsg;

    public function __construct($itemType, $errMsg = null)
    {
        $this->itemType = $itemType;
        if (! empty($errMsg)) {
            $this->errMsg = $errMsg;
        } else {
            $this->errMsg = 'Item not updated';
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
            'message' => $this->errMsg,
            'type' => 'ItemNotUpdatedException',
            'data' => $this->itemType]
            , 430); // not updated
    }
}
