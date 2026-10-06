<?php
namespace Database\Seeders;
use App\Models\{Expense, Group, Settlement, Token, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->delete();
        Token::query()->delete();
        Group::query()->delete();
        Expense::query()->delete();
        Settlement::query()->delete();

        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $users[] = User::create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'),
            ]);
        }

        for ($g = 1; $g <= 3; $g++) {
            $ids = array_map(fn ($u) => (string) $u->_id, array_slice($users, 0, 5));
            $group = Group::create([
                'name' => ['Dubai Trip', 'Office Lunch', 'Apartment Expenses'][$g - 1],
                'description' => 'Seed group ' . $g,
                'owner_id' => $ids[0],
                'member_ids' => $ids,
            ]);

            for ($i = 1; $i <= 4; $i++) {
                $paid = $ids[$i % 5];
                $participants = array_map(fn ($id) => ['user_id' => $id, 'amount' => 200], $ids);
                Expense::create([
                    'group_id' => (string) $group->_id,
                    'description' => 'Seed expense ' . $i,
                    'amount' => 1000,
                    'paid_by' => $paid,
                    'split_type' => 'equal',
                    'participants' => $participants,
                ]);
            }

            Settlement::create([
                'group_id' => (string) $group->_id,
                'paid_by' => $ids[1],
                'paid_to' => $ids[0],
                'amount' => 50,
                'note' => 'Seed settlement',
            ]);
        }

        User::raw(function ($collection) {
            $collection->createIndex(['email' => 1], ['unique' => true]);
        });

        Token::raw(function ($collection) {
            $collection->createIndex(['api_token' => 1], ['unique' => true]);
        });

        Group::raw(function ($collection) {
            $collection->createIndex(['owner_id' => 1]);
            $collection->createIndex(['member_ids' => 1]);
        });

        Expense::raw(function ($collection) {
            $collection->createIndex(['group_id' => 1]);
            $collection->createIndex(['paid_by' => 1]);
        });

        Settlement::raw(function ($collection) {
            $collection->createIndex(['group_id' => 1]);
        });
    }
}
