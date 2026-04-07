<?php

namespace Database\Seeders;

use App\Models\DiagnosticReportTemplate;
use App\Models\Lab;
use App\Models\TemplateSection;
use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportTemplateSeeder extends Seeder
{
    /**
     * Seed realistic diagnostic report templates (with sections) per lab and link tests where names match.
     */
    public function run(): void
    {
        foreach (Lab::query()->orderBy('id')->get() as $lab) {
            $this->seedLab($lab);
        }
    }

    protected function seedLab(Lab $lab): void
    {
        $creatorId = User::query()
            ->where('lab_id', $lab->id)
            ->whereIn('role', ['admin', 'staff'])
            ->value('id');

        DiagnosticReportTemplate::query()
            ->where('lab_id', $lab->id)
            ->update(['is_default' => false]);

        $standard = DiagnosticReportTemplate::updateOrCreate(
            ['lab_id' => $lab->id, 'name' => 'Standard clinical report'],
            [
                'description' => 'Default layout: results table, quality statement, and specimen notes. Use for general chemistry and single-analyte tests.',
                'template_type' => 'simple',
                'layout_type' => 'table',
                'is_default' => true,
                'created_by' => $creatorId,
            ]
        );
        $this->syncSections($standard, [
            ['section_type' => 'parameters', 'title' => 'Test results', 'sort_order' => 0, 'content_json' => null],
            ['section_type' => 'text', 'title' => 'Quality assurance', 'sort_order' => 1, 'content_json' => [
                'text' => 'Results are generated using calibrated instruments and reviewed as per NABL/ISO-aligned lab policies. Critical values are communicated per lab protocol.',
            ]],
            ['section_type' => 'table', 'title' => 'Method summary', 'sort_order' => 2, 'content_json' => [
                'headers' => ['Aspect', 'Detail'],
                'rows' => [
                    ['Reporting', 'SI units unless stated otherwise'],
                    ['Review', 'Verified by authorized signatory'],
                ],
            ]],
            ['section_type' => 'notes', 'title' => 'Specimen & comments', 'sort_order' => 3, 'content_json' => [
                'notes' => 'Specimen type and stability follow standard collection guidelines. Hemolyzed or insufficient samples may affect selected assays.',
            ]],
        ]);

        $cbc = DiagnosticReportTemplate::updateOrCreate(
            ['lab_id' => $lab->id, 'name' => 'Hematology – CBC'],
            [
                'description' => 'Complete blood count with grouped red-cell indices and clinical interpretation hints for hemoglobin.',
                'template_type' => 'panel',
                'layout_type' => 'grouped',
                'is_default' => false,
                'created_by' => $creatorId,
            ]
        );
        $this->syncSections($cbc, [
            ['section_type' => 'parameters', 'title' => 'Complete blood count', 'sort_order' => 0, 'content_json' => null],
            ['section_type' => 'interpretation', 'title' => 'Hemoglobin guide', 'sort_order' => 1, 'content_json' => [
                'rules' => [
                    [
                        'parameter' => 'Hemoglobin',
                        'condition' => 'lt',
                        'value' => '12',
                        'interpretation' => 'Below reference — consider anemia; correlate with clinical findings and iron studies if indicated.',
                    ],
                    [
                        'parameter' => 'Hemoglobin',
                        'condition' => 'between',
                        'value' => ['min' => '12', 'max' => '17'],
                        'interpretation' => 'Within typical adult range (lab-specific refs may differ).',
                    ],
                ],
            ]],
            ['section_type' => 'text', 'title' => 'Notes', 'sort_order' => 2, 'content_json' => [
                'text' => 'CBC reflects point-in-time status; trends are more informative than a single value for chronic conditions.',
            ]],
        ]);

        $lft = DiagnosticReportTemplate::updateOrCreate(
            ['lab_id' => $lab->id, 'name' => 'Liver function (LFT)'],
            [
                'description' => 'Liver panel: enzymes, bilirubin, and proteins in a grouped layout.',
                'template_type' => 'advanced',
                'layout_type' => 'grouped',
                'is_default' => false,
                'created_by' => $creatorId,
            ]
        );
        $this->syncSections($lft, [
            ['section_type' => 'parameters', 'title' => 'Liver panel', 'sort_order' => 0, 'content_json' => null],
            ['section_type' => 'text', 'title' => 'Clinical context', 'sort_order' => 1, 'content_json' => [
                'text' => 'ALT/AST elevation suggests hepatocellular injury; ALP/GGT patterns may indicate cholestasis. Interpret alongside history, imaging, and viral markers.',
            ]],
            ['section_type' => 'notes', 'title' => 'Fasting', 'sort_order' => 2, 'content_json' => [
                'notes' => 'Fasting is not required for standard LFT unless combined with a lipid or glucose panel per ordering instructions.',
            ]],
        ]);

        $lipid = DiagnosticReportTemplate::updateOrCreate(
            ['lab_id' => $lab->id, 'name' => 'Lipid profile'],
            [
                'description' => 'Cholesterol fractions with optional non-HDL context table.',
                'template_type' => 'advanced',
                'layout_type' => 'mixed',
                'is_default' => false,
                'created_by' => $creatorId,
            ]
        );
        $this->syncSections($lipid, [
            ['section_type' => 'parameters', 'title' => 'Lipid results', 'sort_order' => 0, 'content_json' => null],
            ['section_type' => 'table', 'title' => 'Targets (general adult)', 'sort_order' => 1, 'content_json' => [
                'headers' => ['Marker', 'Typical goal'],
                'rows' => [
                    ['LDL-C', 'Per risk category (often under 100–130 mg/dL)'],
                    ['Triglycerides', 'Under 150 mg/dL typical goal'],
                    ['HDL-C', 'Higher is generally favorable'],
                ],
            ]],
            ['section_type' => 'text', 'title' => 'Preparation', 'sort_order' => 2, 'content_json' => [
                'text' => '9–12 h fasting is recommended for meaningful triglyceride and LDL interpretation.',
            ]],
        ]);

        $thyroid = DiagnosticReportTemplate::updateOrCreate(
            ['lab_id' => $lab->id, 'name' => 'Thyroid profile'],
            [
                'description' => 'TSH-led thyroid report with automated interpretation bands.',
                'template_type' => 'advanced',
                'layout_type' => 'mixed',
                'is_default' => false,
                'created_by' => $creatorId,
            ]
        );
        $this->syncSections($thyroid, [
            ['section_type' => 'parameters', 'title' => 'Thyroid hormones', 'sort_order' => 0, 'content_json' => null],
            ['section_type' => 'interpretation', 'title' => 'TSH screening', 'sort_order' => 1, 'content_json' => [
                'rules' => [
                    [
                        'parameter' => 'TSH',
                        'condition' => 'lt',
                        'value' => '0.4',
                        'interpretation' => 'Suppressed TSH — consider hyperthyroidism, pregnancy trimester, or medication effect; correlate with FT4/FT3.',
                    ],
                    [
                        'parameter' => 'TSH',
                        'condition' => 'gt',
                        'value' => '4.5',
                        'interpretation' => 'Elevated TSH — consider primary hypothyroidism; correlate with FT4 and clinical picture.',
                    ],
                    [
                        'parameter' => 'TSH',
                        'condition' => 'between',
                        'value' => ['min' => '0.4', 'max' => '4.5'],
                        'interpretation' => 'TSH within common euthyroid reference (lab-specific ranges may differ).',
                    ],
                ],
            ]],
            ['section_type' => 'notes', 'title' => 'Sampling', 'sort_order' => 2, 'content_json' => [
                'notes' => 'Morning sample preferred; biotin supplements can interfere with some immunoassays — hold per manufacturer guidance if applicable.',
            ]],
        ]);

        $metabolic = DiagnosticReportTemplate::updateOrCreate(
            ['lab_id' => $lab->id, 'name' => 'Metabolic – glucose & HbA1c'],
            [
                'description' => 'Diabetes-relevant markers with interpretation-oriented notes.',
                'template_type' => 'panel',
                'layout_type' => 'table',
                'is_default' => false,
                'created_by' => $creatorId,
            ]
        );
        $this->syncSections($metabolic, [
            ['section_type' => 'parameters', 'title' => 'Results', 'sort_order' => 0, 'content_json' => null],
            ['section_type' => 'interpretation', 'title' => 'Fasting glucose', 'sort_order' => 1, 'content_json' => [
                'rules' => [
                    [
                        'parameter' => 'Glucose',
                        'condition' => 'gt',
                        'value' => '125',
                        'interpretation' => 'Fasting glucose above 125 mg/dL warrants repeat testing; 126 mg/dL or higher on repeat meets ADA diabetes criteria (clinical correlation required).',
                    ],
                ],
            ]],
            ['section_type' => 'text', 'title' => 'HbA1c', 'sort_order' => 2, 'content_json' => [
                'text' => 'HbA1c reflects ~2–3 month mean glycemia; use standardized DCCT-aligned assay interpretation and adjust for conditions affecting RBC turnover.',
            ]],
        ]);

        $this->assignTestsForLab($lab, [
            'CBC (Complete Blood Count)' => $cbc,
            'Liver Function Test (LFT)' => $lft,
            'Lipid Profile' => $lipid,
            'Thyroid Function Test (TFT)' => $thyroid,
            'Blood Sugar (Fasting)' => $metabolic,
            'HbA1c' => $metabolic,
        ], $standard);

        $this->command?->info("Report templates seeded for lab: {$lab->name} (ID: {$lab->id})");
    }

    /**
     * Replace sections when template already existed so re-seeding updates structure.
     */
    protected function syncSections(DiagnosticReportTemplate $template, array $definitions): void
    {
        $template->sections()->delete();

        foreach ($definitions as $row) {
            TemplateSection::create([
                'template_id' => $template->id,
                'section_type' => $row['section_type'],
                'title' => $row['title'] ?? null,
                'content_json' => $row['content_json'] ?? null,
                'sort_order' => $row['sort_order'] ?? 0,
                'is_visible' => true,
            ]);
        }
    }

    /**
     * @param  array<string, DiagnosticReportTemplate>  $map  exact test name => template
     */
    protected function assignTestsForLab(Lab $lab, array $map, DiagnosticReportTemplate $fallback): void
    {
        foreach ($map as $testName => $tpl) {
            Test::query()
                ->where('lab_id', $lab->id)
                ->where('name', $testName)
                ->update(['report_template_id' => $tpl->id]);
        }

        Test::query()
            ->where('lab_id', $lab->id)
            ->whereNull('report_template_id')
            ->update(['report_template_id' => $fallback->id]);
    }
}
