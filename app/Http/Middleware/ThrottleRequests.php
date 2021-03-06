<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class ThrottleRequests extends \Illuminate\Routing\Middleware\ThrottleRequests
{
    protected function buildResponse($key, $maxAttempts)
    {
       return $this->buildJsonResponse($key, $maxAttempts); // TODO: Change the autogenerated stub
    }

    protected function buildJsonResponse($key, $maxAttempts)
    {
        $retryAfter = $this->limiter->availableIn($key);
        $response = new JsonResponse([
            'scode'=>429,'message'=>'Some Errors Happened','errors'=>['You\'ve reached your usage limit, try again after '.$retryAfter." seconds"],'data'=>[]
        ], 429);


        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }
}