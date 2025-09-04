<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Embassy;
use App\Models\GuaranteeCase;
use App\Models\PatientAdditionalType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    private function randomHexColour()
    {
        return '#' . substr(str_shuffle('0123456789abcdef'), 0, 6);
    }

    public function run(): void
    {
        $embassies = [
            'Embassy of the State of Qatar',
        ];

        foreach ($embassies as $embassy) {
            Embassy::create([
                'name'   => $embassy,
                'colour' => $this->randomHexColour(),
            ]);
        }

        $GuaranteeCases = [
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

        foreach ($GuaranteeCases as $case) {
            GuaranteeCase::create([
                'name'   => $case,
                'colour' => $this->randomHexColour(),
            ]);
        }

        $patientAddtionalType = [
            'Addtional',
            'Equipment',
            'Medication',
        ];

        foreach ($patientAddtionalType as $type) {
            PatientAdditionalType::create([
                'name'   => $type,
                'colour' => $this->randomHexColour(),
            ]);
        }
    }
}
