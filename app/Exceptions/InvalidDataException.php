<?php

namespace App\Exceptions;

use Exception;

class InvalidDataException extends Exception
{
    private $data;
    protected $message;

    public function __construct($data, $message = 'Invalid data')
    {
        $this->data = $data;
        $this->message = $message;
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
            'type' => 'InvalidDataException',
            'data' => $this->data]
            , 420); // in-valid data
    }
}
