<?php
namespace OntoPress\Service\OntologyParser;

use Saft\Addition\EasyRdf\Data\ParserEasyRdf;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;
use OntoPress\Service\OntologyParser\OntologyNode;
use OntoPress\Service\OntologyParser\Restriction;

class Parser
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
        /*
        foreach ($statementIterator as $key => $statement) {
            echo '#' . $key . ' - ' .
                (string)$statement->getSubject() . ' - ' .
                (string)$statement->getPredicate() . ' - ' .
                (string)$statement->getObject();
            echo "<br />";
        }
           */
        $objectArray = array();
        $restrictionArray = array();
        $mandatoryArray = array();
        foreach ($statementIterator as $key => $statement) {
            $objectArray[$statement->getSubject()->getUri()] = new OntologyNode($statement->getSubject()->getUri(), null, null, null, null);
            if ($statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#label") {
                $objectArray[$statement->getSubject()->getUri()]->setLabel($statement->getObject()->getValue());
                $objectArray[$statement->getSubject()->getUri()]->setType("Text");
            } elseif ($statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#comment") {
                $objectArray[$statement->getSubject()->getUri()]->setLabel($statement->getObject()->getValue());
                $objectArray[$statement->getSubject()->getUri()]->setType("Kommentar");
            } elseif ($statement->getPredicate() == "http://localhost/k00ni/knorke/restrictionOneOf") {
                $objectArray[$statement->getSubject()->getUri()]->setLabel($statement->getObject()->getUri());
                $objectArray[$statement->getSubject()->getUri()]->setType("Checkbox");
                /*
                if ($restrictionArray[(string)$statement->getSubject()] == null) {
                    $restrictionArray[(string)$statement->getSubject()] = array($statement->getObject());
                } else {
                    array_push($restrictionArray[(string)$statement->getSubject()], $statement->getObject());
                }
                */
            } /*elseif ((string)$statement->getPredicate() == "http://localhost/k00ni/knorke/isMandatory") {
                $mandatoryArray[(string)$statement->getSubject()] = $statement->getObject();
            } else {
                $objectArray[(string)$statement->getSubject()]->getType()[(string)$statement->getPredicate()] = (string)$statement->getObject();
            }
            */
        }
        /*
        foreach ($restrictionArray as $subjectR => $object) {
            $restrictionArray[$subjectR] = new Restriction(null, $object);
            foreach ($mandatoryArray as $subjectM => $mandatory) {
                if ($subjectR == $subjectM) {
                    $object = new Restriction($mandatory, $object);
                }
            }
            foreach ($objectArray as $subjectO => $ontoNode) {
                if ($subjectR == $subjectO) {
                    $ontoNode->setRestriction($object);
                }
            }
        }
        */
        foreach ($objectArray as $key => $object) {
            echo $object->getName() . " - " . $object->getLabel() . " - " . $object->getType() . " - " . $object->getRestriction()->getMandatory() . " - " . $object->getRestriction()->getOneOf();
            echo "<br />";
        }
        //print_r($objectArray);
        return $objectArray;

    /*
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
                if ($restrictionArray[(string)$statement->getSubject()] == null) {
                    $restrictionArray[(string)$statement->getSubject()] = array($statement->getObject());
                } else {
                    array_push($restrictionArray[(string)$statement->getSubject()], $statement->getObject());
                }
            } elseif ((string)$statement->getPredicate() == "http://localhost/k00ni/knorke/isMandatory") {
                $mandatoryArray[(string)$statement->getSubject()] = $statement->getObject();
            }
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

        $statementArray = array( "Labels" => $labelArray, "Comments" => $commentArray, "Restrictions" => $restrictionArray, "Mandatory" => $mandatoryArray);

        return $statementArray;
*/

    }
}
