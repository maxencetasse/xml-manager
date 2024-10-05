<?php

namespace App\Service;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Repository\NodeRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class XMLManagerService
{
    private DocumentRepository $documentRepository;
    private NodeRepository $nodeRepository;
    private Document $document;
    private ParameterBagInterface $parameterBag;

    /**
     * @param DocumentRepository $documentRepository
     * @param NodeRepository $nodeRepository
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(DocumentRepository $documentRepository, NodeRepository $nodeRepository,
                                ParameterBagInterface $parameterBag)
    {
        $this->documentRepository = $documentRepository;
        $this->nodeRepository = $nodeRepository;
        $this->parameterBag = $parameterBag;
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

        $xmlReader = \XMLReader::open($this->parameterBag->get('document_directory').'/'.$this->document->getName());
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

        return $nodes;
    }
}