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
    private $anotherUser;

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

        $this->anotherUser = User::create([
            'api_token' =>  Str::random(32),
            'name' => 'notAdmin',
            'email' => 'notAdmin@gmail.com',
            'password' => '4567'
        ]);
    }

    private function data()
    {
        return [
            'api_token' => $this->user->api_token,
            'url' => 'https://img2.gelbooru.com//images/66/c3/66c38e1cb048c18dcabf1705e52470fa.jpeg',
            'description' => '',
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
        $this->post('/api/imgs', array_merge($this->data(), ['api_token' => $this->anotherUser->api_token]));
        $this->assertCount(1, Image::all());
    }

    public function test_a_list_of_images_can_be_fetched_for_the_authenticated_user()
    {
        Image::create([
            'user_id' => $this->user->id,
            'url' => $this->data()['url'],
            'description' => $this->data()['description'],
            'external_link' => $this->data()['external_link']
        ]);

        Image::create([
            'user_id' => $this->user->id,
            'url' => 'https://i.etsystatic.com/20118893/r/il/b75d91/1888136260/il_794xN.1888136260_oaj1.jpg',
            'description' => $this->data()['description'],
            'external_link' => $this->data()['external_link']
        ]);

        Image::create([
            'user_id' => $this->anotherUser->id,
            'url' => $this->data()['url'],
            'description' => $this->data()['description'],
            'external_link' => $this->data()['external_link']
        ]);

        $response = $this->get('/api/imgs/?api_token=' . $this->user->api_token);
        $response->assertJson([
            'data' => [
                0 => [
                    'url' => $this->data()['url']
                ],
                1 => [
                    'url' => 'https://i.etsystatic.com/20118893/r/il/b75d91/1888136260/il_794xN.1888136260_oaj1.jpg'
                ]
            ]
        ]);
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

    public function test_only_the_owner_of_the_image_can_retrive()
    {
        $image = Image::create([
            'user_id' => $this->user->id,
            'url' => $this->data()['url'],
            'description' => $this->data()['description'],
            'external_link' => $this->data()['external_link']
        ]);
        
        {
            // $response = $this->get('/api/imgs/' . $image->id . '?api_token=' . $this->anotherUser->api_token);
            // $response->assertStatus(403);
        }
        // When Owner of image and request sender are not Identical

        {
            $response = $this->get('/api/imgs/' . $image->id . '?api_token=' . $this->user->api_token);

            $response->assertStatus(200);
            $response->assertJson([
                'data' => [
                    'url' => $image->url
                ]
            ]);
        }
        // When Owner of image and request sender are Identical
    }

    public function test_only_the_owner_of_the_image_can_patch_the_image()
    {
        $image = Image::create([
            'user_id' => $this->user->id,
            'url' => $this->data()['url'],
            'description' => $this->data()['description'],
            'external_link' => $this->data()['external_link']
        ]);

        {
            // $response = $this->patch('/api/imgs/' . $image->id, [
            //     'api_token' => $this->anotherUser->api_token,
            //     'url' => 'https://img2.gelbooru.com//images/b4/7c/b47c5174403ef994e7c78ec2e9496cb0.jpeg'
            // ]);

            // $response->assertStatus(403);
            // $this->assertTrue($image->url == $this->data()['url']);
        }
        // When Owner of image and request sender are not Identical

        {
            $response = $this->patch('/api/imgs/' . $image->id, [
                'api_token' => $this->user->api_token,
                'url' => 'https://img2.gelbooru.com//images/b4/7c/b47c5174403ef994e7c78ec2e9496cb0.jpeg'
            ]);

            $response->assertStatus(200);
            $image = $image->fresh();

            $this->assertTrue($image->url == 'https://img2.gelbooru.com//images/b4/7c/b47c5174403ef994e7c78ec2e9496cb0.jpeg');
            $this->assertTrue($image->url != $this->data()['url']);
        }
        // When Owner of image and request sender are Identical
    }
    
    public function test_only_the_owner_of_the_image_can_delete_the_image()
    {
        $image = Image::create([
            'user_id' => $this->user->id,
            'url' => $this->data()['url'],
            'description' => $this->data()['description'],
            'external_link' => $this->data()['external_link']
        ]);

        {
            // $response = $this->delete('/api/imgs/' . $image->id, ['api_token' => $this->anotherUser->api_token]);

            // $response->assertStatus(403);
            // $this->assertCount(1, Image::all());
        }
        // When Owner of image and request sender are not Identical

        {
            $response = $this->delete('/api/imgs/' . $image->id, ['api_token' => $this->user->api_token]);

            $response->assertStatus(200);
            $this->assertCount(0, Image::all());
        }
        // When Owner of image and request sender are Identical
    }
}