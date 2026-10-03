<?php

namespace Tests\Feature;

use App\Models\Collection;
use App\Models\GameCycle;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameCycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_starts_cycle_without_moving_old_debt_or_changing_other_game(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $smallMember = $this->createMember('mchango_mdogo', 'Small Member', 700000001);
        $anotherSmallMember = $this->createMember('mchango_mdogo', 'Another Small Member', 700000003);
        $largeMember = $this->createMember('mchango_mkubwa', 'Large Member', 700000002);

        $oldSmallCycle = GameCycle::create([
            'pay_type' => 'mchango_mdogo',
            'start_date' => today()->subDays(30),
            'end_date' => today()->subDay(),
            'status' => 'active',
            'created_by' => $admin->id,
        ]);
        $largeCycle = GameCycle::create([
            'pay_type' => 'mchango_mkubwa',
            'start_date' => today()->subDays(10),
            'end_date' => today()->addDays(10),
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        $oldCollection = $this->createCollection($smallMember, $oldSmallCycle, 15000, 5000, 10000);
        $this->createCollection($anotherSmallMember, $oldSmallCycle, 15000, 5000, 10000);
        $largeCollection = $this->createCollection($largeMember, $largeCycle, 100000, 10000, 90000);

        $this->actingAs($admin)
            ->withSession(['pay_type' => 'mchango_mdogo'])
            ->post(route('game-cycles.store'), [
                'start_date' => today()->addDays(21)->toDateString(),
                'end_date' => today()->addDays(51)->toDateString(),
                'contribution_amount' => 7500,
                'confirm' => '1',
            ])
            ->assertRedirect(route('members.index.mdogo'));

        $oldCollection->refresh();
        $largeCollection->refresh();
        $oldSmallCycle->refresh();
        $largeCycle->refresh();

        $this->assertSame(10000.0, (float) $oldCollection->balance);
        $this->assertSame(90000.0, (float) $largeCollection->balance);
        $this->assertSame('closed', $oldSmallCycle->status);
        $this->assertSame('active', $largeCycle->status);
        $this->assertSame(2, $smallMember->collections()->count());
        $this->assertSame(2, $anotherSmallMember->collections()->count());
        $this->assertSame(1, $largeMember->collections()->count());
        $this->assertSame(7500.0, (float) $smallMember->currentCollection->installment_amount);
        $this->assertSame(232500.0, (float) $smallMember->currentCollection->total_amount);
        $this->assertSame(7500.0, (float) $smallMember->fresh()->amount);
        $this->assertSame(7500.0, (float) $anotherSmallMember->currentCollection->installment_amount);
        $this->assertSame(232500.0, (float) $anotherSmallMember->currentCollection->total_amount);
        $this->assertSame(31, (int) $smallMember->fresh()->number_type);
        $this->assertSame(today()->addDays(21)->toDateString(), $smallMember->fresh()->start_date->toDateString());
    }

    public function test_cycle_dates_overlapping_an_existing_cycle_are_rejected(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $this->createMember('mchango_mdogo', 'Small Member', 700000004);

        GameCycle::create([
            'pay_type' => 'mchango_mdogo',
            'start_date' => today()->subDays(10),
            'end_date' => today()->addDays(10),
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->withSession(['pay_type' => 'mchango_mdogo'])
            ->post(route('game-cycles.store'), [
                'start_date' => today()->subDays(2)->toDateString(),
                'end_date' => today()->addDays(15)->toDateString(),
                'contribution_amount' => 5000,
                'confirm' => '1',
            ])
            ->assertSessionHasErrors('start_date');

        $this->assertSame(1, GameCycle::where('pay_type', 'mchango_mdogo')->count());
    }

    public function test_admin_cannot_start_cycle_while_existing_cycle_is_still_active_even_for_later_dates(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $this->createMember('mchango_mdogo', 'Small Member', 700000005);

        GameCycle::create([
            'pay_type' => 'mchango_mdogo',
            'start_date' => today()->subDays(10),
            'end_date' => today()->addDays(10),
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->withSession(['pay_type' => 'mchango_mdogo'])
            ->post(route('game-cycles.store'), [
                'start_date' => today()->addDays(11)->toDateString(),
                'end_date' => today()->addDays(41)->toDateString(),
                'contribution_amount' => 5000,
                'confirm' => '1',
            ])
            ->assertSessionHasErrors('start_date');

        $this->assertSame(1, GameCycle::where('pay_type', 'mchango_mdogo')->count());
    }

    public function test_member_edit_keeps_active_cycle_day_count_and_dates(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $member = $this->createMember('mchango_mdogo', 'Small Member', 700000006);
        $cycle = GameCycle::create([
            'pay_type' => 'mchango_mdogo',
            'start_date' => today()->subDays(5),
            'end_date' => today()->addDays(5),
            'contribution_amount' => 7000,
            'status' => 'active',
            'created_by' => $admin->id,
        ]);
        $collection = $this->createCollection($member, $cycle, 77000, 0, 77000);

        $this->actingAs($admin)
            ->withSession(['pay_type' => 'mchango_mdogo'])
            ->put(route('members.update', $member->id), [
                'name' => 'Updated Small Member',
                'phone' => '700000006',
                'address' => null,
                'business_address' => null,
                'pay_type' => 'mchango_mdogo',
                'type' => 'daily',
                'number_type' => 500,
            ])
            ->assertRedirect(route('members.index.mdogo'));

        $member->refresh();
        $collection->refresh();
        $this->assertSame(11, (int) $member->number_type);
        $this->assertSame(today()->subDays(5)->toDateString(), $member->start_date->toDateString());
        $this->assertSame(today()->addDays(5)->toDateString(), $member->end_date->toDateString());
        $this->assertSame(7000.0, (float) $member->amount);
        $this->assertSame(77000.0, (float) $collection->total_amount);
    }

    public function test_non_admin_cannot_start_a_new_cycle(): void
    {
        $cashier = User::factory()->create();

        $this->actingAs($cashier)
            ->withSession(['pay_type' => 'mchango_mdogo'])
            ->get(route('game-cycles.create'))
            ->assertRedirect(route('dashboard'));
    }

    private function createMember(string $payType, string $name, int $phone): Member
    {
        return Member::create([
            'name' => $name,
            'phone' => $phone,
            'amount' => $payType === 'mchango_mdogo' ? 5000 : 10000,
            'type' => 'daily',
            'number_type' => 10,
            'penalty_per_day' => 0,
            'start_date' => today()->subDays(30)->toDateString(),
            'end_date' => today()->subDay()->toDateString(),
            'pay_type' => $payType,
        ]);
    }

    private function createCollection(Member $member, GameCycle $cycle, float $total, float $paid, float $balance): Collection
    {
        return Collection::create([
            'member_id' => $member->id,
            'game_cycle_id' => $cycle->id,
            'installment_amount' => $member->amount,
            'total_amount' => $total,
            'amount_paid' => $paid,
            'balance' => $balance,
            'total_penalty' => 0,
            'penalty_paid' => 0,
            'penalty_balance' => 0,
            'last_payment_date' => today()->subDay()->toDateString(),
            'status' => 'partial',
        ]);
    }
}