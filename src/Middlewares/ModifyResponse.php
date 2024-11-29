<?php

namespace Attla\Joth\Middlewares;

use Attla\Joth\Joth;
use Attla\Support\Envir;
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
        $secret = Envir::getConfig('joth.secret');

        if ($secret && $response instanceof \Illuminate\Http\JsonResponse) {
            $params = array_merge(Joth::getAttrs($request), empty($attrs) ? [] : $attrs);

            $data = $response->getData(true);

            foreach ($params as $attr) {
                $value = Arr::get($data, $attr, '__undefined__');
                if ($value != '__undefined__') {
                    Arr::set($data, $attr, Joth::encode($value, $secret));
                }
            }

            Arr::set($data, 'pathForJoth', $request->path());
            $response->setData($data);
        }

        return $response;
    }
}
