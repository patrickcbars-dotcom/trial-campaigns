<?php

use App\Http\Requests\ContactPostRequest;
use App\Models\Contact;
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
