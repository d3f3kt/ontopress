<?php
namespace OntoPress\Service;

use Saft\Addition\EasyRdf\Data\ParserEasyRdf;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;

class OntologyParser
{

    public function parsing()
    {
        $fileContent = file_get_contents(__DIR__.'../Resources/ontology/place-ontology.ttl');

        $parser = new ParserEasyRdf(
            new NodeFactoryImpl(),
            new StatementFactoryImpl(),
            'turtle' // RDF format of the file to parse later on (ttl => turtle)
        );

        $statementIterator = $parser->parseStringToIterator($fileContent);

        foreach ($statementIterator as $key => $statement){
            echo '#' . $key . ' - ' .
                (string)$statement->getSubject() . ' - ' .
                (string)$statement->getPredicate() . ' - ' .
                (string)$statement->getObject() . PHP_EOL;
        }
    }
}
