<?php
namespace OntoPress\Service;

use Saft\Addition\EasyRdf\Data\ParserEasyRdf;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;

class OntologyParser
{
    public function parsing($filepath)
    {
        // $fileContent1 = file_get_contents(__DIR__.'/../Resources/ontology/knorke.ttl');
        // $fileContent = file_get_contents(__DIR__.'/../Resources/ontology/place-ontology.ttl');
        //$fileContent = $fileContent1 . $fileContent2;
        $parser = new ParserEasyRdf(
            new NodeFactoryImpl(),
            new StatementFactoryImpl(),
            'turtle' // RDF format of the file to parse later on (ttl => turtle)
        );
        $statementIterator = $parser->parseStreamToIterator($filepath);

        $labelArray = array();
        $commentArray = array();
        $restrictionArray = array();
        $mandatoryArray = array();
        foreach ($statementIterator as $key => $statement) {
            if ((string)$statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#label") {
                $labelArray[(string)$statement->getSubject()] = $statement->getObject();
            } elseif ((string)$statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#comment") {
                $commentArray[(string)$statement->getSubject()] = $statement->getObject();
            } elseif ((string)$statement->getPredicate() == "http://localhost/k00ni/knorke/restrictionOneOf") {
                if ($restrictionArray[(string)$statement->getSubject()] == NULL) {
                    $restrictionArray[(string)$statement->getSubject()] = array($statement->getObject());
                }
                else {
                    array_push($restrictionArray[(string)$statement->getSubject()], $statement->getObject());
                }
            } elseif ((string)$statement->getPredicate() == "http://localhost/k00ni/knorke/isMandatory") {
                $mandatoryArray[(string)$statement->getSubject()] = $statement->getObject();
            }
        }
        /*
        echo "Labels", "<br />";
        foreach ($labelArray as $key => $object) {
            echo $key . ' - ' .
                (string)$object;
            echo '<br />';
        }
        echo "Comments" , "<br />";
        foreach ($commentArray as $key => $object) {
            echo $key . ' - ' .
                (string)$object;
            echo '<br />';
        }
        echo "Restrictions" , "<br />";
        foreach ($restrictionArray as $key1 => $object) {
            foreach ($object as $key2 => $restriction) {
                echo $key1 . ' - ' .
                    (string)$restriction;
                echo '<br />';
            }
        }
        echo "Mandatory" , "<br />";
        foreach ($mandatoryArray as $key => $object) {
                echo $key . ' - ' .
                    (string)$object;
                echo '<br />';
        }
        */
        $statementArray = array( "Labels" => $labelArray, "Comments" => $commentArray, "Restrictions" => $restrictionArray, "Mandatory" => $mandatoryArray);

        return $statementArray;
    }
}
