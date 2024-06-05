<?php

namespace App\Service;

use App\Repository\DocumentRepository;

class XMLManagerService
{
    private DocumentRepository $documentRepository;
    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }
}