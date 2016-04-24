<?php

namespace OntoPress\Service;

use Saft\Addition\ARC2\Store\ARC2;

/**
 * Class SparqlManager.
 *
 * Service-class that manages SPARQL-queries to return requested data
 */
class SparqlManager
{
    /**
     * Store on which the queries are executed.
     *
     * @var ARC2
     */
    private $store;

    public function __construct(ARC2 $arc2)
    {
        $this->store = $arc2;
    }

    /**
     * Method to get all triples from a specified graph or the whole store.
     *
     * @param null|string $graph Graph to get data from, NULL if whole store shall be queried
     *
     * @return Result set of all triples
     *
     * @throws \Exception
     */
    public function getAllTriples($graph = null)
    {
        $query = 'SELECT * WHERE { ?s ?p ?o. }';
        if ($graph != null) {
            $query = 'SELECT * FROM <'.$graph.'> WHERE { ?s ?p ?o. }';
        }

        return $this->store->query($query);
    }

    /**
     * Method to get displayable rows for management-page.
     *
     * @param null|string $graph Graph to get triples from
     *
     * @return array array that contains the rows for display
     */
    public function getAllManageRows($graph = null)
    {
        $answer = array();
        foreach ($this->getAllTriples($graph) as $triple) {
            $subject = $this->getStringFromUri($triple['s']);
            if (!array_key_exists($subject, $answer)) {
                $answer[$subject] = array();
            }

            switch ($triple['p']) {
                case 'OntoPress:name':
                    $answer[$subject]['title'] = $triple['o']->getValue();
                    break;
                case 'OntoPress:author':
                    $answer[$subject]['author'] = $triple['o']->getValue();
                    break;
                case 'OntoPress:date':
                    $answer[$subject]['date'] = $triple['o']->getValue();
                    break;
            }
        }

        return $answer;
    }

    /**
     * Method to get all triples that contain a given subject
     * from a specified graph (or whole store).
     *
     * @param string      $subject graph subject
     * @param null|string $graph   Graph to get triples from
     *
     * @return Result output of the query
     *
     * @throws \Exception
     */
    public function getResourceTriples($subject, $graph = null)
    {
        $query = 'SELECT * WHERE { '.$subject.' ?p ?o. }';
        if ($graph != null) {
            $query = 'SELECT * FROM <'.$graph.'> WHERE { '.$subject.' ?p ?o. }';
        }

        return $this->store->query($query);
    }

    /**
     * Method to create a String from given URI.
     *
     * @param $uri
     *
     * @return String
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
