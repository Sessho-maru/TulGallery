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

    public function testAnImgCanBeAdded()
    {   
        $this->withoutExceptionHandling();
        $this->post('/api/imgs', $this->data());
        $this->assertCount(1, Image::all());

    }
}
