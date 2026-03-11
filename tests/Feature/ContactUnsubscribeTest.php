<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactUnsubscribeTest extends TestCase
{
    /**
     * unsubscribe feature API test .
     */
    public function test_unsubscribe(): void
    {

        $contact = new Contact();
        $contact->name = 'Patrick';
        $contact->email = 'patrickcbars@gmail.pt';
        $contact->save();

        $response = $this->post('/api/contacts/' . $contact->id . '/unsubscribe');

        $response->assertStatus(200);
    }
}
