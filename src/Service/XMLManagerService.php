<?php

namespace App\Service;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Repository\NodeRepository;

class XMLManagerService
{
    private DocumentRepository $documentRepository;
    private NodeRepository $nodeRepository;
    private Document $document;
    public function __construct(DocumentRepository $documentRepository, NodeRepository $nodeRepository)
    {
        $this->documentRepository = $documentRepository;
        $this->nodeRepository = $nodeRepository;
    }

    public function initialize(Document $document): void
    {
        $this->document = $document;
    }

    public function firstReadXMLDocument(): array
    {
        $nodes = [];

        \XMLReader::open($this->document->getName());
        while (\XMLReader::read()) {
            if (\XMLReader::$nodeType === \XMLReader::ELEMENT) {
                $nodes[] = [
                    'name' => \XMLReader::$name,
                    'type' => \XMLReader::$nodeType,
                    'value' => \XMLReader::$value,
                ];
            }
        }

        return $nodes;
    }

    public function readXMLDocument(): void
    {
        \XMLReader::open($this->document->getName());
        while (\XMLReader::read()) {
            if (\XMLReader::$nodeType === \XMLReader::ELEMENT) {
                $node = new Node();
                $node->setName(\XMLReader::$name);
                $node->setType(\XMLReader::$nodeType);
                $node->setDocument($this->document);
                $this->nodeRepository->save($node);
            }
        }
    }
}