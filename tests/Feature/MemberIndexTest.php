<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_action_dropdown_button_does_not_submit_the_bulk_penalty_form(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $member = Member::create([
            'name' => 'Test Member',
            'phone' => '255700000001',
            'address' => 'Kyela',
            'business_address' => 'Sokoni',
            'amount' => 5000,
            'type' => 'daily',
            'number_type' => 30,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(29)->toDateString(),
            'pay_type' => 'mchango_mdogo',
        ]);

        $response = $this->actingAs($user)->get(route('members.index'));

        $response->assertOk();

        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($response->getContent());
        libxml_clear_errors();

        $button = $dom->getElementById("dropdown-{$member->id}-button");

        $this->assertNotNull($button);
        $this->assertSame('button', $button->getAttribute('type'));
    }

    public function test_bulk_penalty_validation_errors_are_stored_in_the_bulk_penalty_bag(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->post(route('members.forgive-penalty.bulk'));

        $response
            ->assertSessionHasErrorsIn('bulkPenalty', ['member_ids']);
    }
}
