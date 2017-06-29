<?php

namespace App\Classes;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Log;

class Pager
{
    private $model;
    private $finalModel = null;
    private $baseTableName;
    private $baseTablePath;
    private $query;
    private $firstFageNumber = 1;
    private $maxSize;
    private $defaultPageSize;
    private $sortDefault = array('key' => null, 'value' => 'desc'); // 0 = recent, default result
    private $multipleQueryDivider = '||'; // default

    private $parsedQuery = [];
    private $searchQuery = [];
    private $filterQuery = [];
    private $sortQuery = [];
    private $rangeQuery = [];
    private $inQuery = [];

    private $partsModels = [];
    private $joinedmodel;

    private $pageNumber;
    private $pageSize;

    private $paginator;
    private $totalCount;
    private $currentPage;
    private $collection;

    public $DBquery;

    public function __construct()
    {
        DB::connection()->enableQueryLog();
        $this->DBquery = DB::getQueryLog();

        $this->multipleQueryDivider = config("pager.comparision.multipleQueryDivider");
        $this->maxSize = config("pager.default.pageSize.max");
        $this->defaultPageSize = config("pager.default.pageSize.basic");
    }

    public function search($model, $query)
    {
        $this->baseTableName = with($model)->getTable();
        $this->baseTablePath = $model;
        $this->setModel($model);
        $this->query = $query;
        $this->parsedQuery = $this->queryParser($query);
        $this->rangeQuery = $this->parsedQuery['range'];
        $this->searchQuery = $this->parsedQuery['search'];
        $this->filterQuery = $this->parsedQuery['filter'];
        $this->inQuery = $this->parsedQuery['in'];
        $this->sortQuery = $this->parsedQuery['sort'];
        $this->pageNumber = $this->setPageNumber();
        $this->pageSize = $this->setPageSize();
        return $this;
    }

    // inject 는 쿼리스트랑과 상관없이 무조건적인 데이터 셋이 필요할 때 사용합니다.
    public function injectRange($query)
    {
        if (!is_array($query)) $query = [$query];
        foreach ($query as $key => $value) {
            $this->rangeQuery = array_merge($this->rangeQuery, $this->getParsedQuery('range', $value)['range']);
        }
        return $this;
    }

    public function injectFilter($query)
    {
        if (!is_array($query)) $query = [$query];
        foreach ($query as $key => $value) {
            $this->filterQuery = array_merge($this->filterQuery, $this->getParsedQuery('filter', $value)['filter']);
        }
        return $this;
    }

    public function injectSearch($query)
    {
        if (!is_array($query)) $query = [$query];
        foreach ($query as $key => $value) {
            $this->searchQuery = array_merge($this->searchQuery, $this->getParsedQuery('search', $value)['search']);
        }
        return $this;
    }

    public function injectIn($query)
    {
        if (!is_array($query)) $query = [$query];
        foreach ($query as $key => $value) {
            $this->inQuery = array_merge($this->inQuery, $this->getParsedQuery('in', $value)['in']);
        }
        return $this;
    }

    public function injectSort($query)
    {
        if (!is_array($query)) $query = [$query];
        foreach ($query as $key => $value) {
            $this->sortQuery = array_merge($this->sortQuery, $this->getParsedQuery('sort', $value)['sort']);
        }
        return $this;
    }

    public function injectPageSize($size)
    {
        $this->pageSize = $size;
        return $this;
    }

    public function queryParser($query)
    {
        $result = [
            "range"  => [],
            "filter" => [],
            "search" => [],
            "in"     => [],
            "sort"   => [],
        ];
        $whiteList = ['filter', 'search', 'sort'];
        foreach ($query as $key => $value) {
            if (in_array($key, $whiteList)) {
                $parse = $this->getParsedQuery($key, $value);
                $result = array_merge($result, $parse);
            }
        }
        return $result;
    }

    private function getParsedQuery($type, $query)
    {
        $explodeQuery = explode($this->multipleQueryDivider, $query);
        $result = [
            "range"  => [],
            "filter" => [],
            "search" => [],
            "in"     => [],
            "sort"   => [],
        ];
        foreach ($explodeQuery as $key => $value) {
            $queryString = urldecode($value);
            // filter = < > <= >= :
            // search :
            // in ;
            // range ~
            // sort :
            $split = preg_split('(<[=>]?|>=?|:|;)', $queryString);
            $checkColumn = $this->columnChecker($split[0]);
            $searchTable = $checkColumn['tableName'];
            $searchKey = $checkColumn['columnName'];
            $searchValue = $this->stringToValueChecker($split[1]);
            $comparison = $this->getComparision($queryString, $split);

            if ($this->isRangeFilter($searchValue)) {
                $result['range'][] = array(
                    'table' => $searchTable,
                    'key'   => $searchKey,
                    'value' => $this->getRangeArray($searchValue),
                );
            } else if ($this->isInFilter($comparison)) {
                $decodeValue = json_decode($searchValue);
                $result['in'][] = array(
                    'table' => $searchTable,
                    'key'   => $searchKey,
                    'value' => is_array($decodeValue) ? $decodeValue : [$decodeValue],
                );
            } else if ($type == 'filter') {
                $result['filter'][] = array(
                    'table'       => $searchTable,
                    'key'         => $searchKey,
                    'comparision' => $comparison,
                    'value'       => $searchValue,
                );
            } else if ($type == 'search') {
                $result['search'][] = array(
                    'table'       => $searchTable,
                    'key'         => $searchKey,
                    'comparision' => $comparison,
                    'value'       => $searchValue,
                );
            } else if ($type == 'sort') {
                $result['sort'][] = array(
                    'table'       => $searchTable,
                    'key'         => $searchKey,
                    'value'       => $searchValue,
                );
            }else{
                abort(400);
            }
        }
        return $result;
    }

    private function getComparision($subject, $search)
    {
        $result = str_replace($search, '', $subject);
        $result = $result == ':' ? '=' : $result;
        return $result;
    }

    private function isRangeFilter($value)
    {
        return strpos($value, '~');
    }

    private function isInFilter($comparison)
    {
        return $comparison === ';';
    }

    private function isColumnQuery($comparison)
    {
        return $comparison !== '=';
    }

    private function getRangeArray($value)
    {
        $explodeValue = explode('~', $value);
        return array($explodeValue[0], $explodeValue[1]);
    }

    private function columnChecker($string)
    {
        $checked = $this->stringToKeyChecker($string);
        $explodeValue = explode('.', $checked);
        if (count($explodeValue) > 1) {
            return [
                "tableName"  => $explodeValue[0],
                "columnName" => $explodeValue[1],
            ];
        }
        return [
            "tableName"  => $this->baseTableName,
            "columnName" => $checked,
        ];
    }

    // Functions that must be added continuously
    private function stringToKeyChecker($string)
    {
        return self::getFieldName($string);
    }
    public static function getFieldName($string){
        $checked = config("pager.searchKeyConversion.$string");
        if (is_null($checked)) abort(400, 'Undefinded search key ' . $string);
        return $checked;
    }
    public static function getDBField($string){
        // RAW쿼리로 때려박을때 테이블.필드 리턴
        $fieldName = self::getFieldName($string);
        $explode = explode('.',$fieldName);
        $result = '`'.$explode[0].'`.`'.$explode[1].'`';
        return $result;
    }

    private function stringToValueChecker($string)
    {
        $checked = config("pager.searchValueConversion.$string");
        if (is_null($checked)) return $string;
        return $checked;
    }

    private function setModel($section)
    {
        $ready = explode('\\',get_class($section));
        $modelName = $ready[1];
        $checked = config("pager.partsModel.$modelName");
        if (!is_null($checked)) {
            $this->setPartsModel($checked);
        }
        $this->model = new $this->baseTablePath;
    }

    // must exist in main model in parts function
    private function setPartsModel($parts)
    {
        foreach ($parts as $value) {
            $this->partsModels[] = $value;
        }
    }

    private function setPageNumber()
    {
        return isset($this->query['pageIndex'])
            ? $this->query['pageIndex']
            : $this->firstFageNumber;
    }

    private function setPageSize()
    {
        return isset($this->query['pageSize']) // && $this->query['pageSize'] <= $this->maxSize
            ? $this->query['pageSize']
            : $this->defaultPageSize;
    }

    private function modelFiltering()
    {
        $this->initModelFilter($this->joinedmodel);

        if ($this->hasQuery($this->rangeQuery)) {
            foreach ($this->rangeQuery as $key => $value) {
                $this->finalModel = $this->finalModel->whereBetween(
                    $value['table'] . '.' . $value['key'],
                    $value['value']
                );
            }
        }
        if ($this->hasQuery($this->searchQuery)) {
            foreach ($this->searchQuery as $key => $value) {
                $this->finalModel = $this->finalModel->where($value['table'] . '.' . $value['key'], 'LIKE', '%' . $value['value'] . '%');
            }
        }
        if ($this->hasQuery($this->filterQuery)) {
            foreach ($this->filterQuery as $key => $value) {
                $this->finalModel = $this->finalModel->where($value['table'] . '.' . $value['key'], $value['comparision'], $value['value']);
            }
        }
        if ($this->hasQuery($this->inQuery)) {
            foreach ($this->inQuery as $key => $value) {
                $this->finalModel = $this->finalModel->whereIn(
                    $value['table'] . '.' . $value['key'],
                    $value['value']
                );
            }
        }
    }

    private function modelSorting()
    {
        $sortQuery = $this->sortQuery;
        if ($this->hasQuery($sortQuery)) {
            foreach ($sortQuery as $key => $value) {
                if( $value['key'] === 'RAW' ){
                    // RAW 쿼리 검색
                    // direction 조정이 필요하다면 추가 로직 필요함
                    $this->finalModel = $this->finalModel->orderBy(
                        DB::raw($value['value']) , 'desc'
                    );
                }else{
                    // 쿼리스트링 검색
                    $this->finalModel = $this->finalModel->orderBy(
                        $value['table'] . '.' . $value['key'],
                        $this->sortDirectionCheck($value['key'],$value['value'])
                    );
                }
            }
        } else {
            $this->initModelSort();
        }
    }

    private function sortDirectionCheck($key,$direction)
    {
        if ($direction == 'desc' || $direction == 'asc') return $direction;
        return abort(400, 'Check Sort Direction');
    }

    private function hasQuery($query)
    {
        return count($query);
    }

    private function initModelFilter($baseModel)
    {
        $this->finalModel = $baseModel;
    }

    public function initModelSort()
    {
        $this->finalModel = $this->finalModel->orderBy(
            $this->baseTableName . '.' . $this->baseTablePath->getKeyName(),
            $this->sortDefault['value']
        );
    }

    private function joinModel($baseModel)
    {
        $joinedModel = $baseModel;
        $operator = '=';
        foreach ($this->partsModels as $key => $value) {
            $joinedModel = $joinedModel->leftjoin(
                $value['join_table_name'],
                $this->getJoinTableColumnInfo($value),
                $operator,
                $value['join_table_name'] . '.' . $value['join_table_key_column']
            );
        }
        return $joinedModel;
    }

    private function getJoinTableColumnInfo($value)
    {
        return is_null(strpos($value['base_table_key_column'], '.'))
            ? $this->baseTableName . '.' . $value['base_table_key_column']
            : $value['base_table_key_column'];
    }

    public function getCollection()
    {
        $this->joinedmodel = $this->joinModel($this->model);
        $this->modelFiltering();
        $this->modelSorting();

        $this->paginator = $this->finalModel
            ->select($this->baseTableName . '.*')
            ->paginate($this->pageSize, ['*'], 'page', $this->pageNumber);
        Log::debug('paginate', [DB::getQueryLog()]);
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
