<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(AccountSubSectionTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(AccSectionsTableSeeder::class);
        $this->call(AccChartsTableSeeder::class);
        $this->call(SurveysTableSeeder::class);
        $this->call(OwnershipsTableSeeder::class);
        $this->call(OwnershipFarmlandsTableSeeder::class);
        $this->call(TransactionTypesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(LoanObjectivesTableSeeder::class);
        $this->call(BranchesTableSeeder::class);
        $this->call(AddressesTableSeeder::class);
        $this->call(NrcPrefixTableSeeder::class);
    }
}
