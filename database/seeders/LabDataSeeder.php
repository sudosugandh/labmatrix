<?php

namespace Database\Seeders;

use App\Models\Lab;
use App\Models\Patient;
use App\Models\Test;
use App\Models\TestOrder;
use App\Models\Testimonial;
use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first lab (or create one if none exists)
        $lab = Lab::first();
        
        if (!$lab) {
            $this->command->error('No lab found. Please create a lab first.');
            return;
        }

        $labId = $lab->id;
        $this->command->info("Seeding data for lab: {$lab->name} (ID: {$labId})");

        // Create 10 patients
        $patients = Patient::factory(10)->create([
            'lab_id' => $labId,
        ])->collect();
        $this->command->info('Created 10 patients');

        // Create tests (from seed data – names/prices as per lab offerings; descriptions for public website)
        $tests = collect([
            [
                'name' => 'Liver Function Test (LFT)',
                'description' => 'A panel of blood tests that help assess liver health and function. Includes enzymes (ALT, AST, ALP), bilirubin, and proteins. Fasting may be required. Results typically within 24–48 hours.',
                'price' => 800.00,
            ],
            [
                'name' => 'Lipid Profile',
                'description' => 'Measures cholesterol and triglycerides to evaluate cardiovascular risk. Includes total cholesterol, LDL, HDL, and VLDL. Fasting for 9–12 hours is required for accurate results.',
                'price' => 600.00,
            ],
            [
                'name' => 'Thyroid Function Test (TFT)',
                'description' => 'Evaluates thyroid gland function with TSH, T3, and T4 levels. Used to screen for hypothyroidism, hyperthyroidism, and to monitor treatment. No fasting needed.',
                'price' => 700.00,
            ],
            [
                'name' => 'Blood Sugar (Fasting)',
                'description' => 'Measures blood glucose after an overnight fast. Essential for diabetes screening and monitoring. Fasting for 8–10 hours is required before the test.',
                'price' => 200.00,
            ],
            [
                'name' => 'HbA1c',
                'description' => 'Glycated hemoglobin test reflects average blood sugar over the past 2–3 months. No fasting required. Recommended for diabetes diagnosis and long-term control.',
                'price' => 500.00,
            ],
            [
                'name' => 'CBC (Complete Blood Count)',
                'description' => 'Full blood count including hemoglobin, RBC, WBC, platelets, and indices. Used for general health check, anemia, infection, and blood disorders. No special preparation needed.',
                'price' => 500.00,
            ],
        ])->map(function ($testData) use ($labId) {
            return Test::updateOrCreate(
                ['lab_id' => $labId, 'name' => $testData['name']],
                array_merge($testData, ['lab_id' => $labId])
            );
        });
        $this->command->info('Created/updated ' . $tests->count() . ' tests');

        // Create 10 test orders
        $statuses = ['pending', 'sample_collected', 'processing', 'completed'];
        $reportStatuses = ['pending', 'completed'];

        for ($i = 0; $i < 10; $i++) {
            TestOrder::create([
                'lab_id' => $labId,
                'patient_id' => $patients->random()->id,
                'test_id' => $tests->random()->id,
                'status' => $statuses[array_rand($statuses)],
                'report_status' => $reportStatuses[array_rand($reportStatuses)],
                'sample_collected_at' => rand(0, 1) ? now()->subDays(rand(1, 7)) : null,
            ]);
        }
        $this->command->info('Created 10 test orders');

        // Sample testimonials for website homepage
        $testimonialData = [
            ['name' => 'Priya S.', 'quote' => 'Very professional staff and quick reports. The home collection was convenient and the phlebotomist was gentle.', 'rating' => 5, 'is_featured' => true, 'sort_order' => 0],
            ['name' => 'Rajesh K.', 'quote' => 'Accurate results and transparent pricing. I have been coming here for annual checkups for three years.', 'rating' => 5, 'is_featured' => true, 'sort_order' => 1],
            ['name' => 'Anita M.', 'quote' => 'Clean facility and minimal wait time. The doctor explained my reports clearly. Highly recommend.', 'rating' => 4, 'is_featured' => false, 'sort_order' => 2],
        ];
        foreach ($testimonialData as $i => $data) {
            Testimonial::updateOrCreate(
                ['lab_id' => $labId, 'name' => $data['name']],
                array_merge($data, ['lab_id' => $labId])
            );
        }
        $this->command->info('Created/updated ' . count($testimonialData) . ' testimonials');

        // Sample FAQs for website homepage
        $faqData = [
            ['question' => 'Do I need to fast before a blood test?', 'answer' => 'It depends on the test. For fasting blood sugar, lipid profile, and some other tests, you need to fast for 8–12 hours (water is allowed). For routine CBC or thyroid tests, fasting is usually not required. Your doctor or our staff will advise you when you book.', 'sort_order' => 0, 'is_active' => true],
            ['question' => 'How long does it take to get my report?', 'answer' => 'Most routine reports are ready within 24–48 hours. Some specialized tests may take 3–5 days. You can collect the report from our centre or check if we offer online report access.', 'sort_order' => 1, 'is_active' => true],
            ['question' => 'Do you offer home collection?', 'answer' => 'Yes, we offer home sample collection for many tests. You can request a home visit when booking or contact us to schedule. A trained phlebotomist will visit at a time that suits you.', 'sort_order' => 2, 'is_active' => true],
        ];
        foreach ($faqData as $data) {
            Faq::updateOrCreate(
                ['lab_id' => $labId, 'question' => $data['question']],
                array_merge($data, ['lab_id' => $labId])
            );
        }
        $this->command->info('Created/updated ' . count($faqData) . ' FAQs');

        $this->command->info('Seeding completed successfully!');
    }
}
