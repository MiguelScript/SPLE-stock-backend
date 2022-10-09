<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
trait ApiResponse
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    private function successResponseWithData($message, $data, $code)
    {
        return  response()->json([
            'msg' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse($message, $code, $error_data = null)
    {
        $data = [
            'error' => $message,
            'code' => $code
        ];

        if ($error_data !== null) {
            $data['data'] = $error_data;
        }
        return  response()->json(
            $data,
            $code
        );
    }

    /* private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    } */

    protected function showAll($message, $collection, $code = 200)
    {
        /* if ($collection->isEmpty()) {
            return $this->successResponse($message, ['data' => $collection], $code);
        }
 */
        /*
        $transformer = $collection->first()->transformer;
        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sortData($collection, $transformer);
        $collection = $this->paginate($collection); 
        $collection = $this->transformData($collection, $transformer);
        $collection = $this->cacheResponse($collection);
        */

        return $this->successResponseWithData($message, $collection, $code);
    }

    protected function showOne($message, Model $instance, $code = 200)
    {
        /* $transformer = $instance->transformer;
        $instance = $this->transformData($instance, $transformer); */

        return $this->successResponseWithData($message, $instance, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse($message, $code);
    }
}
