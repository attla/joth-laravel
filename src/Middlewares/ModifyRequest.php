<?php

namespace Attla\Joth\Middlewares;

use Attla\Joth\Joth;
use Attla\Support\Envir;
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
        $secret = Envir::getConfig('joth.secret');

        foreach ($this->attrs as $attr) {
            $value = Arr::get($data, $attr, '__undefined__');
            if ($value != '__undefined__') {
                Arr::set($data, $attr, Joth::decode($value, $secret));
            }
        }

        return $data;
    }
}
