<?php

namespace App\Services;

use App\Entity\QuoteRequest;
use App\Enum\QuoteRequestStatus;
use Doctrine\ORM\EntityManagerInterface;


class QuoteRequestRejectionService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function reject(QuoteRequest $quoteRequest)
    {


        $quoteRequest->setStatus(QuoteRequestStatus::REJECTED);

        $this->entityManager->persist($quoteRequest);

        $this->entityManager->flush();

        return $quoteRequest;
    }
}
