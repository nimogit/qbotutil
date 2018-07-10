<?php
use Elasticsearch\ClientBuilder;

require '/home/iuw/web/t.iuw.me/public_html/qtdev1033/vendor/autoload.php';
$hosts = ['http://guest:10330178@search.qassioun.com'];
$client = ClientBuilder::create()->setHosts($hosts)->build();

class Qelastic
{

    public function esSearch($query, $pagelimit, $page)
    {
        global $hosts;
        global $client;
        $page = $page - 1;
        $from = $pagelimit * $page;
        $params = ['size' => $pagelimit, 'from' => $from, 'index' => 'kassiounpaper', 'type' => 'AFCONTENT', 'body' => ['query' => ['constant_score' => ['filter' => ['bool' => ['must' => [0 => ['terms' => ['language' => [0 => '*', 1 => 'ar-AA', ], ], ], 1 => ['term' => ['state' => 1, ], ], 2 => ['term' => ['cat_state' => 1, ], ], ], 'should' => [0 => ['match' => ['title' => ['query' => $query, 'operator' => 'and', 'boost' => 1.7, ], ], ], 1 => ['match' => ['description' => ['query' => $query, 'operator' => 'and', 'boost' => 0.7, ], ], ], 2 => ['match' => ['path' => ['query' => $query, 'operator' => 'and', 'boost' => 2, ], ], ], ], 'must_not' => [], 'filter' => [], ], ], ], ], ]];
        $response = $client->search($params);
        $total = $response['hits']['total'];
        if (($from - $total) <= $pagelimit)
        {
            if (($from - $total) <= 0)
            {
                $hits = $pagelimit;
            }
            else
            {
                $hits = $from - $total;
            }
        }
        else
        {
            $hits = 0;
        }

        $obj = new stdClass();
        $obj->total = $total;

        $hits = count($response['hits']['hits']);
        $result = null;
        $i = 0;

        while ($i < $hits)
        {
            $result[$i] = $response['hits']['hits'][$i]['_source'];
            $i++;
        }
        $resultsarray = [];
        foreach ($result as $key => $value)
        {
            $obj->results = array(
                'name' => $value['title'],
                'description' => $value['description'],
                'url' => $value['path'],
                'repository' => $value['path'],
                'id' => $value['id'],
				'thumb' => 'https://kassiounpaper.com/'. str_replace('_S.jpg','_XS.jpg',$value['image']),
                'downloads' => 1022,
                'favers' => 1111
            );
            $arritems[] = $obj->results;
            $arritems = $arritems++;
        }

        $obj->results = $arritems;
        $obj->next = "";

        $response = json_decode(json_encode($obj, JSON_UNESCAPED_UNICODE) , JSON_UNESCAPED_UNICODE);
        $response = json_encode($response, JSON_UNESCAPED_UNICODE);
        return $response;
    }
	
	    public function esCatSearch($category, $pagelimit, $page)
    {
        global $hosts;
        global $client;
        $page = $page - 1;
        $from = $pagelimit * $page;
        $params = ['size' => $pagelimit, 'from' => $from, 'index' => 'kassiounpaper', 'type' => 'AFCONTENT', 'body' => ['query' => ['constant_score' => ['filter' => ['bool' => ['must' => [0 => ['terms' => ['language' => [0 => '*', 1 => 'ar-AA', ], ], ], 1 => ['term' => ['state' => 1, ], ], 2 => ['term' => ['cat_state' => 1, ], ],3 => ['terms' => ['cat_id' => [0 => $category, ], ], ], ], ], ], ], ],'sort'=>['start_date'=>['order' => 'desc']] ]];
        $response = $client->search($params);
        $total = $response['hits']['total'];
        if (($from - $total) <= $pagelimit)
        {
            if (($from - $total) <= 0)
            {
                $hits = $pagelimit;
            }
            else
            {
                $hits = $from - $total;
            }
        }
        else
        {
            $hits = 0;
        }

        $obj = new stdClass();
        $obj->total = $total;

        $hits = count($response['hits']['hits']);
        $result = null;
        $i = 0;

        while ($i < $hits)
        {
            $result[$i] = $response['hits']['hits'][$i]['_source'];
            $i++;
        }
        $resultsarray = [];
        foreach ($result as $key => $value)
        {
            $obj->results = array(
                'name' => $value['title'],
                'description' => $value['description'],
                'url' => $value['path'],
                'repository' => $value['path'],
                'id' => $value['id'],
				'thumb' => 'https://kassiounpaper.com/'. str_replace('_S.jpg','_XS.jpg',$value['image']),
                'downloads' => 1022,
                'favers' => 1111
            );
            $arritems[] = $obj->results;
            $arritems = $arritems++;
        }

        $obj->results = $arritems;
        $obj->next = "";

        $response = json_decode(json_encode($obj, JSON_UNESCAPED_UNICODE) , JSON_UNESCAPED_UNICODE);
        $response = json_encode($response, JSON_UNESCAPED_UNICODE);
        return $response;
    }
	
}
?>
