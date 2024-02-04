<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_contact()
    {
        $response = $this->post('/api/contacts', [
            'name' => 'John Doe',
            'contact' => '123456789',
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'John Doe',
            'contact' => '123456789',
            'email' => 'john@example.com',
        ]);
    }

    public function test_show_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->get("/api/contacts/{$contact->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $contact->id,
            'name' => $contact->name,
        ]);
    }

    public function test_update_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->put("/api/contacts/{$contact->id}", [
            'name' => 'Jane Doe',
            'contact' => '987654321',
            'email' => 'jane@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Jane Doe',
            'contact' => '987654321',
            'email' => 'jane@example.com',
        ]);
    }

    public function test_delete_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->delete("/api/contacts/{$contact->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Contact deleted successfully']);
    }

    public function test_list_contacts_with_pagination()
    {
        Contact::factory()->count(20)->create();

        $response = $this->get('/api/contacts');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'contact', 'email'],
            ],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total'],
        ]);
    }

    public function test_create_contact_with_missing_data()
    {
        $response = $this->post('/api/contacts', []);

        $response->assertStatus(422)
            ->assertJson([
                'name' => ['The name field is required.'],
                'contact' => ['The contact field is required.'],
                'email' => ['The email field is required.'],
            ]);
    }

    public function test_create_contact_with_invalid_contact_format()
    {
        $response = $this->post('/api/contacts', [
            'name' => 'John Doe',
            'contact' => '12345678',
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'contact' => ['The contact field must be 9 digits.'],
            ]);
    }

    public function test_update_contact_with_invalid_data()
    {
        $contact = Contact::factory()->create();

        $response = $this->put("/api/contacts/{$contact->id}", [
            'name' => '',
            'contact' => '9876543210',
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'name' => ['The name field is required.'],
                'contact' => ['The contact field must be 9 digits.'],
                'email' => ['The email field must be a valid email address.'],
            ]);
    }

}
