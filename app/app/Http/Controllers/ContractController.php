<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Client;
use App\Models\ContractNotification;
use App\Models\ContractAudit;
use App\Jobs\SendWhatsAppMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;

class ContractController extends Controller
{
    /**
     * Constructor dengan middleware
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // Middleware untuk proteksi route
        // $this->middleware('can:manage-contracts')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $isAdmin = Auth::user() && Auth::user()->role === 'admin';

        $query = Contract::with([
            'client:id,name,email',
            'creator:id,name',
            'boqItems:id,contract_id,description,unit,quantity,unit_price,subtotal'
        ])
            ->select([
                'id',
                'contract_number',
                'nomor_surat',
                'contract_name',
                'contract_term',
                'tahun_1',
                'tahun_2',
                'jumlah',
                'ppn',
                'total',
                'pr',
                'po',
                'ttb',
                'berita_acara',
                'payment_status',
                'payment_date',
                'keterangan',
                'client_id',
                'contract_type',
                'dpp',
                'start_date',
                'end_date',
                'status',
                'created_by',
                'created_at',
                'updated_at'
            ]);

        // Jika bukan admin, batasi ke kontrak yang dibuat oleh user ini
        if (!$isAdmin) {
            $query->where('created_by', $userId);
        }

        // Filter berdasarkan status
        $status = trim((string) $request->input('status', ''));
        if ($status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        // Pencarian global
        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                    ->orWhere('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('contract_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan nomor kontrak
        $contractNumber = trim((string) $request->input('contract_number', ''));
        if ($contractNumber !== '') {
            $query->where('contract_number', 'like', "%{$contractNumber}%");
        }

        // Filter berdasarkan client
        $clientId = trim((string) $request->input('client_id', ''));
        if ($clientId !== '') {
            $query->where('client_id', $clientId);
        }

        $contractType = trim((string) $request->input('contract_type', ''));
        if ($contractType !== '' && array_key_exists($contractType, config('contract_types.types', []))) {
            $query->where('contract_type', $contractType);
        }

        // Sort and pagination normalization
        $allowedSorts = ['contract_number', 'nomor_surat', 'contract_name', 'start_date', 'end_date', 'status', 'created_at', 'updated_at', 'tahun_1', 'tahun_2', 'jumlah', 'ppn', 'total', 'pr', 'po', 'ttb', 'berita_acara', 'payment_status', 'payment_date'];
        $sort = trim((string) $request->input('sort', 'created_at'));
        $order = strtolower(trim((string) $request->input('order', 'desc')));

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (!in_array($order, ['asc', 'desc'], true)) {
            $order = 'desc';
        }

        $query->orderBy($sort, $order);

        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $contracts = $query->paginate($perPage)->withQueryString();

        // Ambil data clients untuk dropdown filter (hanya visible untuk user jika bukan admin)
        $clientsQuery = Client::orderBy('name')->select(['id', 'name']);
        if (!$isAdmin) {
            $clientsQuery->where('created_by', $userId);
        }
        $clients = $clientsQuery->get();

        // Hitung status kontrak (termasuk pembatasan untuk non-admin)
        $allContractQuery = Contract::query();
        if (!$isAdmin) {
            $allContractQuery->where('created_by', $userId);
        }

        $activeCount = (clone $allContractQuery)->where('status', 'active')->count();
        $gracePeriodCount = (clone $allContractQuery)->where('status', 'grace_period')->count();
        $expiredCount = (clone $allContractQuery)->where('status', 'expired')->count();
        $totalContracts = $allContractQuery->count();
        $activeContracts = $activeCount;

        return view('contracts.index', compact(
            'contracts',
            'clients',
            'activeCount',
            'gracePeriodCount',
            'expiredCount',
            'totalContracts',
            'activeContracts'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $contractTypes = config('contract_types.types', []);

        return view('contracts.create', compact('clients', 'contractTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'contract_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('contracts', 'contract_number')
            ],
            'nomor_surat' => 'nullable|string|max:10000',
            'contract_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'start_date' => 'required|date|after_or_equal:'.now()->subYear()->toDateString(),
            'end_date' => 'required|date|after:start_date',
            'grace_period_days' => 'nullable|integer|min:1|max:365',
            'actual_end_date' => 'nullable|date|after:start_date',
            'termination_reason' => 'nullable|string|max:500',
            'status' => 'required|in:active,grace_period,expired,terminated',
            'contract_term' => 'nullable|in:termin_1,termin_2,termin_3,termin_4',
            'tahun_1' => 'nullable|numeric|min:0',
            'tahun_2' => 'nullable|numeric|min:0',
            'pr' => 'nullable|numeric',
            'po' => 'nullable|numeric',
            'ttb' => 'nullable|numeric',
            'berita_acara' => 'nullable|string|max:255',
            'payment_status' => 'nullable|in:SUDAH BAYAR,BELUM BAYAR',
            'payment_date' => 'nullable|date',
            'keterangan' => 'nullable|string|max:1000',
            'contract_type' => ['required', Rule::in(array_keys(config('contract_types.types', [])))],
            'dpp' => 'nullable|numeric|min:0',
            'boq_items' => 'nullable|array|max:500',
            'boq_items.*.description' => 'nullable|string|max:255|required_with:boq_items.*.unit,boq_items.*.quantity,boq_items.*.unit_price',
            'boq_items.*.unit' => 'nullable|string|max:30|required_with:boq_items.*.description,boq_items.*.quantity,boq_items.*.unit_price',
            'boq_items.*.quantity' => 'nullable|numeric|decimal:0,3|min:0.001|required_with:boq_items.*.description,boq_items.*.unit,boq_items.*.unit_price',
            'boq_items.*.unit_price' => 'nullable|numeric|decimal:0,2|min:0|required_with:boq_items.*.description,boq_items.*.unit,boq_items.*.quantity',
            'pdf_files' => 'required|array|min:1',
            'pdf_files.*' => 'file|mimes:pdf|max:51200',
        ]);

        DB::beginTransaction();

        try {
            $userId = Auth::id();

            // Handle jika user tidak login
            if (!$userId) {
                // Jika menggunakan API atau tanpa auth, gunakan sistem user
                $systemUser = User::where('email', 'system@example.com')->first();

                if (!$systemUser) {
                    $systemUser = User::create([
                        'name' => 'System',
                        'email' => 'system@example.com',
                        'password' => bcrypt(Str::random(32)),
                        'email_verified_at' => now(),
                    ]);
                }

                $userId = $systemUser->id;
            }

            // Buat kontrak
            $currentUser = Auth::user();
            $client = Client::find($validated['client_id']);
            $t1 = isset($validated['tahun_1']) && $validated['tahun_1'] !== '' ? (float)$validated['tahun_1'] : null;
            $t2 = isset($validated['tahun_2']) && $validated['tahun_2'] !== '' ? (float)$validated['tahun_2'] : null;
            $jml = ($t1 !== null || $t2 !== null) ? (($t1 ?? 0) + ($t2 ?? 0)) : null;
            $ppn = $jml !== null ? round($jml * 0.11, 2) : null;
            $total = $jml !== null ? round($jml + ($ppn ?? 0), 2) : null;

            $contract = Contract::create([
                'contract_number' => $validated['contract_number'],
                'nomor_surat' => $validated['nomor_surat'] ?? null,
                'contract_name' => $validated['contract_name'],
                'client_id' => $validated['client_id'],
                'vendor_name' => $client?->name,
                'contract_type' => $validated['contract_type'],
                'description' => $validated['description'] ?? null,
                'dpp' => $validated['dpp'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'grace_period_days' => $validated['grace_period_days'] ?? 30,
                'actual_end_date' => $validated['actual_end_date'] ?? null,
                'termination_reason' => $validated['termination_reason'] ?? null,
                'status' => $validated['status'],
                'contract_term' => $validated['contract_term'] ?? null,
                'tahun_1' => $t1,
                'tahun_2' => $t2,
                'jumlah' => $jml,
                'ppn' => $ppn,
                'total' => $total,
                'pr' => isset($validated['pr']) && $validated['pr'] !== '' ? $validated['pr'] : null,
                'po' => isset($validated['po']) && $validated['po'] !== '' ? $validated['po'] : null,
                'ttb' => isset($validated['ttb']) && $validated['ttb'] !== '' ? $validated['ttb'] : null,
                'berita_acara' => $validated['berita_acara'] ?? null,
                'payment_status' => $validated['payment_status'] ?? 'BELUM BAYAR',
                'payment_date' => $validated['payment_date'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'created_by' => $userId,
                'account_name' => $currentUser ? $currentUser->name : ($systemUser->name ?? null),
                'account_position' => $currentUser ? ($currentUser->position ?? null) : null,
            ]);

            $boqTotal = $this->syncBoqItems($contract, $validated['boq_items'] ?? []);
            $contract->update(['dpp' => $boqTotal ?? ($validated['dpp'] ?? null)]);

            // Handle multiple PDF file uploads
            if ($request->hasFile('pdf_files')) {
                $pdfPaths = [];
                
                foreach ($request->file('pdf_files') as $file) {
                    // Generate unique filename - sanitize to avoid URL encoding issues
                    $timestamp = now()->timestamp;
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    // Replace spaces and special characters with underscores for URL safety
                    $originalName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $originalName);
                    $fileName = $contract->id . '_' . $timestamp . '_' . $originalName . '.pdf';
                    
                    // Store file di storage/public/contracts/pdf
                    $path = $file->storeAs('contracts/pdf', $fileName, 'public');
                    $pdfPaths[] = $path;
                    
                    // Log file upload untuk audit
                    Log::info('PDF file uploaded', [
                        'contract_id' => $contract->id,
                        'file_name' => $fileName,
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'uploaded_by' => $userId,
                    ]);
                }
                
                // Simpan paths sebagai array (model casting akan handle JSON encoding)
                $contract->update([
                    'pdf_files' => $pdfPaths
                ]);
            };

            DB::commit();

            return redirect()->route('contracts.show', $contract)
                ->with('success', 'Kontrak berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error untuk debugging
            Log::error('Gagal membuat kontrak: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        // Eager load relationships
        $contract->load(['client', 'creator', 'notifications', 'boqItems']);

        return view('contracts.show', compact('contract'));
    }

    /**
     * Download all stored PDF documents attached to the contract as a ZIP bundle.
     */
    public function printPdf(Contract $contract)
    {
        $contract->load(['client', 'creator', 'boqItems']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('contracts.print_pdf', compact('contract'));
        $pdf->setPaper('A4', 'portrait');

        $filename = preg_replace('/[^A-Za-z0-9._-]+/', '-', trim($contract->contract_number));
        $filename = $filename ?: 'kontrak';

        return $pdf->download($filename . '-detail.pdf');
    }

    public function downloadPdf(Request $request, Contract $contract)
    {
        $pdfFilesRaw = $contract->pdf_files ?? [];

        if (empty($pdfFilesRaw) || !is_array($pdfFilesRaw)) {
            abort(404, 'No PDF files found for this contract.');
        }

        // Filter and validate: ensure all items are strings and non-empty
        $pdfFiles = collect($pdfFilesRaw)
            ->filter(function ($item) {
                return is_string($item) && !empty(trim($item));
            })
            ->map(function ($item) {
                return trim($item);
            })
            ->values()
            ->toArray();

        if (empty($pdfFiles)) {
            abort(404, 'No valid PDF files found for this contract.');
        }

        // If only one file, download directly
        if (count($pdfFiles) === 1) {
            $filePath = $pdfFiles[0];
            
            // Validate path is string
            if (!is_string($filePath) || !Storage::disk('public')->exists($filePath)) {
                abort(404, 'PDF file not found.');
            }
            
            try {
                /** @var \Illuminate\Filesystem\FilesystemAdapter $publicDisk */
                $publicDisk = Storage::disk('public');
                return $publicDisk->download($filePath, basename($filePath));
            } catch (\Exception $e) {
                Log::error('Error downloading PDF', ['path' => $filePath, 'error' => $e->getMessage()]);
                abort(500, 'Error downloading file.');
            }
        }

        // Multiple files: create ZIP bundle if ZipArchive is available
        if (class_exists('\ZipArchive')) {
            $zipFileName = 'contract_' . $contract->contract_number . '_documents_' . now()->format('Ymd_His') . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);

            // Ensure temp directory exists
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                @mkdir($tempDir, 0755, true);
            }

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                abort(500, 'Could not create ZIP file.');
            }

            $addedFiles = 0;
            foreach ($pdfFiles as $filePath) {
                // Skip if not string
                if (!is_string($filePath)) {
                    continue;
                }

                try {
                    $fullPath = Storage::disk('public')->path($filePath);
                    if (file_exists($fullPath)) {
                        $fileName = basename($filePath);
                        $zip->addFile($fullPath, $fileName);
                        $addedFiles++;
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not add file to ZIP', ['path' => $filePath, 'error' => $e->getMessage()]);
                    continue;
                }
            }

            $zip->close();

            if ($addedFiles === 0) {
                if (file_exists($zipPath)) {
                    @unlink($zipPath);
                }
                abort(404, 'No valid PDF files found.');
            }

            try {
                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            } catch (\Exception $e) {
                Log::error('Error downloading ZIP', ['path' => $zipPath, 'error' => $e->getMessage()]);
                if (file_exists($zipPath)) {
                    @unlink($zipPath);
                }
                abort(500, 'Error downloading file.');
            }
        }

        // Fallback: download the first file when ZIP is not available
        $filePath = $pdfFiles[0];
        
        // Validate path is string
        if (!is_string($filePath)) {
            abort(404, 'Invalid PDF file path.');
        }

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'PDF file not found.');
        }

        try {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $publicDisk */
            $publicDisk = Storage::disk('public');
            return $publicDisk->download($filePath, basename($filePath));
        } catch (\Exception $e) {
            Log::error('Error downloading PDF (fallback)', ['path' => $filePath, 'error' => $e->getMessage()]);
            abort(500, 'Error downloading file.');
        }
    }

    /**
     * Download PDF via signed QR link for external devices (no login required).
     */
    public function downloadPdfScan(Request $request, Contract $contract)
    {
        return $this->downloadPdf($request, $contract);
    }

    /**
     * Rename PDF file
     */
    public function renamePdf(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'old_path' => 'required|string',
            'new_name' => 'required|string|min:3|max:255',
        ]);

        try {
            $oldPath = $validated['old_path'];
            $newName = $validated['new_name'];
            
            // Sanitize new name
            $newName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $newName);
            
            // Get the directory from old path
            $pathParts = explode('/', $oldPath);
            $directory = implode('/', array_slice($pathParts, 0, -1));
            $newPath = $directory . '/' . $newName . '.pdf';
            
            // Check if old file exists
            if (!Storage::disk('public')->exists($oldPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], 404);
            }
            
            // Check if new name already exists
            if (Storage::disk('public')->exists($newPath) && $oldPath !== $newPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama file sudah digunakan'
                ], 422);
            }
            
            // Get file content
            $fileContent = Storage::disk('public')->get($oldPath);
            
            // Store with new name
            Storage::disk('public')->put($newPath, $fileContent);
            
            // Update database
            $pdfFiles = $contract->pdf_files ?? [];
            if (is_string($pdfFiles)) {
                $pdfFiles = json_decode($pdfFiles, true) ?? [];
            }

            // Validate and filter: ensure all are strings
            $pdfFiles = collect($pdfFiles)
                ->filter(function ($path) {
                    return is_string($path) && !empty($path);
                })
                ->map(function($path) use ($oldPath, $newPath) {
                    return $path === $oldPath ? $newPath : $path;
                })
                ->values()
                ->toArray();
            
            $contract->update(['pdf_files' => $pdfFiles]);
            
            // Delete old file
            Storage::disk('public')->delete($oldPath);
            
            // Log audit
            Log::info('PDF file renamed', [
                'contract_id' => $contract->id,
                'old_path' => $oldPath,
                'new_path' => $newPath,
                'renamed_by' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'File berhasil diganti nama'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error renaming PDF file', [
                'error' => $e->getMessage(),
                'contract_id' => $contract->id,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengganti nama file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete PDF file
     */
    public function deletePdf(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'file_path' => 'required|string',
        ]);

        try {
            $filePath = trim($validated['file_path']);
            
            // Validate filePath
            if (!is_string($filePath) || empty($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Path file tidak valid'
                ], 400);
            }
            
            // Check if file exists
            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], 404);
            }
            
            // Update database - remove from array
            $pdfFiles = $contract->pdf_files ?? [];
            if (is_string($pdfFiles)) {
                $pdfFiles = json_decode($pdfFiles, true) ?? [];
            }

            // Validate and filter: ensure all are strings and remove the target
            $pdfFiles = collect($pdfFiles)
                ->filter(function ($path) use ($filePath) {
                    return is_string($path) && !empty($path) && $path !== $filePath;
                })
                ->values()
                ->toArray();
            
            $contract->update(['pdf_files' => $pdfFiles]);
            
            // Delete file from storage
            Storage::disk('public')->delete($filePath);
            
            // Log audit
            Log::info('PDF file deleted', [
                'contract_id' => $contract->id,
                'file_path' => $filePath,
                'deleted_by' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting PDF file', [
                'error' => $e->getMessage(),
                'contract_id' => $contract->id,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client details for contract form (AJAX)
     */
    public function getClientDetails(Client $client)
    {
        return response()->json([
            'id' => $client->id,
            'name' => $client->name,
            'email' => $client->email,
            'phone' => $client->phone ?? '',
            'address' => $client->address ?? '',
            'pic_name' => $client->pic_name ?? '',
            'pic_email' => $client->pic_email ?? '',
            'pic_phone' => $client->pic_phone ?? '',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        // Optional: Tambahkan authorization check
        // $this->authorize('update', $contract);

        // Cek apakah kontrak sudah terminated atau expired
        if (in_array($contract->status, ['terminated', 'expired'])) {
            return back()->with('warning', 'Kontrak yang sudah dihentikan atau kedaluwarsa tidak dapat diedit.');
        }

        $contract->load('boqItems');

        $clients = Client::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone', 'address']);

        $contractTypes = config('contract_types.types', []);

        return view('contracts.edit', compact('contract', 'clients', 'contractTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        // Validasi tanggal: saat edit, tidak perlu enforce start_date harus di masa depan
        // karena kontrak sudah berjalan. Hanya validasi format dan urutan tanggal.
        $validated = $request->validate([
            'contract_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('contracts', 'contract_number')->ignore($contract->id)
            ],
            'nomor_surat' => 'nullable|string|max:10000',
            'contract_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'grace_period_days' => 'nullable|integer|min:1|max:365',
            'actual_end_date' => 'nullable|date|after:start_date',
            'termination_reason' => 'nullable|string|max:500',
            'status' => 'required|in:active,grace_period,expired,terminated',
            'contract_term' => 'nullable|in:termin_1,termin_2,termin_3,termin_4',
            'tahun_1' => 'nullable|numeric|min:0',
            'tahun_2' => 'nullable|numeric|min:0',
            'pr' => 'nullable|numeric',
            'po' => 'nullable|numeric',
            'ttb' => 'nullable|numeric',
            'berita_acara' => 'nullable|string|max:255',
            'payment_status' => 'nullable|in:SUDAH BAYAR,BELUM BAYAR',
            'payment_date' => 'nullable|date',
            'keterangan' => 'nullable|string|max:1000',
            'contract_type' => ['required', Rule::in(array_keys(config('contract_types.types', [])))],
            'dpp' => 'nullable|numeric|min:0',
            'boq_items' => 'nullable|array|max:500',
            'boq_items.*.description' => 'nullable|string|max:255|required_with:boq_items.*.unit,boq_items.*.quantity,boq_items.*.unit_price',
            'boq_items.*.unit' => 'nullable|string|max:30|required_with:boq_items.*.description,boq_items.*.quantity,boq_items.*.unit_price',
            'boq_items.*.quantity' => 'nullable|numeric|decimal:0,3|min:0.001|required_with:boq_items.*.description,boq_items.*.unit,boq_items.*.unit_price',
            'boq_items.*.unit_price' => 'nullable|numeric|decimal:0,2|min:0|required_with:boq_items.*.description,boq_items.*.unit,boq_items.*.quantity',
            'pdf_files' => 'nullable|array|min:1',
            'pdf_files.*' => 'file|mimes:pdf|max:51200',
            'remove_pdf_files' => 'nullable|array',
            'remove_pdf_files.*' => 'string',
        ]);

        DB::beginTransaction();

        try {
            $oldContractType = $contract->contract_type;

            $client = Client::find($validated['client_id']);

            $contractData = $validated;
            unset($contractData['boq_items'], $contractData['pdf_files'], $contractData['remove_pdf_files']);

            $t1 = isset($contractData['tahun_1']) && $contractData['tahun_1'] !== '' && $contractData['tahun_1'] !== null ? (float)$contractData['tahun_1'] : null;
            $t2 = isset($contractData['tahun_2']) && $contractData['tahun_2'] !== '' && $contractData['tahun_2'] !== null ? (float)$contractData['tahun_2'] : null;
            $contractData['tahun_1'] = $t1;
            $contractData['tahun_2'] = $t2;
            $contractData['jumlah'] = ($t1 !== null || $t2 !== null) ? (($t1 ?? 0) + ($t2 ?? 0)) : null;
            $contractData['ppn'] = $contractData['jumlah'] !== null ? round($contractData['jumlah'] * 0.11, 2) : null;
            $contractData['total'] = $contractData['jumlah'] !== null ? round($contractData['jumlah'] + ($contractData['ppn'] ?? 0), 2) : null;
            $contractData['nomor_surat'] = isset($contractData['nomor_surat']) && $contractData['nomor_surat'] !== '' ? $contractData['nomor_surat'] : null;
            $contractData['pr'] = isset($contractData['pr']) && $contractData['pr'] !== '' ? $contractData['pr'] : null;
            $contractData['po'] = isset($contractData['po']) && $contractData['po'] !== '' ? $contractData['po'] : null;
            $contractData['ttb'] = isset($contractData['ttb']) && $contractData['ttb'] !== '' ? $contractData['ttb'] : null;
            $contractData['berita_acara'] = isset($contractData['berita_acara']) && $contractData['berita_acara'] !== '' ? $contractData['berita_acara'] : null;
            $contractData['payment_status'] = $contractData['payment_status'] ?? 'BELUM BAYAR';
            $contractData['payment_date'] = isset($contractData['payment_date']) && $contractData['payment_date'] !== '' ? $contractData['payment_date'] : null;
            $contractData['keterangan'] = isset($contractData['keterangan']) && $contractData['keterangan'] !== '' ? $contractData['keterangan'] : null;

            // Ensure only allowed fields are updated
            $contract->update(array_merge($contractData, [
                'vendor_name' => $client?->name,
            ]));

            $boqTotal = $this->syncBoqItems($contract, $validated['boq_items'] ?? []);
            $contract->update(['dpp' => $boqTotal]);

            // Handle multiple PDF file uploads (append to existing)
            if ($request->hasFile('pdf_files')) {
                // Get existing PDFs (model casting handles array conversion)
                $existingPdfs = is_array($contract->pdf_files) ? $contract->pdf_files : [];
                
                // Validate and filter existing PDFs: ensure all are strings
                $existingPdfs = collect($existingPdfs)
                    ->filter(function ($path) {
                        return is_string($path) && !empty($path);
                    })
                    ->values()
                    ->toArray();
                
                $pdfPaths = [];
                
                foreach ($request->file('pdf_files') as $file) {
                    // Generate unique filename - sanitize to avoid URL encoding issues
                    $timestamp = now()->timestamp;
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    // Replace spaces and special characters with underscores for URL safety
                    $originalName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $originalName);
                    $fileName = $contract->id . '_' . $timestamp . '_' . $originalName . '.pdf';
                    
                    // Store file di storage/public/contracts/pdf
                    $path = $file->storeAs('contracts/pdf', $fileName, 'public');
                    $pdfPaths[] = $path;
                    
                    // Log file upload untuk audit
                    Log::info('PDF file uploaded on update', [
                        'contract_id' => $contract->id,
                        'file_name' => $fileName,
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'uploaded_by' => Auth::id(),
                    ]);
                }
                
                // Merge with existing PDFs
                $allPdfs = array_merge($existingPdfs, $pdfPaths);
                
                // Simpan paths sebagai array (model casting akan handle JSON encoding)
                $contract->update([
                    'pdf_files' => $allPdfs
                ]);
            }

            // Handle removing PDF files
            if ($request->has('remove_pdf_files') && is_array($request->remove_pdf_files)) {
                // Get existing PDFs (model casting handles array conversion)
                $existingPdfs = is_array($contract->pdf_files) ? $contract->pdf_files : [];
                
                // Validate and filter: ensure all are strings
                $existingPdfs = collect($existingPdfs)
                    ->filter(function ($path) {
                        return is_string($path) && !empty($path);
                    })
                    ->toArray();
                
                $toRemove = $request->remove_pdf_files;
                
                foreach ($toRemove as $pathToRemove) {
                    // Validate pathToRemove is string
                    if (!is_string($pathToRemove) || empty($pathToRemove)) {
                        continue;
                    }
                    
                    if (in_array($pathToRemove, $existingPdfs)) {
                        // Delete file from storage
                        try {
                            if (Storage::disk('public')->exists($pathToRemove)) {
                                Storage::disk('public')->delete($pathToRemove);
                            }
                        } catch (\Exception $e) {
                            Log::warning('Could not delete PDF file', ['path' => $pathToRemove, 'error' => $e->getMessage()]);
                        }
                        
                        // Remove from array
                        $existingPdfs = array_diff($existingPdfs, [$pathToRemove]);
                        
                        // Log removal
                        Log::info('PDF file removed on update', [
                            'contract_id' => $contract->id,
                            'file_path' => $pathToRemove,
                            'removed_by' => Auth::id(),
                        ]);
                    }
                }
                
                // Update database (model casting will handle JSON encoding)
                $contract->update([
                    'pdf_files' => array_values($existingPdfs)
                ]);
            };

            DB::commit();

            $message = 'Kontrak berhasil diperbarui.';
            if ($oldContractType !== $contract->contract_type) {
                $message .= ' Jenis kontrak diperbarui.';
            }

            return redirect()->route('contracts.show', $contract)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui kontrak: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kontrak: ' . $e->getMessage());
        }
    }

    private function syncBoqItems(Contract $contract, array $rows): ?float
    {
        $contract->boqItems()->delete();
        $total = 0.0;
        $hasItems = false;

        foreach ($rows as $row) {
            $description = trim((string) ($row['description'] ?? ''));
            $unit = trim((string) ($row['unit'] ?? ''));
            $quantity = $row['quantity'] ?? null;
            $unitPrice = $row['unit_price'] ?? null;

            if ($description === '' && $unit === '' && ($quantity === null || $quantity === '') && ($unitPrice === null || $unitPrice === '')) {
                continue;
            }

            $subtotal = round((float) $quantity * (float) $unitPrice, 2);
            $contract->boqItems()->create([
                'description' => $description,
                'unit' => $unit,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);

            $total = round($total + $subtotal, 2);
            $hasItems = true;
        }

        return $hasItems ? $total : null;
    }

    public function bulkDelete(Request $request)
    {
        $filters = $request->validate([
            'status' => 'nullable|in:all,active,grace_period,expired,terminated',
            'client_id' => 'nullable|exists:clients,id',
            'contract_type' => ['nullable', Rule::in(array_keys(config('contract_types.types', [])))],
            'search' => 'nullable|string|max:255',
            'contract_number' => 'nullable|string|max:100',
            'contract_ids' => 'sometimes|array|min:1|max:500',
            'contract_ids.*' => 'required|integer|distinct|exists:contracts,id',
            'force' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $query = Contract::query();

        if ($user->role !== 'admin') {
            $query->where('created_by', $user->id);
        }

        $selectedIds = $filters['contract_ids'] ?? [];
        if ($selectedIds) {
            $accessibleCount = (clone $query)->whereIn('id', $selectedIds)->count();
            abort_unless($accessibleCount === count($selectedIds), 403);
            $query->whereIn('id', $selectedIds);
        } else {
            if (!empty($filters['status']) && $filters['status'] !== 'all') {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['client_id'])) {
                $query->where('client_id', $filters['client_id']);
            }

            if (!empty($filters['contract_type'])) {
                $query->where('contract_type', $filters['contract_type']);
            }

            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('contract_number', 'like', "%{$search}%")
                        ->orWhere('contract_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($clientQuery) use ($search) {
                            $clientQuery->where('name', 'like', "%{$search}%");
                        });
                });
            }

            if (!empty($filters['contract_number'])) {
                $query->where('contract_number', 'like', "%{$filters['contract_number']}%");
            }
        }

        $force = $request->boolean('force');
        $matchingCount = (clone $query)->count();
        $deletableQuery = clone $query;

        if (!$force) {
            $deletableQuery->whereNotIn('status', ['active', 'grace_period']);
        }

        $deletableCount = (clone $deletableQuery)->count();
        $skippedCount = $matchingCount - $deletableCount;

        if ($deletableCount === 0) {
            return back()->with('warning', $matchingCount > 0
                ? 'Tidak ada kontrak yang dapat dihapus. Kontrak aktif dan masa tenggang perlu konfirmasi paksa.'
                : ($selectedIds ? 'Tidak ada kontrak terpilih yang cocok.' : 'Tidak ada kontrak yang cocok dengan filter saat ini.'));
        }

        DB::transaction(function () use ($deletableQuery) {
            $deletableQuery->orderBy('id')->chunkById(100, function ($contracts) {
                foreach ($contracts as $contract) {
                    $contract->boqItems()->delete();
                    $contract->delete();
                }
            });
        });

        $message = "{$deletableCount} kontrak berhasil dihapus.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} kontrak aktif/masa tenggang dilewati.";
        }

        if ($force) {
            Log::warning('Bulk forced contract deletion completed.', [
                'user_id' => $user->id,
                'deleted_count' => $deletableCount,
                'filters' => $filters,
                'ip' => $request->ip(),
            ]);
        }

        return redirect()->route('contracts.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Contract $contract)
    {
        DB::beginTransaction();

        try {
            // Cek apakah kontrak bisa dihapus
            if (in_array($contract->status, ['active', 'grace_period']) && !$request->boolean('force')) {
                // Log detailed context for why deletion was blocked
                Log::warning('Blocked attempt to delete contract due to active/grace status.', [
                    'user_id' => Auth::id(),
                    'contract_id' => $contract->id,
                    'contract_number' => $contract->contract_number ?? null,
                    'contract_status' => $contract->status,
                    'request_ip' => $request->ip(),
                    'request_method' => $request->method(),
                    'request_url' => $request->fullUrl(),
                    'user_agent' => $request->header('User-Agent'),
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat menghapus kontrak yang masih aktif.'
                    ], 422);
                }

                return back()
                    ->with('error', 'Tidak dapat menghapus kontrak yang masih aktif.');
            }

            // If force flag is present and contract is active/grace, log forced deletion
            if (in_array($contract->status, ['active', 'grace_period']) && $request->boolean('force')) {
                Log::warning('Forced deletion of active/grace contract.', [
                    'user_id' => Auth::id(),
                    'contract_id' => $contract->id,
                    'contract_number' => $contract->contract_number ?? null,
                    'contract_status' => $contract->status,
                    'request_ip' => $request->ip(),
                    'request_method' => $request->method(),
                    'request_url' => $request->fullUrl(),
                    'user_agent' => $request->header('User-Agent'),
                ]);
            }

            $contract->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kontrak berhasil dihapus.'
                ]);
            }

            return redirect()->route('contracts.index')
                ->with('success', 'Kontrak berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menghapus kontrak: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'trace' => $e->getTraceAsString()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus kontrak: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->with('error', 'Gagal menghapus kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Terminate a contract.
     */
    public function terminate(Request $request, Contract $contract)
    {
        $request->validate([
            'termination_reason' => 'required|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            // Cek apakah kontrak sudah terminated
            if ($contract->status == 'terminated') {
                return response()->json([
                    'success' => false,
                    'message' => 'Kontrak sudah dihentikan sebelumnya.'
                ], 400);
            }

            $contract->update([
                'status' => 'terminated',
                'termination_reason' => $request->termination_reason,
                'actual_end_date' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kontrak berhasil dihentikan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menghentikan kontrak: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghentikan kontrak: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update contract status.
     */
    public function updateStatus(Request $request, Contract $contract)
    {
        $request->validate([
            'status' => 'required|in:active,grace_period,expired,terminated',
            'termination_reason' => 'nullable|required_if:status,terminated|string|max:500',
            'actual_end_date' => 'nullable|date|after:start_date',
        ]);

        DB::beginTransaction();

        try {
            $updateData = ['status' => $request->status];

            if ($request->status === 'terminated') {
                if (!$request->termination_reason) {
                    throw new \Exception('Alasan penghentian wajib diisi untuk status terminated.');
                }
                $updateData['termination_reason'] = $request->termination_reason;
                $updateData['actual_end_date'] = $request->actual_end_date ?? now();
            }

            $contract->update($updateData);

            DB::commit();

            return back()
                ->with('success', 'Status kontrak berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal mengubah status kontrak: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Export contracts data.
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'json');

        // Build base query (reuse filters like index)
        $query = Contract::with(['client', 'creator'])
            ->select(['id', 'contract_number', 'nomor_surat', 'contract_name', 'contract_type', 'contract_term', 'client_id', 'tahun_1', 'tahun_2', 'jumlah', 'ppn', 'total', 'pr', 'po', 'ttb', 'berita_acara', 'keterangan', 'payment_status', 'payment_date', 'start_date', 'end_date', 'status', 'created_at'])
            ->when($request->has('status') && $request->status != 'all', function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->has('client_id') && $request->client_id, function ($q) use ($request) {
                return $q->where('client_id', $request->client_id);
            })
            ->when($request->has('search') && $request->search != '', function ($q) use ($request) {
                $search = $request->search;
                return $q->where(function ($qq) use ($search) {
                    $qq->where('contract_number', 'like', "%{$search}%")
                        ->orWhere('contract_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($clientQuery) use ($search) {
                            $clientQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->has('expiring') && $request->get('expiring'), function ($q) {
                // Kontrak yang akan berakhir dalam 30 hari ke depan
                return $q->where('status', 'active')
                    ->whereBetween('end_date', [now()->toDateString(), now()->addDays(30)->toDateString()]);
            })
            ->orderBy('created_at', 'desc');

        try {
            // CSV export (native streaming)
            if ($type === 'csv') {
                $contracts = $query->get();
                $fileName = 'contracts_' . now()->format('Ymd_His') . '.csv';
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                ];

                $callback = function () use ($contracts) {
                    $out = fopen('php://output', 'w');
                    fputcsv($out, ['Contract Number', 'Nomor Surat', 'Contract Name', 'Client', 'Jenis Kontrak', 'Tahun I', 'Tahun II', 'Jumlah', 'PPN (11%)', 'Total', 'PR', 'PO', 'TTB', 'Berita Acara', 'Keterangan', 'Status Pembayaran', 'Start Date', 'End Date', 'Status', 'Created At']);

                    foreach ($contracts as $c) {
                        $termLabel = ['termin_1' => 'Termin I', 'termin_2' => 'Termin II', 'termin_3' => 'Termin III', 'termin_4' => 'Termin IV'][$c->contract_term] ?? $c->contract_term;
                        $ketFull = trim(($termLabel ? "[{$termLabel}] " : '') . ($c->payment_date ? "Tgl: " . $c->payment_date->format('d/m/Y') . " " : '') . ($c->payment_status ? "[{$c->payment_status}] " : '') . ($c->keterangan ?? ''));
                        fputcsv($out, [
                            $c->contract_number,
                            $c->nomor_surat ?? '',
                            $c->contract_name,
                            optional($c->client)->name ?? '',
                            config('contract_types.types.' . $c->contract_type . '.name', ''),
                            $c->tahun_1 ?? '',
                            $c->tahun_2 ?? '',
                            $c->jumlah ?? '',
                            $c->ppn ?? '',
                            $c->total ?? '',
                            $c->pr ?? '',
                            $c->po ?? '',
                            $c->ttb ?? '',
                            $c->berita_acara ?? '',
                            $ketFull ?: ($c->keterangan ?? ''),
                            $c->payment_status ?? 'BELUM BAYAR',
                            $c->start_date ? $c->start_date->format('Y-m-d') : '',
                            $c->end_date ? $c->end_date->format('Y-m-d') : '',
                            $c->status,
                            $c->created_at ? $c->created_at->format('Y-m-d H:i:s') : '',
                        ]);
                    }

                    fclose($out);
                };

                return response()->stream($callback, 200, $headers);
            }

            // Excel export (use PhpSpreadsheet when available)
            if ($type === 'excel') {
                $contracts = $query->get();
                $fileName = 'contracts_' . now()->format('Ymd_His') . '.xlsx';

                if (class_exists('\PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();

                    // Header row
                    $sheet->fromArray(['Contract Number', 'Nomor Surat', 'Contract Name', 'Client', 'Jenis Kontrak', 'Tahun I', 'Tahun II', 'Jumlah', 'PPN (11%)', 'Total', 'PR', 'PO', 'TTB', 'Berita Acara', 'Keterangan', 'Status Pembayaran', 'Start Date', 'End Date', 'Status', 'Created At'], NULL, 'A1');

                    $row = 2;
                    foreach ($contracts as $c) {
                        $termLabel = ['termin_1' => 'Termin I', 'termin_2' => 'Termin II', 'termin_3' => 'Termin III', 'termin_4' => 'Termin IV'][$c->contract_term] ?? $c->contract_term;
                        $ketFull = trim(($termLabel ? "[{$termLabel}] " : '') . ($c->payment_date ? "Tgl: " . $c->payment_date->format('d/m/Y') . " " : '') . ($c->payment_status ? "[{$c->payment_status}] " : '') . ($c->keterangan ?? ''));
                        $sheet->setCellValue('A' . $row, $c->contract_number);
                        $sheet->setCellValue('B' . $row, $c->nomor_surat ?? '');
                        $sheet->setCellValue('C' . $row, $c->contract_name);
                        $sheet->setCellValue('D' . $row, optional($c->client)->name ?? '');
                        $sheet->setCellValue('E' . $row, config('contract_types.types.' . $c->contract_type . '.name', ''));
                        $sheet->setCellValue('F' . $row, $c->tahun_1 ?? '');
                        $sheet->setCellValue('G' . $row, $c->tahun_2 ?? '');
                        $sheet->setCellValue('H' . $row, $c->jumlah ?? '');
                        $sheet->setCellValue('I' . $row, $c->ppn ?? '');
                        $sheet->setCellValue('J' . $row, $c->total ?? '');
                        $sheet->setCellValue('K' . $row, $c->pr ?? '');
                        $sheet->setCellValue('L' . $row, $c->po ?? '');
                        $sheet->setCellValue('M' . $row, $c->ttb ?? '');
                        $sheet->setCellValue('N' . $row, $c->berita_acara ?? '');
                        $sheet->setCellValue('O' . $row, $ketFull ?: ($c->keterangan ?? ''));
                        $sheet->setCellValue('P' . $row, $c->payment_status ?? 'BELUM BAYAR');
                        $sheet->setCellValue('Q' . $row, $c->start_date ? $c->start_date->format('Y-m-d') : '');
                        $sheet->setCellValue('R' . $row, $c->end_date ? $c->end_date->format('Y-m-d') : '');
                        $sheet->setCellValue('S' . $row, $c->status);
                        $sheet->setCellValue('T' . $row, $c->created_at ? $c->created_at->format('Y-m-d H:i:s') : '');
                        $row++;
                    }

                    // Style: bold header + autosize columns
                    $sheet->getStyle('A1:T1')->getFont()->setBold(true);
                    foreach (range('A', 'T') as $col) {
                        $sheet->getColumnDimension($col)->setAutoSize(true);
                    }

                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $temp = tempnam(sys_get_temp_dir(), 'xlsx');
                    $writer->save($temp);

                    return response()->download($temp, $fileName)->deleteFileAfterSend(true);
                }

                // Fallback: provide CSV but with .xlsx filename for compatibility
                $headers = [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                ];

                $callback = function () use ($contracts) {
                    $out = fopen('php://output', 'w');
                    fputcsv($out, ['Contract Number', 'Nomor Surat', 'Contract Name', 'Client', 'Jenis Kontrak', 'Tahun I', 'Tahun II', 'Jumlah', 'PPN (11%)', 'Total', 'PR', 'PO', 'TTB', 'Berita Acara', 'Keterangan', 'Status Pembayaran', 'Start Date', 'End Date', 'Status', 'Created At']);

                    foreach ($contracts as $c) {
                        $termLabel = ['termin_1' => 'Termin I', 'termin_2' => 'Termin II', 'termin_3' => 'Termin III', 'termin_4' => 'Termin IV'][$c->contract_term] ?? $c->contract_term;
                        $ketFull = trim(($termLabel ? "[{$termLabel}] " : '') . ($c->payment_date ? "Tgl: " . $c->payment_date->format('d/m/Y') . " " : '') . ($c->payment_status ? "[{$c->payment_status}] " : '') . ($c->keterangan ?? ''));
                        fputcsv($out, [
                            $c->contract_number,
                            $c->nomor_surat ?? '',
                            $c->contract_name,
                            optional($c->client)->name ?? '',
                            config('contract_types.types.' . $c->contract_type . '.name', ''),
                            $c->tahun_1 ?? '',
                            $c->tahun_2 ?? '',
                            $c->jumlah ?? '',
                            $c->ppn ?? '',
                            $c->total ?? '',
                            $c->pr ?? '',
                            $c->po ?? '',
                            $c->ttb ?? '',
                            $c->berita_acara ?? '',
                            $ketFull ?: ($c->keterangan ?? ''),
                            $c->payment_status ?? 'BELUM BAYAR',
                            $c->start_date ? $c->start_date->format('Y-m-d') : '',
                            $c->end_date ? $c->end_date->format('Y-m-d') : '',
                            $c->status,
                            $c->created_at ? $c->created_at->format('Y-m-d H:i:s') : '',
                        ]);
                    }

                    fclose($out);
                };

                return response()->stream($callback, 200, $headers);
            }

            // PDF (requires barryvdh/laravel-dompdf)
            if ($type === 'pdf') {
                $contracts = $query->get();

                // Prefer facade, but also allow using Dompdf directly
                if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class) || class_exists(\Dompdf\Dompdf::class)) {
                    try {
                        // Use the facade when available (it bootstraps the package in Laravel)
                        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('contracts.export_pdf', compact('contracts'));
                        } else {
                            // Direct Dompdf usage as a last resort
                            $html = view('contracts.export_pdf', compact('contracts'))->render();
                            $dompdf = new \Dompdf\Dompdf();
                            $dompdf->loadHtml($html);
                            $dompdf->render();
                            $pdf = $dompdf;
                        }

                        $filename = 'contracts_' . now()->format('Ymd_His') . '.pdf';

                        // If facade - use its download() helper, otherwise stream the generated PDF
                        if (isset($pdf) && method_exists($pdf, 'download')) {
                            return $pdf->download($filename);
                        }

                        $output = $pdf->output();
                        return response($output, 200, [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                        ]);
                    } catch (\Throwable $e) {
                        Log::error('PDF generation failed: ' . $e->getMessage(), [
                            'trace' => $e->getTraceAsString()
                        ]);

                        return response()->json([
                            'error' => 'PDF generation failed on the server. Check logs.'
                        ], 500);
                    }
                }

                // Fallback: return HTML for manual PDF printing if package not installed
                return response()->view('contracts.export_pdf', compact('contracts'))
                    ->header('Content-Type', 'text/html');
            }

            // default: JSON
            $contracts = $query->get();
            return response()->json($contracts);
        } catch (\Exception $e) {
            Log::error('Gagal export kontrak: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    /**
     * Debug: export and save result to storage (local env only)
     *
     * GET /_debug/export/save/{type}
     */
    public function exportAndSave(Request $request)
    {
        // Restrict to local environment for safety
        if (!app()->environment('local')) {
            return response()->json(['error' => 'Not allowed in this environment'], 403);
        }

        $type = $request->route('type') ?? $request->get('type', 'excel');

        // Build a request to reuse existing export logic
        $exportRequest = Request::create('/contracts/export', 'GET', array_merge($request->all(), ['type' => $type]));

        // Call export; it may return a StreamedResponse or BinaryFileResponse
        $response = $this->export($exportRequest);

        $storageDir = storage_path('app/exports');
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        // Binary file (temporary file path available)
        if ($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
            $file = $response->getFile();
            $filePath = $file->getRealPath();
            $cd = $response->headers->get('Content-Disposition');
            $filename = $this->extractFilenameFromContentDisposition($cd) ?? ($type . '_' . now()->format('Ymd_His'));
            $dest = $storageDir . DIRECTORY_SEPARATOR . $filename;
            copy($filePath, $dest);

            return response()->json([
                'saved' => true,
                'path' => 'storage/app/exports/' . $filename,
                'size' => filesize($dest)
            ]);
        }

        // Streamed response (CSV fallback)
        if ($response instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
            ob_start();
            $response->sendContent();
            $content = ob_get_clean();
            $cd = $response->headers->get('Content-Disposition');
            $filename = $this->extractFilenameFromContentDisposition($cd) ?? ($type . '_' . now()->format('Ymd_His'));
            $dest = $storageDir . DIRECTORY_SEPARATOR . $filename;
            file_put_contents($dest, $content);

            return response()->json([
                'saved' => true,
                'path' => 'storage/app/exports/' . $filename,
                'size' => filesize($dest)
            ]);
        }

        // Default: content available via getContent()
        $content = method_exists($response, 'getContent') ? $response->getContent() : null;
        $filename = $type . '_' . now()->format('Ymd_His') . '.txt';
        $dest = $storageDir . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($dest, $content ?? '');

        return response()->json([
            'saved' => true,
            'path' => 'storage/app/exports/' . $filename,
            'size' => filesize($dest)
        ]);
    }

    /**
     * Renew / extend a contract's end date via AJAX
     */
    public function renew(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'new_end_date' => 'nullable|date',
            'extend_days' => 'nullable|integer|min:1',
            'note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $originalEnd = $contract->end_date ? $contract->end_date->copy() : null;


            $oldEnd = $contract->end_date ? $contract->end_date->toDateString() : null;

            if (!empty($validated['new_end_date'])) {
                $newEnd = \Carbon\Carbon::parse($validated['new_end_date'])->startOfDay();

                // validate new end date is after start_date
                if ($newEnd->lt($contract->start_date->startOfDay())) {
                    return response()->json(['success' => false, 'message' => 'Tanggal berakhir baru harus setelah tanggal mulai kontrak.'], 422);
                }

                // ensure we are actually extending (new end after current end)
                if ($contract->end_date && !$newEnd->gt($contract->end_date->startOfDay())) {
                    return response()->json(['success' => false, 'message' => 'Tanggal berakhir baru harus lebih akhir dari tanggal berakhir saat ini.'], 422);
                }

                $contract->end_date = $newEnd;
            } elseif (!empty($validated['extend_days'])) {
                $days = intval($validated['extend_days']);
                if ($days <= 0) {
                    return response()->json(['success' => false, 'message' => 'Jumlah hari harus positif.'], 422);
                }

                $contract->end_date = $contract->end_date->copy()->addDays($days);
            } else {
                return response()->json(['success' => false, 'message' => 'Tidak ada perubahan yang diberikan.'], 422);
            }

            // If contract was expired or in grace but new end date is in the future, restore to active
            if (in_array($contract->status, ['expired', 'grace_period'])) {
                if ($contract->end_date->isFuture() || $contract->end_date->isToday()) {
                    $contract->status = 'active';
                    $contract->actual_end_date = null;
                    $contract->termination_reason = null;
                }
            }

            $contract->save();

            DB::commit();

            // Persist structured audit entry for this renewal
            try {
                ContractAudit::create([
                    'contract_id' => $contract->id,
                    'user_id' => Auth::id(),
                    'action' => 'renew',
                    'old_values' => ['end_date' => $oldEnd],
                    'new_values' => ['end_date' => $contract->end_date ? $contract->end_date->toDateString() : null],
                    'note' => $validated['note'] ?? null,
                    'ip' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to persist contract audit: ' . $e->getMessage(), ['contract_id' => $contract->id]);
            }

            // Audit log (fallback / quick tracing)
            Log::info('Contract renewed', [
                'user_id' => Auth::id(),
                'contract_id' => $contract->id,
                'old_end_date' => $oldEnd,
                'new_end_date' => $contract->end_date ? $contract->end_date->toDateString() : null,
                'note' => $validated['note'] ?? null,
                'ip' => $request->ip(),
            ]);

            // Create a renewal notification record and dispatch WhatsApp if configured
            try {
                ContractNotification::createRenewalNotification($contract, $oldEnd, $contract->end_date ? $contract->end_date->toDateString() : null, $validated['note'] ?? null);
            } catch (\Exception $e) {
                Log::warning('Failed to create renewal notification: ' . $e->getMessage(), ['contract_id' => $contract->id]);
            }

            // calculate days remaining for response
            $daysRemaining = $contract->end_date->isPast() ? 0 : now()->diffInDays($contract->end_date);

            return response()->json([
                'success' => true,
                'message' => 'Perpanjangan kontrak berhasil disimpan.',
                'contract' => [
                    'end_date' => $contract->end_date->toDateString(),
                    'days_remaining' => $daysRemaining,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperpanjang kontrak: ' . $e->getMessage(), ['contract_id' => $contract->id, 'request' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Gagal memperpanjang kontrak: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper: extract filename from Content-Disposition header
     */
    private function extractFilenameFromContentDisposition(?string $cd): ?string
    {
        if (!$cd) {
            return null;
        }

        if (preg_match('/filename=\"?([^\";]+)\"?/', $cd, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    /**
     * Dashboard statistics.
     */
    public function statistics()
    {
        // Gunakan single query untuk semua count
        $stats = Contract::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = "grace_period" THEN 1 ELSE 0 END) as in_grace_period,
            SUM(CASE WHEN status = "expired" THEN 1 ELSE 0 END) as expired,
            SUM(CASE WHEN status = "terminated" THEN 1 ELSE 0 END) as terminated
        ')->first();

        // Hitung kontrak yang akan segera berakhir (30 hari ke depan)
        $expiringSoon = Contract::where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->count();

        return response()->json([
            'total' => $stats->total ?? 0,
            'active' => $stats->active ?? 0,
            'in_grace_period' => $stats->in_grace_period ?? 0,
            'expired' => $stats->expired ?? 0,
            'terminated' => $stats->terminated ?? 0,
            'expiring_soon' => $expiringSoon,
        ]);
    }

    /**
     * Get contracts by client ID (for API).
     */
    public function getByClient(Client $client)
    {
        $contracts = $client->contracts()
            ->with(['creator:id,name'])
            ->orderBy('created_at', 'desc')
            ->get([
                'id',
                'contract_number',
                'nomor_surat',
                'contract_name',
                'status',
                'start_date',
                'end_date',
                'created_at'
            ]);

        return response()->json($contracts);
    }

    /**
     * Send reminder to client via WhatsApp (WhatsApp Cloud API)
     */
    public function sendReminder(Request $request, Contract $contract)
    {
        $client = $contract->client;

        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Client tidak ditemukan.'], 404);
        }

        $to = $client->pic_phone ?? $client->phone ?? null;

        if (!$to) {
            return response()->json(['success' => false, 'message' => 'Nomor telepon klien tidak tersedia.'], 400);
        }

        $message = sprintf(
            "Reminder: Kontrak %s (%s) akan berakhir pada %s. Mohon tindak lanjut.",
            $contract->contract_number,
            $contract->contract_name,
            $contract->end_date ? $contract->end_date->format('d/m/Y') : '-'
        );

        $phoneId = config('services.whatsapp.phone_number_id') ?? env('WHATSAPP_PHONE_NUMBER_ID');
        $accessToken = config('services.whatsapp.access_token') ?? env('WHATSAPP_ACCESS_TOKEN');
        $apiUrl = config('services.whatsapp.api_url') ?? env('WHATSAPP_API_URL', 'https://graph.facebook.com');

        if (empty($phoneId) && empty($apiUrl) && empty($accessToken)) {
            return response()->json(['success' => false, 'message' => 'WhatsApp configuration is not set.'], 500);
        }

        try {
            $notification = $this->createReminderNotification($contract, $message, ['manual' => true, 'queued' => true]);

            $toClean = preg_replace('/[^0-9]/', '', $to);
            $uuid = (string) Str::uuid();

            SendWhatsAppMessage::dispatch($toClean, 'text', ['text' => $message], $contract->id, $uuid)->onQueue('whatsapp');

            $emailResult = $this->dispatchReminderEmail($contract, $notification, $message);
            $responseMessage = 'Reminder Kontrak berhasil dikirim.';

            if (!empty($emailResult['warning'])) {
                $responseMessage .= ' ' . $emailResult['warning'];
            } elseif (!empty($emailResult['success'])) {
                $responseMessage .= ' ' . $emailResult['success'];
            }

            return response()->json([
                'success' => true,
                'message' => $responseMessage,
                'notification' => $notification
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengantri pengiriman reminder: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Gagal mengantri pengiriman reminder: ' . $e->getMessage()], 500);
        }
    }


    private function buildReminderEmailBody(string $message, Contract $contract): string
    {
        $signed = URL::temporarySignedRoute('contracts.download-pdf.scan', now()->addHours(6), ['contract' => $contract->id]);

        return sprintf(
            "<p>%s</p><p>Download dokumen kontrak: <a href=\"%s\">%s</a></p>",
            e($message),
            $signed,
            $signed
        );
    }

    private function dispatchReminderEmail(Contract $contract, ?ContractNotification $notification, string $message): array
    {
        $clientEmail = $contract->client->email ?? null;
        if (!$clientEmail) {
            return ['warning' => ''];
        }

        $emailSubject = sprintf('Reminder: Kontrak %s akan berakhir', $contract->contract_number);
        $emailBody = $this->buildReminderEmailBody($message, $contract);

        if ($this->isSmtpConfigured()) {
            if ($this->isGmailSmtp() && !$this->isGmailAppPasswordConfigured()) {
                return ['warning' => ''];
            }

            Mail::to($clientEmail)->queue(new ReminderMail($emailSubject, $emailBody));
            return ['success' => ''];
        }

        return ['warning' => ''];
    }

    private function createReminderNotification(Contract $contract, string $message, array $data = []): ?ContractNotification
    {
        try {
            return ContractNotification::create([
                'contract_id' => $contract->id,
                'type' => ContractNotification::TYPE_EXPIRY,
                'message' => $message,
                'is_read' => false,
                'data' => array_merge($data, ['uuid' => $data['uuid'] ?? (string) Str::uuid()]),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to persist ContractNotification before queuing send', ['error' => $e->getMessage(), 'contract_id' => $contract->id]);
            return null;
        }
    }

    private function isSmtpConfigured(): bool
    {
        $mailer = env('MAIL_MAILER', config('mail.default'));
        $host = env('MAIL_HOST');
        $username = env('MAIL_USERNAME');
        $password = env('MAIL_PASSWORD');

        return $mailer === 'smtp' && !empty($host) && !empty($username) && !empty($password);
    }

    private function isGmailSmtp(): bool
    {
        $host = env('MAIL_HOST', '');
        return $this->isSmtpConfigured() && stripos($host, 'smtp.gmail.com') !== false;
    }

    private function isGmailAppPasswordConfigured(): bool
    {
        $password = str_replace(' ', '', env('MAIL_PASSWORD', ''));
        return strlen($password) === 16;
    }

    /**
     * Send reminder via Gmail API (OAuth2 access token flow).
     *
     * Uses the Gmail REST API:
     *   POST https://www.googleapis.com/gmail/v1/users/me/messages/send
     * with a raw RFC-2822 message encoded in base64url.
     *
     * Requires env vars: GMAIL_CLIENT_ID, GMAIL_CLIENT_SECRET, GMAIL_REFRESH_TOKEN
     */
    /**
     * Send reminder via Gmail API (OAuth2 access token flow) or SMTP fallback.
     *
     * Primary: Gmail REST API (POST https://www.googleapis.com/gmail/v1/users/me/messages/send)
     * Fallback: Laravel Mail (SMTP)
     */
    public function sendGmailReminder(Request $request, Contract $contract)
    {
        $client = $contract->client;

        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Client tidak ditemukan.'], 404);
        }

        $toEmail = $client->pic_email ?? $client->email ?? null;

        if (!$toEmail) {
            return response()->json(['success' => false, 'message' => 'Alamat email client tidak tersedia.'], 400);
        }

        $subject = sprintf('Reminder: Kontrak %s akan berakhir', $contract->contract_number);

        $reminderMessage = sprintf(
            "Reminder: Kontrak %s (%s) akan berakhir pada %s. Mohon tindak lanjut.",
            $contract->contract_number,
            $contract->contract_name,
            $contract->end_date ? $contract->end_date->format('d/m/Y') : '-'
        );

        $downloadLink = URL::temporarySignedRoute(
            'contracts.download-pdf.scan',
            now()->addHours(6),
            ['contract' => $contract->id]
        );

        $htmlBody = sprintf(
            '<p>%s</p><p>Download dokumen kontrak: <a href="%s">Klik di sini</a></p>' .
            '<p>Detail kontrak:</p><ul>' .
            '<li>Nomor Kontrak: %s</li>' .
            '<li>Nama Kontrak: %s</li>' .
            '<li>Tanggal Berakhir: %s</li>' .
            '<li>Status: %s</li>' .
            '</ul>',
            e($reminderMessage),
            $downloadLink,
            e($contract->contract_number),
            e($contract->contract_name),
            $contract->end_date ? $contract->end_date->format('d F Y') : '-',
            e(ucfirst(str_replace('_', ' ', $contract->status)))
        );

        // 1. Coba kirim via Gmail REST API jika kredensial OAuth tersedia
        $accessToken = \App\Services\GmailService::getAccessToken();

        if ($accessToken) {
            try {
                $creds       = \App\Services\GmailService::getCredentials();
                $fromAddress = $creds['from_email'] ?: env('MAIL_FROM_ADDRESS', 'noreply@example.com');
                $fromName    = $creds['from_name'] ?: env('MAIL_FROM_NAME', 'Pelindo E-Contract');

                $boundary  = '----=_NextPart_' . md5(uniqid((string) rand(), true));
                $rawEmail  = "From: {$fromName} <{$fromAddress}>\r\n";
                $rawEmail .= "To: {$toEmail}\r\n";
                $rawEmail .= "Subject: {$subject}\r\n";
                $rawEmail .= "MIME-Version: 1.0\r\n";
                $rawEmail .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n\r\n";
                $rawEmail .= "--{$boundary}\r\n";
                $rawEmail .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
                $rawEmail .= strip_tags($reminderMessage) . "\r\n\r\nDownload: {$downloadLink}\r\n\r\n";
                $rawEmail .= "--{$boundary}\r\n";
                $rawEmail .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
                $rawEmail .= $htmlBody . "\r\n\r\n";
                $rawEmail .= "--{$boundary}--";

                $encodedMessage = \App\Services\GmailService::encodeBase64Url($rawEmail);

                $sendResponse = Http::withToken($accessToken)
                    ->timeout(20)
                    ->post('https://www.googleapis.com/gmail/v1/users/me/messages/send', [
                        'raw' => $encodedMessage,
                    ]);

                if ($sendResponse->successful()) {
                    $this->createReminderNotification($contract, $reminderMessage, [
                        'channel'      => 'gmail_api',
                        'to'           => $toEmail,
                        'manual'       => true,
                        'gmail_msg_id' => $sendResponse->json('id'),
                    ]);

                    Log::info('Gmail reminder sent successfully via Gmail API', [
                        'contract_id'  => $contract->id,
                        'to'           => $toEmail,
                        'gmail_msg_id' => $sendResponse->json('id'),
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => "Reminder berhasil dikirim via Gmail API ke {$toEmail}.",
                    ]);
                }

                Log::warning('Gmail API send gagal, mencoba fallback SMTP', ['response' => $sendResponse->body()]);
            } catch (\Throwable $e) {
                Log::warning('Gmail API send exception: ' . $e->getMessage() . ', mencoba fallback SMTP');
            }
        }

        // 2. Fallback: Kirim via Laravel Mail (SMTP) jika Gmail API belum diotorisasi
        if ($this->isSmtpConfigured()) {
            try {
                Mail::to($toEmail)->send(new ReminderMail($subject, $htmlBody));

                $this->createReminderNotification($contract, $reminderMessage, [
                    'channel' => 'smtp_fallback',
                    'to'      => $toEmail,
                    'manual'  => true,
                ]);

                Log::info('Gmail reminder sent via SMTP fallback', [
                    'contract_id' => $contract->id,
                    'to'          => $toEmail,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Reminder berhasil dikirim ke {$toEmail} (menggunakan SMTP Gmail).",
                ]);
            } catch (\Throwable $smtpError) {
                Log::error('SMTP fallback failed: ' . $smtpError->getMessage());
            }
        }

        // 3. Jika kedua metode belum dikonfigurasi
        return response()->json([
            'success'      => false,
            'message'      => 'Konfigurasi Gmail API belum lengkap. Harap atur GMAIL_CLIENT_ID, GMAIL_CLIENT_SECRET, dan lakukan otorisasi di menu Pengaturan > Gmail API.',
            'settings_url' => route('settings.index') . '#gmail',
        ], 503);
    }

    public function checkContractNumber(Request $request)
    {
        $request->validate([
            'contract_number' => 'required|string',
            'except_id' => 'nullable|exists:contracts,id'
        ]);
        $query = Contract::where('contract_number', $request->contract_number);

        if ($request->has('except_id')) {
            $query->where('id', '!=', $request->except_id);
        }

        $exists = $query->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Nomor kontrak sudah digunakan.' : 'Nomor kontrak tersedia.'
        ]);
    }
}
