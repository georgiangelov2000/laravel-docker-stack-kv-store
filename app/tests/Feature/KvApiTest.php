<?php
declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class KvApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_sets_and_gets_without_ttl(): void
    {
        $this->postJson('/api/v1/kv/add', ['key' => 'name', 'value' => 'John'])
            ->assertCreated()
            ->assertJsonPath('data.key', 'name');

        $this->getJson('/api/v1/kv/get/name')
            ->assertOk()
            ->assertJsonPath('data.value', 'John');

        $this->getJson('/api/v1/kv/get/age')
            ->assertNotFound()
            ->assertJsonPath('message', 'Key not found or expired');
    }

    #[Test]
    public function it_respects_ttl_when_getting_values(): void
    {
        $this->postJson('/api/v1/kv/add', ['key' => 'name', 'value' => 'Larry', 'ttl' => 30])
            ->assertCreated();

        $this->getJson('/api/v1/kv/get/name')
            ->assertOk()
            ->assertJsonPath('data.value', 'Larry');

        $this->travel(31)->seconds();

        $this->getJson('/api/v1/kv/get/name')
            ->assertNotFound()
            ->assertJsonPath('message', 'Key not found or expired');
    }

    #[Test]
    public function it_deletes_a_key(): void
    {
        $this->postJson('/api/v1/kv/add', ['key' => 'token', 'value' => 'abc123'])
            ->assertCreated();

        $this->deleteJson('/api/v1/kv/delete', ['key' => 'token'])
            ->assertOk()
            ->assertJsonPath('data.key', 'token');

        $this->getJson('/api/v1/kv/get/token')
            ->assertNotFound();
    }
}
