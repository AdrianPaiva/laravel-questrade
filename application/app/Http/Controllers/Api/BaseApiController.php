<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Input;

class BaseApiController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($data, string $message = 'Success!', int $code = 200)
    {
        $response = [
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError(string $message, $errors = [], int $code = 404)
    {
        $response = [
            'message' => $message,
            'data'    => null,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Get the list of related models to include
     * 
     * @return array
     */
    public function getEagerLoads()
    {
        return $this->getRequestArray('with');
    }

    /**
    * retrieve an array from the request
    * convert from comma separated string to array if necessary
    *
    * @param $parameter_name
    * @return array
    */
    public function getRequestArray(string $parameter_name): array
    {
        $value = Input::get($parameter_name, []);

        if (is_string($value)) {
            return explode(',', $value);
        }

        return (array) $value;
    }
}
