<?php

use App\Http\Requests\CampaignPostRequest;
use App\Http\Requests\ContactListPostRequest;
use App\Http\Requests\ContactPostRequest;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\ContactList;
use App\Services\CampaignService;
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
        return ContactList::orderBy('name', 'asc')->get();
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

/**
 * API Campaigns Route Group  /api/campaigns
 */
Route::prefix('campaigns')->group(function () {

    // Get Campaign With Sends
    Route::get('/', function (Request $request) {
        return Campaign::with('sends')->get();
    });

    // Post Campaign and validate using CampaignPostRequest
    Route::post('/', function (CampaignPostRequest $request) {
        // Validated Data
        $validated = $request->validated();

        // Create Campaign
        $campaign = Campaign::create($validated);

        // Return Campaign
        return  $campaign;
    });

    // Get a Campaigns with send status
    Route::get('/{id}', function (Request $request, int $id) {
        // Get the Campaign with Send Status
        return Campaign::with('sends')->where('id', $id)->get();
    });

    // Dispatch a Campaign
    Route::post('/{id}/dispatch', function (Request $request, int $id, CampaignService $campaignService) {

        // Get the Campaign 
        $campaign = Campaign::find($id);

        // If Campaign is null then return not found error message
        if (empty($campaign))
            return new JsonResponse(['result' => false, 'msg' => 'campaign not found'], 404);

        // Dispatch Campaign
        $campaignService->dispatch($campaign);

        // Return Success Response
        return new JsonResponse(['result' => true, 'msg' => 'campaign successfull dispatched']);
    });
});
