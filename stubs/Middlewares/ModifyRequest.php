<?php

namespace App\Http\Middleware;

use Octha\Joth\Joth;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\ParameterBag;

class ModifyRequest
{
    /**
     * Attributes to be modified
     *
     * @var array
     */
    private $attrs = [];

    /**
     * Handle an incoming request
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$attrs)
    {
        $this->attrs = array_merge(Joth::getAttrs($request), empty($attrs) ? [] : $attrs);
        $this->modify($request);

        return $next($request);
    }

    /**
     * Modify the request's data
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function modify($request)
    {
        $this->modifyParameterBag($request->query);

        $this->modifyParameterBag($request->request);

        if ($request->isJson()) {
            $this->modifyParameterBag($request->json());
        }
    }

    /**
     * Modify the data in the parameter bag
     *
     * @param \Symfony\Component\HttpFoundation\ParameterBag $bag
     * @return void
     */
    protected function modifyParameterBag(ParameterBag $bag)
    {
        $bag->replace($this->transform($bag->all()));
    }

    /**
     * Modify the data in the given array
     *
     * @param array $data
     * @return array
     */
    protected function transform(array $data)
    {
        $secret = config('joth.secret');

        foreach ($this->attrs as $attr) {
            if ($value = Arr::get($data, $attr)) {
                Arr::set($data, $attr, Joth::decode($value, $secret));
            }
        }

        return $data;
    }
}
