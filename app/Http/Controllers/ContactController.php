<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Services\ContactServiceInterface;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactServiceInterface $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        $contacts = $this->contactService->getAllContacts();
        return new ContactCollection($contacts);
    }

    public function show($id)
    {
        $contact = $this->contactService->getContactById($id);
        return new ContactResource($contact);
    }

    public function store(StoreContactRequest $request)
    {
        $contact = $this->contactService->createContact($request->validated());
        return new ContactResource($contact);
    }

    public function update(UpdateContactRequest $request, $id)
    {
        $contact = $this->contactService->updateContact($id, $request->validated());
        return new ContactResource($contact);
    }

    public function destroy($id)
    {
        $this->contactService->deleteContact($id);
        return response()->json(['message' => 'Contact deleted successfully']);
    }
}
