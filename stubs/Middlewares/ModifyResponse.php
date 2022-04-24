<?php

namespace App\Http\Middleware;

use Octha\Joth\Joth;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class ModifyResponse
{
    /**
     * Handle an incoming request
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null ...$params
     * @return mixed
     */
    public function handle(Request $request, \Closure $next, ...$attrs)
    {
        $response = $next($request);
        $secret = config('joth.secret');

        if ($secret && $response instanceof \Illuminate\Http\JsonResponse) {
            $params = array_merge(Joth::getAttrs($request), empty($attrs) ? [] : $attrs);

            $data = $response->getData(true);

            foreach ($params as $attr) {
                if ($value = Arr::get($data, $attr)) {
                    Arr::set($data, $attr, Joth::encode($value, $secret));
                }
            }

            Arr::set($data, 'pathForJoth', $request->path());
            $response->setData($data);
        }

        return $response;
    }
}
