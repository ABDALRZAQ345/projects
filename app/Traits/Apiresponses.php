<?php

namespace App\Traits;

trait Apiresponses
{
    //
    protected function ok($message ){
        return $this->success($message,200);
    }

    protected  function success($message,$status = 200){
        return response()->json([
            'message' => $message,
            'status' => $status
        ],$status);
    }
}
