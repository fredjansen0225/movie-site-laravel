<?php namespace Lib;

use Helpers;

class Repository {
    
    /**
     * Paginate titles.
     * 
     * @return array
     */
    public function paginate($params, $returnQuery = false)
    {       
        $data = array();

        $data['page'] = isset($params['page']) ? $params['page'] : 1;
        $data['perPage'] = isset($params['perPage']) ? $params['perPage'] : 15;

        $results = $this->buildPaginateQuery($params, $data['page'], $data['perPage']);

        $data['items'] = $results['query']->get()->toArray();
        $data['query'] = $results['query'];

        $data['totalPages'] = $results['count'];
        
        return $data;
    }

    /**
     * Builds paginate query with given parameters.
     * 
     * @param  array   $params
     * @param  integer $page
     * @param  integer $perPage
     * 
     * @return array
     */
    public function buildPaginateQuery(array $params, $page = 1, $perPage = 15)
    {
        $table = $this->model->table;

        $query = $this->model;

        $query->from($table);

        $query = $this->appendParams($params, $query);

        $count = $query->cacheTags('count')->remember(2000)->count() / $perPage;
    
        $query = $query->skip($perPage * ($page - 1))->take($perPage);

        $query = $query->order(isset($params['order']) && $params['order'] ? $params['order'] : $table.'.created_at');

        $query = $query->cacheTags(array($this->model->table, 'pagination'))->remember(2000);
         
        return array('query' => $query, 'count' => $count);
    }

    /**
     * Restrict query by given params.
     *
     * @param  array $params
     * @param  Builder $query
     * @return Builder
     */
    protected function appendParams(array $params, $query)
    {
        $table = null;

        if(method_exists($query, 'getQuery'))
        {
            $table = $query->getQuery()->from;
        }
        else
        {
            $table = $query->table;
        }
        
        if (isset($params['query']))
        {
            if(in_array($table, array('titles', 'links')))
            {
                $query = $query->where('titles.title', 'LIKE', '%'.$params['query'].'%');
            }
            elseif($table == 'actors')
            {
                $query = $query->where('name', 'LIKE', '%'.$params['query'].'%');
            }
        }

        if (isset($params['type']) && $params['type'])
        {
            $query = $query->where('type', $params['type']);
        }

        return $query;
    }
	
}