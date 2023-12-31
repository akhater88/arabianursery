<?php


namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected Request $request;

    protected Builder $query;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function apply(Builder $query)
    {
        $this->query = $query;

        foreach($this->filters() as $filter => $value){
            $filter_method_name = Str::camel($filter);

            if(method_exists($this, $filter_method_name) && $value){
                $this->$filter_method_name($value);
            }
        }

        return $this->query;
    }

    /**
     * @return array
     */
    protected function filters()
    {
        return $this->request->all();
    }
}
