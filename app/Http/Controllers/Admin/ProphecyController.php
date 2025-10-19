<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Prophecy;
use App\Models\ProphecyTranslation;
use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProphecyController extends Controller
{
    public function index(Request $request)
    {
        $query = Prophecy::with(['category', 'creator'])->orderBy('created_at', 'desc');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $prophecies = $query->paginate(15);
        $categories = Category::active()->orderBy('name')->get();
        
        return view('admin.prophecies.index', compact('prophecies', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.prophecies.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'jebikalam_vanga_date' => 'required|date',
            'status' => 'required|in:draft,published,archived',
            'video_url' => 'nullable|url|max:500',
            'prayer_points_content' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        // Handle PDF file upload
        $pdfData = [];
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'prophecy_main_' . time() . '.pdf';
            $path = $file->storeAs('prophecy_pdfs', $filename, 'public');
            
            $pdfData = [
                'pdf_file' => $path,
                'pdf_uploaded_at' => now(),
                'pdf_original_name' => $file->getClientOriginalName(),
                'pdf_file_size' => $file->getSize(),
            ];
        }

        $prophecy = Prophecy::create(array_merge([
            'title' => $request->title,
            'description' => $request->description,
            'jebikalam_vanga_date' => $request->jebikalam_vanga_date,
            'category_id' => $request->category_id,
            'created_by' => Auth::id(),
            'status' => $request->status,
            'visibility' => $request->visibility ?? 'public',
            'excerpt' => $request->excerpt,
            'video_url' => $request->video_url,
            'prayer_points' => $request->prayer_points_content,
            'published_at' => $request->status === 'published' ? now() : null,
        ], $pdfData));

        // Handle Save & Continue functionality
        $message = 'Prophecy created successfully.';
        if (!empty($pdfData)) {
            $message .= ' PDF file uploaded successfully.';
        }
        
        if ($request->has('save_and_continue')) {
            return redirect()->route('admin.prophecies.edit', $prophecy)
                ->with('success', $message . ' Continue editing below.');
        }

        return redirect()->route('admin.prophecies.show', $prophecy)
            ->with('success', $message);
    }

    public function show(Prophecy $prophecy)
    {
        $prophecy->load(['category', 'creator', 'translations']);
        return view('admin.prophecies.show', compact('prophecy'));
    }

    public function edit(Prophecy $prophecy)
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.prophecies.edit', compact('prophecy', 'categories'));
    }

    public function update(Request $request, Prophecy $prophecy)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'jebikalam_vanga_date' => 'required|date',
            'status' => 'required|in:draft,published,archived',
            'video_url' => 'nullable|url|max:500',
            'prayer_points_content' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        // Handle PDF file upload
        $pdfData = [];
        if ($request->hasFile('pdf_file')) {
            // Delete old PDF file if exists
            if ($prophecy->pdf_file && Storage::disk('public')->exists($prophecy->pdf_file)) {
                Storage::disk('public')->delete($prophecy->pdf_file);
            }
            
            $file = $request->file('pdf_file');
            $filename = 'prophecy_main_' . $prophecy->id . '_' . time() . '.pdf';
            $path = $file->storeAs('prophecy_pdfs', $filename, 'public');
            
            $pdfData = [
                'pdf_file' => $path,
                'pdf_uploaded_at' => now(),
                'pdf_original_name' => $file->getClientOriginalName(),
                'pdf_file_size' => $file->getSize(),
            ];
        }

        $prophecy->update(array_merge([
            'title' => $request->title,
            'description' => $request->description,
            'jebikalam_vanga_date' => $request->jebikalam_vanga_date,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'visibility' => $request->visibility ?? 'public',
            'excerpt' => $request->excerpt,
            'video_url' => $request->video_url,
            'prayer_points' => $request->prayer_points_content,
            'published_at' => $request->status === 'published' ? now() : null,
        ], $pdfData));

        // Handle Save & Continue functionality
        $message = 'Prophecy updated successfully.';
        if (!empty($pdfData)) {
            $message .= ' PDF file uploaded successfully.';
        }
        
        if ($request->has('save_and_continue')) {
            return redirect()->route('admin.prophecies.edit', $prophecy)
                ->with('success', $message . ' Continue editing below.');
        }

        return redirect()->route('admin.prophecies.show', $prophecy)
            ->with('success', $message);
    }

    public function destroy(Prophecy $prophecy)
    {
        $prophecy->delete();
        return redirect()->route('admin.prophecies.index')
            ->with('success', 'Prophecy deleted successfully.');
    }

    public function publish(Prophecy $prophecy)
    {
        $prophecy->update(['status' => 'published', 'published_at' => now()]);
        return back()->with('success', 'Prophecy published successfully.');
    }

    public function unpublish(Prophecy $prophecy)
    {
        $prophecy->update(['status' => 'draft', 'published_at' => null]);
        return back()->with('success', 'Prophecy unpublished successfully.');
    }

    /**
     * Show translations for a prophecy.
     */
    public function translations(Prophecy $prophecy)
    {
        $prophecy->load('translations');
        $languages = [
            'ta' => 'Tamil', 
            'kn' => 'Kannada', 
            'te' => 'Telugu', 
            'ml' => 'Malayalam', 
            'hi' => 'Hindi'
        ];
        
        return view('admin.prophecies.translations', compact('prophecy', 'languages'));
    }

    /**
     * Store a new translation.
     */
    public function storeTranslation(Request $request, Prophecy $prophecy)
    {
        $request->validate([
            'language' => 'required|string|in:en,ta,kn,te,ml,hi',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'prayer_points' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        // Handle PDF file upload
        $pdfData = [];
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'prophecy_' . $prophecy->id . '_' . $request->language . '_' . time() . '.pdf';
            $path = $file->storeAs('prophecy_pdfs', $filename, 'public');
            
            $pdfData = [
                'pdf_file' => $path,
                'pdf_uploaded_at' => now(),
                'pdf_original_name' => $file->getClientOriginalName(),
                'pdf_file_size' => $file->getSize(),
            ];
        }

        ProphecyTranslation::updateOrCreate(
            [
                'prophecy_id' => $prophecy->id,
                'language' => $request->language,
            ],
            array_merge([
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'prayer_points' => $request->prayer_points,
            ], $pdfData)
        );

        $message = 'Translation saved successfully.';
        if (!empty($pdfData)) {
            $message .= ' PDF file uploaded successfully.';
        }

        return back()->with('success', $message);
    }

    /**
     * Show the form for editing a translation.
     */
    public function editTranslation(Prophecy $prophecy, $language)
    {
        // Validate language
        $validLanguages = ['en', 'ta', 'kn', 'te', 'ml', 'hi'];
        if (!in_array($language, $validLanguages)) {
            abort(404, 'Language not supported');
        }

        // Get existing translation
        $translation = ProphecyTranslation::where('prophecy_id', $prophecy->id)
            ->where('language', $language)
            ->first();

        if (!$translation) {
            return redirect()->route('admin.prophecies.translations', $prophecy)
                ->with('error', 'Translation not found for this language.');
        }

        $languages = [
            'ta' => 'Tamil', 
            'kn' => 'Kannada', 
            'te' => 'Telugu', 
            'ml' => 'Malayalam', 
            'hi' => 'Hindi'
        ];

        return view('admin.prophecies.edit-translation', compact('prophecy', 'translation', 'language', 'languages'));
    }

    /**
     * Update a translation.
     */
    public function updateTranslation(Request $request, Prophecy $prophecy, $language)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
        ]);

        $translation = ProphecyTranslation::where('prophecy_id', $prophecy->id)
            ->where('language', $language)
            ->firstOrFail();

        $translation->update([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
        ]);

        return back()->with('success', 'Translation updated successfully.');
    }

    /**
     * Delete a translation.
     */
    public function deleteTranslation(Prophecy $prophecy, $language)
    {
        $translation = ProphecyTranslation::where('prophecy_id', $prophecy->id)
            ->where('language', $language)
            ->firstOrFail();

        $translation->delete();

        return back()->with('success', 'Translation deleted successfully.');
    }
}
