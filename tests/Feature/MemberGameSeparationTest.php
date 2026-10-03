<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberGameSeparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_small_game_member_list_hides_large_game_members(): void
    {
        Member::create([
            'name' => 'Small Game Member',
            'phone' => '255700000001',
            'amount' => 5000,
            'type' => 'daily',
            'number_type' => 10,
            'pay_type' => 'mchango_mdogo',
        ]);

        Member::create([
            'name' => 'Large Game Member',
            'phone' => '255700000002',
            'amount' => 10000,
            'type' => 'daily',
            'number_type' => 10,
            'pay_type' => 'mchango_mkubwa',
        ]);

        $this->actingAs(User::factory()->create())
            ->withSession(['pay_type' => 'mchango_mdogo'])
            ->get(route('members.index'))
            ->assertOk()
            ->assertSee('Small Game Member')
            ->assertDontSee('Large Game Member');
    }
}