<?php
namespace App\Repositories;

use App\Models\Contact;

class ContactRepository implements ContactRepositoryInterface
{
    public function all(array $data)
    {
        $query = Contact::query();
        if (isset($data['search'])) {
            $query->where('name', 'like', '%' . $data['search'] . '%')
                ->orWhere('contact', 'like', '%' . $data['search'] . '%')
                ->orWhere('email', 'like', '%' . $data['search'] . '%');
        }
        return $query->orderBy('id', 'desc')->paginate(10);
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
