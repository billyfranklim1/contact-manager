<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Repositories\ContactRepositoryInterface;

class ContactController extends Controller
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function index()
    {
        $contacts = $this->contactRepository->all();
        return new ContactCollection($contacts);
    }

    public function show($id)
    {
        $contact = $this->contactRepository->findById($id);
        return new ContactResource($contact);
    }

    public function store(StoreContactRequest $request)
    {
        $contact = $this->contactRepository->create($request->validated());
        return new ContactResource($contact);
    }

    public function update(UpdateContactRequest $request, $id)
    {
        $contact = $this->contactRepository->update($id, $request->validated());
        return new ContactResource($contact);
    }

    public function destroy($id)
    {
        $this->contactRepository->delete($id);
        return response()->json(['message' => 'Contact deleted successfully']);
    }
}
