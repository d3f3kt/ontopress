<?php

namespace OntoPress\Service;

use Saft\Sparql\SparqlUtils;
use Saft\Sparql\Result\EmptyResultImpl;
use Saft\Sparql\Result\SetResultImpl;
use Saft\Sparql\Result\StatementSetResultImpl;
use Saft\Sparql\Result\ValueResultImpl;
use Saft\Addition\ARC2\Store\ARC2;

class SparqlManager
{
    private $store;

    public function __construct(ARC2 $arc2)
    {
        $this->store = $arc2;
    }

    /**
     * @param null $graph
     * @return \Saft\Addition\ARC2\Store\Result
     * @throws \Exception
     */
    public function getAllTriples($graph = null)
    {
        $query = 'SELECT * WHERE { ?s ?p ?o. }';
        if ($graph != null) {
            $query = 'SELECT * FROM <' . $graph . '> WHERE { ?s ?p ?o. }';
        }

        return $this->store->query($query);
    }

    /**
     * @param null $graph
     * @return array
     */
    public function getAllManageRows($graph = null)
    {
        $all = $this->getAllTriples($graph);
        $answer = array();
        foreach ($all as $triples) {
            $subject = $this->getStringFromUri($triples['s']);
            if (!array_key_exists($subject, $answer)) {
                $answer[$subject] = array('title' => $this->getStringFromUri($triples['s']));
            }
            if ($triples['p'] == 'OntoPress:author') {
                $answer[$subject]['author'] = $triples['o']->getValue();
            } elseif ($triples['p'] == 'OntoPress:date') {
                $answer[$subject]['date'] = $triples['o']->getValue();
            }
        }
        return $answer;
    }

    /**
     * @param $subject
     * @param null $graph
     * @return \Saft\Addition\ARC2\Store\Result
     * @throws \Exception
     */
    public function getResourceTriples($subject, $graph = null)
    {
        $query = 'SELECT * WHERE { ' . $subject . ' ?p ?o. }';
        if ($graph != null) {
            $query = 'SELECT * FROM <' . $graph . '> WHERE { ' . $subject . ' ?p ?o. }';
        }

        return $this->store->query($query);
    }

    /**
     * @param $uri
     * @return mixed
     */
    private function getStringFromUri($uri)
    {
        $name = explode(':', $uri)[1];
        return preg_replace('/(?<!\ )[A-Z]/', ' $0', $name);
        /*
        $check = array();
        $regEx = '/^([a-zA-Z][a-zA-Z0-9+.-]+):([^\x00-\x0f\x20\x7f<>{}|\[\]`"^\\\\])+$/';
        preg_match($regEx, $uri, $check);
        switch ($check[1]) {
            case 'name':
                $name = explode(':' , $uri)[1];
                return preg_replace('/(?<!\ )[A-Z]/', ' $0', $name);
            default:
                return $uri;
        }
        */
    }
}
