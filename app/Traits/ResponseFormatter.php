<?php

namespace App\Traits;

trait ResponseFormatter
{

    function formatResponse($code = 200, $message = 'OK', $data = null)
    {
        return response()->json(
            compact('code', 'message', 'data'),
            $code
        );
    }
}
