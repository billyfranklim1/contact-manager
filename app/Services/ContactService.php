<?php

namespace App\Services;

use App\Repositories\ContactRepositoryInterface;

class ContactService implements ContactServiceInterface
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function getAllContacts(array $data)
    {
        return $this->contactRepository->all($data);
    }

    public function getContactById($id)
    {
        return $this->contactRepository->findById($id);
    }

    public function createContact(array $data)
    {
        return $this->contactRepository->create($data);
    }

    public function updateContact($id, array $data)
    {
        return $this->contactRepository->update($id, $data);
    }

    public function deleteContact($id)
    {
        return $this->contactRepository->delete($id);
    }
}
