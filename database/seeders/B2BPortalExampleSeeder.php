<?php

namespace Database\Seeders;

use App\Models\CorporateAccount;
use App\Models\Doctor;
use App\Models\HealthCamp;
use App\Models\Lab;
use App\Models\TestOrder;
use Illuminate\Database\Seeder;

/**
 * Example B2B data: corporate accounts, health camps, demo doctor, and linked test orders.
 *
 * Run: php artisan db:seed --class=B2BPortalExampleSeeder
 * (LabSeeder + LabDataSeeder should run first so patients/tests/orders exist.)
 */
class B2BPortalExampleSeeder extends Seeder
{
    /** Demo password for all portal logins created here (change in production). */
    public const DEMO_PASSWORD = 'password';

    public function run(): void
    {
        $lab = Lab::first();
        if (! $lab) {
            $this->command->warn('B2BPortalExampleSeeder: No lab found. Run LabSeeder first.');

            return;
        }

        $labId = $lab->id;

        $acme = CorporateAccount::updateOrCreate(
            ['lab_id' => $labId, 'name' => 'Acme Industries (HR)'],
            [
                'contact_name' => 'Priya Sharma',
                'phone' => '9876500100',
                'email' => 'hr.wellness@acme-example.test',
                'password' => self::DEMO_PASSWORD,
                'notes' => 'Example employer account for corporate portal demos.',
                'is_active' => true,
            ]
        );

        $techPark = CorporateAccount::updateOrCreate(
            ['lab_id' => $labId, 'name' => 'TechPark Business Park'],
            [
                'contact_name' => 'Facilities Team',
                'phone' => '9876500101',
                'email' => 'wellness@techpark-example.test',
                'password' => self::DEMO_PASSWORD,
                'notes' => 'Second example tenant for camp-wise reporting.',
                'is_active' => true,
            ]
        );

        $camp1 = HealthCamp::updateOrCreate(
            ['lab_id' => $labId, 'name' => 'Acme Annual Health Camp 2026'],
            [
                'corporate_account_id' => $acme->id,
                'camp_date' => now()->addMonth()->startOfMonth()->addDays(14),
                'venue' => 'Acme Industries — Main auditorium',
                'status' => 'scheduled',
                'notes' => 'Fasting blood tests; slots 9:00–13:00.',
            ]
        );

        $camp2 = HealthCamp::updateOrCreate(
            ['lab_id' => $labId, 'name' => 'TechPark screening week'],
            [
                'corporate_account_id' => $techPark->id,
                'camp_date' => now()->addWeeks(2),
                'venue' => 'Tower B, ground floor clinic room',
                'status' => 'scheduled',
                'notes' => 'Occupational health screening.',
            ]
        );

        $doctor = Doctor::updateOrCreate(
            ['lab_id' => $labId, 'email' => 'portal.demo.doctor@example.test'],
            [
                'name' => 'Dr. Demo Referring Physician',
                'specialization' => 'General Medicine',
                'qualification' => 'MBBS',
                'phone' => '9876500200',
                'password' => self::DEMO_PASSWORD,
                'clinic_name' => 'Demo Clinic',
                'is_active' => true,
                'commission_type' => 'percentage',
                'commission_rate' => 10,
            ]
        );

        if (empty($doctor->referral_code)) {
            $doctor->referral_code = Doctor::generateReferralCode($labId);
            $doctor->save();
        }

        $orders = TestOrder::where('lab_id', $labId)
            ->whereNull('health_camp_id')
            ->orderBy('id')
            ->take(8)
            ->get();

        foreach ($orders->take(3) as $order) {
            $order->update([
                'health_camp_id' => $camp1->id,
                'doctor_id' => $doctor->id,
            ]);
        }
        foreach ($orders->skip(3)->take(2) as $order) {
            $order->update([
                'health_camp_id' => $camp2->id,
                'doctor_id' => $doctor->id,
            ]);
        }

        $this->command->newLine();
        $this->command->info('=== B2B / Doctor portal examples (lab: '.$lab->name.') ===');
        $this->command->table(
            ['Portal', 'Path', 'Phone login', 'Password'],
            [
                ['Corporate (Acme)', '/corporate-portal/login', $acme->phone, self::DEMO_PASSWORD],
                ['Corporate (TechPark)', '/corporate-portal/login', $techPark->phone, self::DEMO_PASSWORD],
                ['Doctor', '/doctor-portal/login', $doctor->phone, self::DEMO_PASSWORD],
            ]
        );
        $this->command->info('Referral booking URL: '.$doctor->referralBookingUrl());
        $this->command->info('Health camps: "'.$camp1->name.'" (#'.$camp1->id.'), "'.$camp2->name.'" (#'.$camp2->id.')');
        $linked = min(5, $orders->count());
        $this->command->info("Linked {$linked} existing test order(s) to camps + demo doctor (if orders were available).");
    }
}
