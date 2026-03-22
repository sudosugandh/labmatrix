<?php

namespace App\Http\Controllers;

use App\Models\DiagnosticReportTemplate;
use App\Models\TemplateSection;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiagnosticReportTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $templates = DiagnosticReportTemplate::forCurrentLab()
            ->with(['sections', 'createdBy'])
            ->orderBy('name')
            ->get();

        return view('report-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('report-templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template_type' => 'required|in:simple,panel,advanced',
            'layout_type' => 'required|in:table,grouped,mixed',
            'is_default' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // If setting as default, unset other defaults
            if ($request->has('is_default') && $request->is_default) {
                DiagnosticReportTemplate::forCurrentLab()
                    ->update(['is_default' => false]);
            }

            $template = DiagnosticReportTemplate::create([
                'lab_id' => currentLabId(),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'template_type' => $validated['template_type'],
                'layout_type' => $validated['layout_type'],
                'is_default' => $request->has('is_default') && $request->is_default,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('report-templates.show', $template)
                ->with('success', 'Template created successfully. Now add sections to your template.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create template: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DiagnosticReportTemplate $reportTemplate): View
    {
        // Ensure template belongs to current lab
        if ($reportTemplate->lab_id !== currentLabId()) {
            abort(404);
        }

        $reportTemplate->load(['sections' => function($query) {
            $query->orderBy('sort_order');
        }, 'createdBy', 'tests']);

        return view('report-templates.show', compact('reportTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DiagnosticReportTemplate $reportTemplate): View
    {
        // Ensure template belongs to current lab
        if ($reportTemplate->lab_id !== currentLabId()) {
            abort(404);
        }

        return view('report-templates.edit', compact('reportTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DiagnosticReportTemplate $reportTemplate): RedirectResponse
    {
        // Ensure template belongs to current lab
        if ($reportTemplate->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template_type' => 'required|in:simple,panel,advanced',
            'layout_type' => 'required|in:table,grouped,mixed',
            'is_default' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // If setting as default, unset other defaults
            if ($request->has('is_default') && $request->is_default && !$reportTemplate->is_default) {
                DiagnosticReportTemplate::forCurrentLab()
                    ->where('id', '!=', $reportTemplate->id)
                    ->update(['is_default' => false]);
            }

            $reportTemplate->update($validated);

            DB::commit();

            return redirect()->route('report-templates.show', $reportTemplate)
                ->with('success', 'Template updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update template: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DiagnosticReportTemplate $reportTemplate): RedirectResponse
    {
        // Ensure template belongs to current lab
        if ($reportTemplate->lab_id !== currentLabId()) {
            abort(404);
        }

        // Check if template is being used by any tests
        if ($reportTemplate->tests()->count() > 0) {
            return back()->with('error', 'Cannot delete template. It is assigned to ' . $reportTemplate->tests()->count() . ' test(s).');
        }

        $reportTemplate->delete();

        return redirect()->route('report-templates.index')
            ->with('success', 'Template deleted successfully.');
    }

    /**
     * Store a new section for the template
     */
    public function storeSection(Request $request, DiagnosticReportTemplate $reportTemplate): RedirectResponse
    {
        // Ensure template belongs to current lab
        if ($reportTemplate->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'section_type' => 'required|in:parameters,text,table,calculated,notes,interpretation',
            'title' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            // Section-specific fields
            'text_content' => 'nullable|string',
            'calculated_formula' => 'nullable|string',
            'calculated_unit' => 'nullable|string',
            'notes_content' => 'nullable|string',
            'table_headers' => 'nullable|string',
            'table_rows' => 'nullable|string',
            'interpretation_parameter' => 'nullable|array',
            'interpretation_condition' => 'nullable|array',
            'interpretation_value' => 'nullable|array',
            'interpretation_text' => 'nullable|array',
        ]);

        $maxSortOrder = $reportTemplate->sections()->max('sort_order') ?? -1;
        $contentJson = $this->buildContentJson($request, $validated['section_type']);

        TemplateSection::create([
            'template_id' => $reportTemplate->id,
            'section_type' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'content_json' => $contentJson,
            'sort_order' => $validated['sort_order'] ?? ($maxSortOrder + 1),
            'is_visible' => true,
        ]);

        return redirect()->back()
            ->with('success', 'Section added successfully.');
    }

    /**
     * Build content_json based on section type
     */
    private function buildContentJson(Request $request, string $sectionType): ?array
    {
        switch ($sectionType) {
            case 'text':
                return ['text' => $request->input('text_content')];
            
            case 'calculated':
                return [
                    'formula' => $request->input('calculated_formula'),
                    'unit' => $request->input('calculated_unit'),
                ];
            
            case 'notes':
                return ['notes' => $request->input('notes_content')];
            
            case 'table':
                $headers = $request->input('table_headers');
                $rows = $request->input('table_rows');
                
                $tableData = [];
                if ($headers) {
                    $tableData['headers'] = array_map('trim', explode(',', $headers));
                }
                if ($rows) {
                    $tableData['rows'] = array_map(function($row) {
                        return array_map('trim', explode(',', $row));
                    }, array_filter(explode("\n", $rows)));
                }
                
                return $tableData;
            
            case 'interpretation':
                $rules = [];
                $parameters = $request->input('interpretation_parameter', []);
                $conditions = $request->input('interpretation_condition', []);
                $values = $request->input('interpretation_value', []);
                $texts = $request->input('interpretation_text', []);
                
                for ($i = 0; $i < count($parameters); $i++) {
                    if (!empty($parameters[$i]) && !empty($conditions[$i]) && !empty($texts[$i])) {
                        $rule = [
                            'parameter' => $parameters[$i],
                            'condition' => $conditions[$i],
                            'value' => $values[$i] ?? null,
                            'interpretation' => $texts[$i],
                        ];
                        
                        // Handle 'between' condition
                        if ($conditions[$i] === 'between' && !empty($values[$i])) {
                            $valueParts = explode('-', $values[$i]);
                            if (count($valueParts) === 2) {
                                $rule['value'] = [
                                    'min' => trim($valueParts[0]),
                                    'max' => trim($valueParts[1]),
                                ];
                            }
                        }
                        
                        $rules[] = $rule;
                    }
                }
                
                return ['rules' => $rules];
            
            case 'parameters':
            default:
                return null;
        }
    }

    /**
     * Show a section (for AJAX requests)
     */
    public function showSection(DiagnosticReportTemplate $reportTemplate, TemplateSection $section)
    {
        // Ensure template and section belong to current lab
        if ($reportTemplate->lab_id !== currentLabId() || $section->template_id !== $reportTemplate->id) {
            abort(404);
        }

        return response()->json([
            'id' => $section->id,
            'section_type' => $section->section_type,
            'title' => $section->title,
            'content_json' => $section->content_json,
            'sort_order' => $section->sort_order,
            'is_visible' => $section->is_visible,
        ]);
    }

    /**
     * Update a section
     */
    public function updateSection(Request $request, DiagnosticReportTemplate $reportTemplate, TemplateSection $section): RedirectResponse
    {
        // Ensure template and section belong to current lab
        if ($reportTemplate->lab_id !== currentLabId() || $section->template_id !== $reportTemplate->id) {
            abort(404);
        }

        $validated = $request->validate([
            'section_type' => 'required|in:parameters,text,table,calculated,notes,interpretation',
            'title' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_visible' => 'nullable|boolean',
            // Section-specific fields
            'text_content' => 'nullable|string',
            'calculated_formula' => 'nullable|string',
            'calculated_unit' => 'nullable|string',
            'notes_content' => 'nullable|string',
            'table_headers' => 'nullable|string',
            'table_rows' => 'nullable|string',
            'interpretation_parameter' => 'nullable|array',
            'interpretation_condition' => 'nullable|array',
            'interpretation_value' => 'nullable|array',
            'interpretation_text' => 'nullable|array',
        ]);

        $contentJson = $this->buildContentJson($request, $validated['section_type']);

        $section->update([
            'section_type' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'content_json' => $contentJson,
            'sort_order' => $validated['sort_order'] ?? $section->sort_order,
            'is_visible' => $validated['is_visible'] ?? $section->is_visible,
        ]);

        return redirect()->back()
            ->with('success', 'Section updated successfully.');
    }

    /**
     * Delete a section
     */
    public function destroySection(DiagnosticReportTemplate $reportTemplate, TemplateSection $section): RedirectResponse
    {
        // Ensure template and section belong to current lab
        if ($reportTemplate->lab_id !== currentLabId() || $section->template_id !== $reportTemplate->id) {
            abort(404);
        }

        $section->delete();

        return redirect()->back()
            ->with('success', 'Section deleted successfully.');
    }

    /**
     * Reorder sections
     */
    public function reorderSections(Request $request, DiagnosticReportTemplate $reportTemplate): RedirectResponse
    {
        // Ensure template belongs to current lab
        if ($reportTemplate->lab_id !== currentLabId()) {
            abort(404);
        }

        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:template_sections,id',
            'sections.*.sort_order' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['sections'] as $sectionData) {
                TemplateSection::where('id', $sectionData['id'])
                    ->where('template_id', $reportTemplate->id)
                    ->update(['sort_order' => $sectionData['sort_order']]);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Sections reordered successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to reorder sections: ' . $e->getMessage()]);
        }
    }
}
