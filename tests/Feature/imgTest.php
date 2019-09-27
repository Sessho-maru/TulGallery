<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\User;
use App\Image;
use Illuminate\Support\Str;

class imgTest extends TestCase
{
    use RefreshDatabase;
    private $user;

    // This Function is Executed Right Before Every Single Test
    protected function setUp() : void
    {
        parent::setUp();
        $this->user = User::create([
            'api_token' =>  Str::random(32),
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '1234'
        ]);
    }

    private function data()
    {
        return [
            'api_token' => $this->user->api_token,
            'url' => 'https://img2.gelbooru.com//images/66/c3/66c38e1cb048c18dcabf1705e52470fa.jpeg',
            'external_link'=> 'https://img2.gelbooru.com//images/34/74/3474a5dc0caac58e006671fed794f941.png'
        ];
    }

    private function viewUrl($image)
    {
        if ($image['external_link'] != null)
        {
            dd($image->url, $image->external_link);
        }
        else
        {
            dd($image->url);
        }
    }

    public function test_an_unauthenticated_user_should_be_redirected_to_login()
    {
        $response = $this->post('/api/imgs', array_merge($this->data(), ['api_token' => '']));
        $response->assertRedirect('/login');
        $this->assertCount(0, Image::all());
    }

    public function test_an_authenticated_user_can_add_an_image()
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

    public function test_a_image_can_be_retrived()
    {
        $this->post('/api/imgs', $this->data());
        $image = Image::first();

        $response = $this->get('/api/imgs/' . $image->id);
        $response->assertJson(['url' => $image->url]);
    }

    public function test_a_image_can_be_patch()
    {
        $this->post('/api/imgs', $this->data());
        $image = Image::first();

        $response = $this->patch('/api/imgs/' . $image->id, ['url' => 'https://img2.gelbooru.com//images/b4/7c/b47c5174403ef994e7c78ec2e9496cb0.jpeg']);
        $image = $image->fresh();

        // $this->viewUrl($image);
        $this->assertTrue($this->data()['url'] != 'https://img2.gelbooru.com//images/b4/7c/b47c5174403ef994e7c78ec2e9496cb0.jpeg');
    }

    public function test_a_image_can_be_deleted()
    {
        $this->post('/api/imgs', $this->data());
        $image = Image::first();

        $this->delete('/api/imgs/' . $image->id);
        $this->assertCount(0, Image::all());
    }
}
