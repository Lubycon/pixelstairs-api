<?php

namespace App\Classes\Pager;

use DB;
use Log;
use Illuminate\Database\Eloquent\Builder;

class Modeling
{
    private $model;
    private $modelClassName;

    private $defaultPageNumber = 1;
    private $defaultPageSize;
    private $maxSize;
    private $sortDefault;

    private $pageNumber;
    private $pageSize;

    private $paginator;
    private $totalCount;
    private $currentPage;
    private $collection;

    public function __construct(Builder $model)
    {
        $this->model = $model;
        $this->modelClassName = get_class($model->getModel());
        $this->maxSize = config("pager.default.pageSize.max");
        $this->defaultPageSize = config("pager.default.pageSize.basic");
        $this->sortDefault = config("pager.default.sort");
    }

    public function setQuery($queryObject)
    {
        foreach ($queryObject as $index => $object) {
            if( $this->_isSameModel($object) ){
                // 바로 쿼리
                $this->model->where($object['column'],$object['comparison'],$object['value']);
            }else{
                $this->model->whereHas($object['relation'], function(Builder $query) use ($object){
                    $query->where($object['column'],$object['comparison'],$object['value']);
                });
            }
        }
        return $this;
    }

    private function _isSameModel($queryObject){
        return $queryObject['model'] === $this->modelClassName;
    }


//    // FIXME :: 이거 어디 둘지 다시 고민.
//    private function _setPageNumber()
//    {
//        return isset($this->query['pageIndex'])
//            ? $this->query['pageIndex']
//            : $this->defaultPageNumber;
//    }
//    // FIXME :: 이거 어디 둘지 다시 고민.
//    private function _setPageSize()
//    {
//        return isset($this->query['pageSize']) // && $this->query['pageSize'] <= $this->maxSize
//            ? $this->query['pageSize']
//            : $this->defaultPageSize;
//    }


    public function getCollection()
    {
        $this->paginator = $this->model->paginate(10, ['*'], 'page', 1);
        $this->totalCount = $this->paginator->total();
        $this->currentPage = $this->paginator->currentPage();
        $this->collection = $this->paginator->getCollection();
        return $this->collection;
    }

    public function getPageInfo()
    {
        return (object)[
            "totalCount"  => $this->totalCount,
            "currentPage" => $this->currentPage,
        ];
    }
}
