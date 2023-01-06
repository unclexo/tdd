<?php

namespace Tests\Feature;

use App\Mail\OrderShipped;
use App\Mail\PostPublished;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MailTest extends TestCase
{
    /** @test */
    public function mailables_are_available()
    {
        $postPublished = new PostPublished();
        $orderShipped = new OrderShipped();

        $this->assertInstanceOf(Mailable::class, $postPublished);
        $this->assertInstanceOf(Mailable::class, $orderShipped);
    }
}
