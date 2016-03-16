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

        //$statementIterator = $parser->parseStringToIterator($fileContent);
        $statementIterator = $parser->parseStreamToIterator($filepath);
        //label und restriction in einem array
        $labelArray = array();
        $commentArray = array();
        foreach ($statementIterator as $key => $statement) {
            if ((string)$statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#label") {
                $labelArray[(string)$statement->getSubject()] = $statement->getObject();
            }
            elseif ((string)$statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#comment") {
                $commentArray[(string)$statement->getSubject()] = $statement->getObject();
            }
            elseif ((string)$statement->getPredicate() == "http://localhost/k00ni/knorke/restrictionOneOf") {
               echo (string)$statement, '<br />';
            }
            /*
            if ((string)$statement->getPredicate() == "http://localhost/k00ni/knorke/isMandatory") {
                if( (string)$statement->getObject() == "true") {

                }
                echo '<br />';
            }*/
        }

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
        $statementArray = array( "Labels" => $labelArray, "Comments" => $commentArray);
        /*
        foreach ($statementIterator as $key => $statement) {
            echo '#' . $key . ' - ' .
                (string)$statement->getSubject() . ' - ' .
                (string)$statement->getPredicate() . ' - ' .
                (string)$statement->getObject() . PHP_EOL;
            echo '<br />';
        }
        */
        return $statementArray;
    }
}
