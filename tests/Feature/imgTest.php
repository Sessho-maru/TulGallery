<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\User;
use App\Image;

class imgTest extends TestCase
{
    use RefreshDatabase;
    private $user;

    // This Function is Executed Right Before Every Single Test
    protected function setUp() : void
    {
        parent::setUp();
        $this->user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '1234'
        ]);
    }

    private function data()
    {
        return [
            'url' => 'https://img2.gelbooru.com//images/66/c3/66c38e1cb048c18dcabf1705e52470fa.jpeg'
        ];
    }

    public function test_an_image_can_be_added()
    {   
        $this->post('/api/imgs', $this->data());
        $this->assertCount(1, Image::all());
    }

    public function test_fields_are_required()
    {
        collect(['url'])
            ->each(function ($field) 
            {
                $response = $this->post('/api/imgs', array_merge($this->data(), [$field => '']));

                $response->assertSessionHasErrors($field);
                $this->assertCount(0, Image::all());
            });
    }

    public function test_url_field_must_be_an_url()
    {
        $resopnse = $this->post('/api/imgs', $this->data());
        $resopnse->assertSessionHasNoErrors('url');
    }

    // public function test_an_unauthenticated_user_should_be_redirected_to_login()
    // {
    //     $response = $this->post('/api/imgs', $this->data());
    //     $response->assertRedirect('/login');
    //     $this->assertCount(0, Image::all());
    // }
}
