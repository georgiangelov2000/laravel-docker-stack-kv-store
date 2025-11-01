<?php
declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class StackApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_pushes_and_pops_in_lifo_order(): void
    {
        $this->postJson('/api/v1/stack/add', ['value' => 'Hello'])->assertCreated();
        $this->postJson('/api/v1/stack/add', ['value' => 'World'])->assertCreated();

        $this->getJson('/api/v1/stack/get')->assertOk()->assertJsonPath('data.value', 'World');

        $this->postJson('/api/v1/stack/add', ['value' => 'Again'])->assertCreated();
        $this->getJson('/api/v1/stack/get')->assertOk()->assertJsonPath('data.value', 'Again');
        $this->getJson('/api/v1/stack/get')->assertOk()->assertJsonPath('data.value', 'Hello');

        $this->getJson('/api/v1/stack/get')->assertNotFound()->assertJsonPath('message', 'Stack is empty');
    }
}
