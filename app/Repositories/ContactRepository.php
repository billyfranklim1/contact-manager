<?php
namespace App\Repositories;

use App\Models\Contact;

class ContactRepository implements ContactRepositoryInterface
{
    public function all()
    {
        return Contact::all();
    }

    public function findById($id)
    {
        return Contact::find($id);
    }

    public function create(array $data)
    {
        return Contact::create($data);
    }

    public function update($id, array $data)
    {
        $contact = $this->findById($id);
        if ($contact) {
            $contact->update($data);
            return $contact;
        }
        return null;
    }

    public function delete($id)
    {
        $contact = $this->findById($id);
        if ($contact) {
            $contact->delete();
            return $contact;
        }
        return null;
    }
}
