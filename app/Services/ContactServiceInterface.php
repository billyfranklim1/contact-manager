<?php

namespace App\Services;

interface ContactServiceInterface
{
    public function getAllContacts(array $data);
    public function getContactById($id);
    public function createContact(array $data);
    public function updateContact($id, array $data);
    public function deleteContact($id);
}
