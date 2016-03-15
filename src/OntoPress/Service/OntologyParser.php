<?php
namespace OntoPress\Service;

use Saft\Addition\EasyRdf\Data\ParserEasyRdf;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;

class OntologyParser
{

    public function parsing()
    {
        $fileContent1 = file_get_contents(__DIR__.'/../Resources/ontology/knorke.ttl');
        $fileContent2 = file_get_contents(__DIR__.'/../Resources/ontology/place-ontology.ttl');
        $fileContent = $fileContent1 . $fileContent2;
        $parser = new ParserEasyRdf(
            new NodeFactoryImpl(),
            new StatementFactoryImpl(),
            'turtle' // RDF format of the file to parse later on (ttl => turtle)
        );

        $statementIterator = $parser->parseStringToIterator($fileContent);

        //label und restriction in einem array
        foreach ($statementIterator as $key => $predicate) {
            //echo (string)$predicate->getPredicate();
            if ((string)$predicate->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#label") {
                echo (string)$predicate->getObject();
                echo '<br />';
            }
        }
        /*
        foreach ($statementIterator as $key => $statement) {
            echo '#' . $key . ' - ' .
                (string)$statement->getSubject() . ' - ' .
                (string)$statement->getPredicate() . ' - ' .
                (string)$statement->getObject() . PHP_EOL;
            echo '<br />';


            echo "<pre>";
            print_r($statement);
            echo '</pre><br />';

        }*/

    }
}
