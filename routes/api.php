<?php

use App\Http\Requests\ContactListPostRequest;
use App\Http\Requests\ContactPostRequest;
use App\Models\Contact;
use App\Models\ContactList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * API Contacts Route Group  /api/contacts
 */
Route::prefix('contacts')->group(function () {

    // Get Contacts Paginate
    Route::get('/', function (Request $request) {
        return Contact::orderBy('name', 'asc')->paginate();
    });

    // Post Contact and validate using ContactPostReques
    Route::post('/', function (ContactPostRequest $request) {
        // Validated Data
        $validated = $request->validated();

        //Create Contact
        $contact = Contact::create($validated);

        // Refresh Model to Retrive all Atributes, for example the Contact Status is not binded after Create or Save and Enum must be binded
        $contact->refresh();

        //Return Contact
        return  $contact;
    });

    // Unsubscribe Contact 
    Route::post('/{id}/unsubscribe', function (Request $request, int $id) {
        // Get the Contact from DB
        $contact = Contact::find($id);

        // If Empty Returun Not Found Json Response
        if (empty($contact))
            return new JsonResponse(['result' => false, 'msg' => 'contact not found'], 404);

        // Call Unscribe Method
        $contact->unsubscribe();

        // Return Success Response
        return new JsonResponse(['result' => true, 'msg' => 'successfull unsubscribed']);
    });
});


/**
 * API Contact List Route Group  /api/contact-lists
 */
Route::prefix('contact-lists')->group(function () {

    // Get Contact List All
    Route::get('/', function (Request $request) {
        return ContactList::orderBy('name', 'asc')->all();
    });

    // Post Contact list and validate using ContactListPostRequest
    Route::post('/', function (ContactListPostRequest $request) {
        // Validated Data
        $validated = $request->validated();

        // Create Contact List
        $contactList = ContactList::create($validated);

        // Return Contact List
        return  $contactList;
    });

    // Add a Contact to Contact List
    Route::post('/{id}/contacts', function (ContactPostRequest $request, int $id) {

        // Get the Contact List
        $contactList = ContactList::find($id);

        // If contactList is null the return not found error message
        if (empty($contactList))
            return new JsonResponse(['result' => false, 'msg' => 'contact list not found'], 404);

        // Validated Data
        $validated = $request->validated();

        //Create Contact
        $contact = Contact::create($validated);

        // Save the contact on the List
        $contactList->contacts()->save($contact);

        // Return Success Response
        return new JsonResponse(['result' => true, 'msg' => 'contact successfull Created']);
    });
});
