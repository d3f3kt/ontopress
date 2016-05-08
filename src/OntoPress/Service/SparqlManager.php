<?php

namespace OntoPress\Service;

use Doctrine\ORM\EntityManager;
use Saft\Rdf\StatementImpl;
use Saft\Sparql\Result\Result;
use Saft\Store\Store;

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
     * @var Store
     */
    private $store;
    
    public function __construct(Store $arc2)
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
        return $this->getRows($this->getAllTriples($graph));
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
        $query = 'SELECT ?p ?o WHERE { <'.$subject.'> ?p ?o. }';
        if ($graph != null) {
            $query = 'SELECT ?p ?o FROM <'.$graph.'> WHERE { <'.$subject.'> ?p ?o. }';
        }

        $result = $this->store->query($query);
        $doubles = array();
        if ($result->current() instanceof StatementImpl) {
            foreach ($result as $double) {
                $doubles[$double->getPredicate()->getUri()] = $double->getObject()->getValue();
            }
        } else {
            foreach ($result as $double) {
                $doubles[$double['p']->getUri()] = $double['o']->getValue();
            }
        }
        return $doubles;
    }

    /**
     * Method to get array for inserting resourcedata in existing form
     *
     * @param  string $subject     requested Resource
     * @param  Form   $form        form for inserting data
     * @return array  $valueArray  data array
     */
    public function getValueArray($subject, $form)
    {
        $data = $this->getResourceTriples($subject);
        $valueArray = array();
        $valueArray['OntoPress:name'] = $data['OntoPress:name'];
        foreach ($form->getOntologyFields() as $field) {
            $valueArray[$field->getFormFieldName()] = $data[$field->getName()];
        }
        return $valueArray;
    }

    /**
     * Method to return the ID of the form used to create given resource
     *
     * @param string       $subject  Uri of resource
     * @param null|string  $graph    Graph to search in
     * @return \Saft\Sparql\Result\Result
     */
    public function getFormId($subject, $graph = null)
    {
        $query = 'SELECT ?o WHERE { <'.$subject.'> <OntoPress:formId> ?o. }';
        if ($graph != null) {
            $query = 'SELECT ?o FROM <'.$graph.'> WHERE { <'.$subject.'> <OntoPress:formId> ?o. }';
        }
        $result = $this->store->query($query);
        return $result->current()['o']->getValue();
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
    }

    /**
     * Method to return the number of resources in a graph (or
     *
     * @param null $graph
     * @return mixed
     * @throws \Exception
     */
    public function countResources($graph = null)
    {
        $query = 'SELECT DISTINCT ?s WHERE { ?s <OntoPress:date> ?o }';
        if ($graph != null) {
            $query = 'SELECT DISTINCT ?s FROM <'.$graph.'> WHERE { ?s ?p ?o }';
        }

        $result = $this->store->query($query);
        return sizeof($result);
    }

    /**
     * Method to count the triples of a resource
     *
     * @param $subject
     * @param null $graph
     *
     * @return int
     */
    public function countResourceTriples($subject, $graph = null)
    {
        $query = 'SELECT DISTINCT ?s WHERE { <'.$subject.'> ?p ?o. }';
        if ($graph != null) {
            $query = 'SELECT { <'.$subject.'> ?p ?o. } FROM <'.$graph.'> WHERE { <'.$subject.'> ?p ?o. }';
        }
        $result = $this->store->query($query);
        return sizeof($result);
    }

    /**
     * Method to delete all statements of a given resource
     *
     * @param string       $resourceUri
     * @param null|string  $graph
     */
    public function deleteResource($resourceUri, $graph = null)
    {
        
        $query = 'DELETE { <'.$resourceUri.'> ?p ?o. } WHERE { <'.$resourceUri.'> ?p ?o. }';
        if ($graph != null) {
            $query = 'DELETE FROM <'.$graph.'> { <'.$resourceUri.'> ?p ?o . } WHERE { <'.$resourceUri.'> ?p ?o . }';
        }
        
        $this->store->query($query);
    }

    /**
     * Method to get displayable rows for dashboard latest resources Table.
     *
     * @param null|string $graph Graph to get triples from
     *
     * @return array Array that contains the Title and the Author of all Resources from the given Graph or the whole Store.
     *               All Resources are sorted by the Upload date, beginning with the Latest.
     */
    public function getLatestResources($graph = null)
    {
        //maybe needs to be optimized by LIMIT
        $query = 'SELECT * WHERE { ?s ?p ?o. ?s <OntoPress:date> ?date.} ORDER BY DESC(?date) ';
        if ($graph != null) {
            $query = 'SELECT * FROM <'.$graph.'> WHERE { ?s ?p ?o. ?s <OntoPress:date> ?date.} ORDER BY DESC(?date) ';
        }
        $result = $this->store->query($query);

        return $this->getRows($result);
    }

    /**
     * Return resources that contain given string
     *
     * @param $uriPart
     * @return Store
     */
    public function getResourceRowsLike($uriPart, $graph = null)
    {
        $allRows = $this->getAllManageRows($graph);
        $wantedRows = array();
        foreach ($allRows as $resource => $data) {
            if (strpos(strtolower($data['title']), strtolower($uriPart)) !== false) {
                $wantedRows[$resource] = $data;
            }
        }
        
        return $wantedRows;
    }

    /**
     * Helper-method to generate displayable rows
     *
     * @param  array|Result $triples Resourcedata to generate rows from
     * @return array        $answer  Rows
     */
    private function getRows($triples)
    {
        $answer = array();
        foreach ($triples as $triple) {
            if ($triple instanceof StatementImpl) {
                //this case is only for the Test-Environment,
                //not triggered in real system
                $subject = $this->getStringFromUri($triple->getSubject());
                if (!array_key_exists($subject, $answer)) {
                    $answer[$subject] = array();
                }

                switch ($triple->getPredicate()) {
                    case 'OntoPress:name':
                        $answer[$subject]['title'] = $triple->getObject()->getValue();
                        break;
                    case 'OntoPress:author':
                        $answer[$subject]['author'] = $triple->getObject()->getValue();
                        break;
                    case 'OntoPress:date':
                        $answer[$subject]['date'] = $triple->getObject()->getValue();
                        break;
                    case 'OntoPress:isSuspended':
                        $answer[$subject]['title'] = $answer[$subject]['title'].'--suspended';
                        break;
                }
            } else {
                $subject = $triple['s']->getUri();
                if (!array_key_exists($subject, $answer)) {
                    $answer[$subject] = array('uri' => $subject);
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
                    case 'OntoPress:isSuspended':
                        $answer[$subject]['title'] = $answer[$subject]['title'].'--suspended';
                        break;
                }
            }
        }
        return $answer;
    }

    /**
     * Method for returning an ordered array of resources
     *
     * @param  string $sortBy property to sort
     * @param  string $order  "ASC" or "DESC"
     * @return array          sorted Array
     */
    public function getSortedTable($sortBy, $order)
    {
        $query = 'SELECT * WHERE { ?s ?p ?o ; <OntoPress:'.$sortBy.'> ?sort. } ORDER BY '.$order.'(?sort)';
        $result = $this->store->query($query);

        return $this->getRows($result);
    }

    /**
     * Method to get all triples of a graph
     *
     * @param null $graph
     */
    public function exportRdf($graph = null)
    {
        $triples = $this->getAllTriples($graph);
    }
}
