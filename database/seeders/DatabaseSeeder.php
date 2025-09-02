<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\GuaranteeMainCase;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $guaranteeMainCases = [
            'Anesthesiologist',
            'Cardiology',
            'Cardiothoracic',
            'Dental',
            'Dermatology',
            'Dermatology (Plastic Surgery)',
            'ENT',
            'Emergency',
            'Endocrinology',
            'Gastroenterology',
            'General Surgery',
            'Hematology',
            'Hemodialysis',
            'Infectious',
            'Internal Medicine',
            'Joints',
            'Maxillofacial',
            'Medicine',
            'Nephrology',
            'Neurology',
            'Oncology',
            'Ophthalmology',
            'Orthopedics',
            'Otolaryngology',
            'Pediatrics',
            'Periodontics (Dental)',
            'Physical Medicine & Rehabilitation',
            'Podiatry',
            'Psychiatry',
            'Pulmonology',
            'Regenerative Medicine',
            'Rheumatology',
            'Spine',
            'Stem Cell Transplantation',
            'Urology',
            'Vascular Surgery',
            'Neuro-Surgery',
        ];

        foreach ($guaranteeMainCases as $case) {
            GuaranteeMainCase::create([
                'case' => $case,
            ]);
        }
    }
}
