<?php

namespace App\Exceptions;

use Exception;

class ItemNotDeletedException extends Exception
{
    private $itemType;
    private $errMsg;

    public function __construct($itemType, $errMsg = null)
    {
        $this->itemType = $itemType;
        if (! empty($errMsg)) {
            $this->errMsg = $errMsg;
        } else {
            $this->errMsg = 'Item not deleted';
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
            'type' => 'ItemNotDeletedException',
            'data' => $this->itemType]
            , 440); // not deleted
    }
}
