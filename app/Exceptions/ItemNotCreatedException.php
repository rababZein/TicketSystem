<?php

namespace App\Exceptions;

use Exception;

class ItemNotCreatedException extends Exception
{
    private $itemType;
    private $errMsg;
    private $from;
    private $title;

    public function __construct($itemType, $errMsg = null, $from = null, $title = null)
    {
        $this->itemType = $itemType;
        if (! empty($errMsg)) {
            $this->errMsg = $errMsg;
        } else {
            $this->errMsg = 'Item not created';
        }
        $this->from = $from;
        $this->title = $title;
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
            'type' => 'ItemNotCreatedException',
            'data' => $this->itemType]
            , 410); // not created
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getErrorMessage()
    {
        return $this->errMsg;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getType()
    {
        return 'ItemNotCreatedException in '.$this->itemType;
    }
}
