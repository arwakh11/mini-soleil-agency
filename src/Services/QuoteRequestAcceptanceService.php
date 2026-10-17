<?php

namespace App\Services;

use App\Entity\Client;
use App\Entity\Projects;
use App\Entity\QuoteRequest;
use App\Enum\QuoteRequestStatus;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;


class QuoteRequestAcceptanceService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function accept(QuoteRequest $quoteRequest): Client
    {

        if ($quoteRequest->getStatus() !== QuoteRequestStatus::PENDING) {
            throw new \LogicException('Only pending quote requests can be accepted.');
        }

        $client = new Client();
        $client->setFullName($quoteRequest->getFullName());
        $client->setEmail($quoteRequest->getEmail());
        $client->setPhoneNumber($quoteRequest->getPhoneNumber());
        $client->setInstagramAccount($quoteRequest->getInstagramAccount());
        $client->setBrandName($quoteRequest->getBrandName());
        $client->setContactPreference($quoteRequest->getContactPreference());
        $client->setCreatedAt(new DateTime());

        $project = new Projects();

        $project->setClient($client);
        $project->setShootType($quoteRequest->getShootType());
        $project->setMediaType($quoteRequest->getMediaType());
        $project->setContentQuantity($quoteRequest->getContentQuantity());
        $project->setNumberOfItems($quoteRequest->getNumberOfItems());
        $project->setProductType($quoteRequest->getProductType());
        $project->setCreativeDescription($quoteRequest->getCreativeDescription());
        $project->setStartDate($quoteRequest->getStartDate());
        $project->setStatus("PENDING");
        $project->setCreatedAt(new DateTime());

        $quoteRequest->setStatus(QuoteRequestStatus::ACCEPTED);

        $this->entityManager->persist($client);
        $this->entityManager->persist($project);
        $this->entityManager->persist($quoteRequest);

        $this->entityManager->flush();

        return $client;
    }
}
