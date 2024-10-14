<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Seeder;

class IconsTableSeeder extends Seeder
{
    /**
     * Seed the icons table.
     */
    public function run(): void
    {
        Icon::create([
            'name' => 'Other Income',
            'path' => '/assets/icon/box.png',
        ]);

        Icon::create([
            'name' => 'Education',
            'path' => '/assets/icon/expense/education.png',
        ]);

        Icon::create([
            'name' => 'Electricity Bill',
            'path' => '/assets/icon/expense/electricity_bill.png',
        ]);

        Icon::create([
            'name' => 'Fitness',
            'path' => '/assets/icon/expense/fitness.png',
        ]);

        Icon::create([
            'name' => 'Food',
            'path' => '/assets/icon/expense/food.png',
        ]);

        Icon::create([
            'name' => 'Food & Drink',
            'path' => '/assets/icon/expense/food&drink.png',
        ]);
        Icon::create([
            'name' => 'Fun Money',
            'path' => '/assets/icon/expense/fun_money.png',
        ]);

        Icon::create([
            'name' => 'Game',
            'path' => '/assets/icon/expense/game.png',
        ]);

        Icon::create([
            'name' => 'Gas Bill',
            'path' => '/assets/icon/expense/gas_bill.png',
        ]);

        Icon::create([
            'name' => 'Gifts & Donations',
            'path' => '/assets/icon/expense/gifts_donations.png',
        ]);

        Icon::create([
            'name' => 'Home Maintenance',
            'path' => '/assets/icon/expense/home_maintenance.png',
        ]);

        Icon::create([
            'name' => 'Home Services',
            'path' => '/assets/icon/expense/home_services.png',
        ]);
        Icon::create([
            'name' => 'House',
            'path' => '/assets/icon/expense/house.png',
        ]);

        Icon::create([
            'name' => 'Houseware',
            'path' => '/assets/icon/expense/houseware.png',
        ]);

        Icon::create([
            'name' => 'Insurances',
            'path' => '/assets/icon/expense/insurances.png',
        ]);

        Icon::create([
            'name' => 'Invest',
            'path' => '/assets/icon/expense/invest.png',
        ]);

        Icon::create([
            'name' => 'Makeup',
            'path' => '/assets/icon/expense/makeup.png',
        ]);

        Icon::create([
            'name' => 'Medical Check-up',
            'path' => '/assets/icon/expense/medical-checkup.png',
        ]);

        Icon::create([
            'name' => 'Other Expense',
            'path' => '/assets/icon/expense/other_expense.png',
        ]);

        Icon::create([
            'name' => 'Other Utility Bills',
            'path' => '/assets/icon/expense/other_utility_bills.png',
        ]);

        Icon::create([
            'name' => 'Outgoing transfer',
            'path' => '/assets/icon/expense/outgoing_transfer.png',
        ]);

        Icon::create([
            'name' => 'Pay Interest',
            'path' => '/assets/icon/expense/pay_interest.png',
        ]);

        Icon::create([
            'name' => 'Personal Items',
            'path' => '/assets/icon/expense/personal_items.png',
        ]);

        Icon::create([
            'name' => 'Pets',
            'path' => '/assets/icon/expense/pets.png',
        ]);

        Icon::create([
            'name' => 'Phone Bill',
            'path' => '/assets/icon/expense/phone_bill.png',
        ]);

        Icon::create([
            'name' => 'Rentals',
            'path' => '/assets/icon/expense/rentals.png',
        ]);

        Icon::create([
            'name' => 'Shopping',
            'path' => '/assets/icon/expense/shopping.png',
        ]);

        Icon::create([
            'name' => 'Streaming Service',
            'path' => '/assets/icon/expense/streaming_service.png',
        ]);

        Icon::create([
            'name' => 'Television Bill',
            'path' => '/assets/icon/expense/television_bill.png',
        ]);

        Icon::create([
            'name' => 'Transportation',
            'path' => '/assets/icon/expense/transport.png',
        ]);

        Icon::create([
            'name' => 'Vehicle Maintenance',
            'path' => '/assets/icon/expense/vehicle_maintenance.png',
        ]);

        Icon::create([
            'name' => 'Watter Bill',
            'path' => '/assets/icon/expense/watter_bill.png',
        ]);

        Icon::create([
            'name' => 'Collect Interest',
            'path' => '/assets/icon/income/collect_interest.png',
        ]);

        Icon::create([
            'name' => 'Salary',
            'path' => '/assets/icon/income/salary.png',
        ]);

        Icon::create([
            'name' => 'Other Income',
            'path' => '/assets/icon/income/other_income.png',
        ]);

        Icon::create([
            'name' => 'Incoming transfer',
            'path' => '/assets/icon/income/incoming_transfer.png',
        ]);
    }
}
