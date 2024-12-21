<?php

namespace App\Service;

use App\Entity\Document;
use App\Entity\Node;
use App\Repository\DocumentRepository;
use App\Repository\NodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class XMLManagerService
{
    private Document $document;
    private ParameterBagInterface $parameterBag;
    private EntityManagerInterface $entityManager;

    /**
     * @param ParameterBagInterface $parameterBag
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
    }

    /**
     * Initialize the class with datas needed
     * @param Document $document
     * @return void
     */
    public function initialize(Document $document): void
    {
        $this->document = $document;
    }

    /**
     * Use for the first read of the XMLDocument
     * @return array
     */
    public function firstReadXMLDocument(): array
    {
        $nodes = [];

        $xmlReader = \XMLReader::open($this->parameterBag->get('document_directory') . '/' . $this->document->getName());
        while ($xmlReader->read()) {
            if ($xmlReader->nodeType === \XMLReader::ELEMENT) {
                $nodes[] = [
                    'name' => $xmlReader->name,
                    'type' => $xmlReader->nodeType,
                    'depth' => $xmlReader->depth,
                    'value' => null,
                ];
            }

            if ($xmlReader->nodeType === \XMLReader::TEXT) {
                $lastElement = &$nodes[count($nodes) - 1];
                $lastElement['value'] = $xmlReader->value;
            }
        }

        foreach ($nodes as $node) {
            $nodeEntity = new Node();
            $nodeEntity->setName($node['name']);
            $nodeEntity->setDocument($this->document);
            $nodeEntity->setType($node['type']);
            $nodeEntity->setValue($node['value']);

            $this->entityManager->persist($nodeEntity);
            $this->entityManager->flush();
        }

        return $nodes;
    }
}