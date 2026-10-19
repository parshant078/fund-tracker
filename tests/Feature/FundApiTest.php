<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundApiTest extends TestCase
{
    use RefreshDatabase;

    private string $token = 'test-api-token-12345';

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create(['api_token' => $this->token]);
    }

    /** @return array<string, string> */
    private function headers(): array
    {
        return ['Authorization' => 'Bearer '.$this->token, 'Accept' => 'application/json'];
    }

    public function test_unauthenticated_api_requests_are_rejected(): void
    {
        $this->getJson('/api/summary')->assertStatus(401);
        $this->getJson('/api/funds')->assertStatus(401);
    }

    public function test_summary_returns_totals(): void
    {
        $this->getJson('/api/summary', $this->headers())->assertOk()
            ->assertJsonStructure(['total_funds', 'total_expenses', 'balance', 'by_category', 'works', 'trend']);
    }

    public function test_fund_crud_over_api(): void
    {
        $create = $this->postJson('/api/funds', [
            'donor_name' => 'Test Donor',
            'amount' => 5000,
            'date' => '2026-10-19',
        ], $this->headers())->assertCreated();

        $id = $create->json('data.id');
        $this->assertNotNull($id);

        $this->getJson("/api/funds/{$id}", $this->headers())->assertOk()
            ->assertJsonPath('data.donor_name', 'Test Donor');

        $this->putJson("/api/funds/{$id}", [
            'donor_name' => 'Test Donor',
            'amount' => 6000,
            'date' => '2026-10-19',
        ], $this->headers())->assertOk()
            ->assertJsonPath('data.amount', '6000.00');

        $this->deleteJson("/api/funds/{$id}", [], $this->headers())->assertOk();
        $this->getJson("/api/funds/{$id}", $this->headers())->assertNotFound();
    }

    public function test_fund_validation_errors_return_422(): void
    {
        $this->postJson('/api/funds', ['amount' => -5], $this->headers())
            ->assertStatus(422)
            ->assertJsonValidationErrors(['donor_name', 'amount', 'date']);
    }

    public function test_expense_needs_valid_category(): void
    {
        $this->postJson('/api/expenses', [
            'category_id' => 9999,
            'amount' => 100,
            'date' => '2026-10-19',
        ], $this->headers())->assertStatus(422)
            ->assertJsonValidationErrors(['category_id']);
    }
}
