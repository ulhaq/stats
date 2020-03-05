<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class RequestQueryWithRelations
{
    public function attach($resource, Request $request = null)
    {
        $request = $request ?? app('request');
        return tap($resource, function ($resource) use ($request) {
            // $this->getRequestWithRelations($request)->each(function ($value, $filter) use ($resource) {
            //     if (!empty($filter)) {
            //         // dd($filter, $value, $resource);
            //         dd($resource->items);
            //         $resource = $resource->where($filter, $value);
            //     }
            // });

            $this->getRequestIncludes($request)->each(function ($include) use ($resource) {
                if (trim($include) !== '') {
                    $resource->load($include);
                }
            });
        });
    }

    protected function getRequestIncludes(Request $request)
    {
        $param = $request->get("include");

        $exploded = empty($param) || trim($param) === '' ? '' : explode(',', $param);

        return collect($exploded);
    }

    // protected function getRequestWithRelations(Request $request)
    // {
    //     $param = $request->get("filter");
      
    //     return collect($param);
    // }
}
