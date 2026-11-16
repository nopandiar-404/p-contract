<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

class SaveExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:save {type=excel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call /contracts/export?type={type} and save the file to storage/app/exports for verification';

    public function handle()
    {
        $type = $this->argument('type') ?? 'excel';

        $request = Request::create('/contracts/export', 'GET', ['type' => $type]);

        $controller = app(\App\Http\Controllers\ContractController::class);

        try {
            $response = $controller->export($request);

            $storageDir = storage_path('app/exports');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            if ($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
                $file = $response->getFile();
                $filePath = $file->getRealPath();
                $cd = $response->headers->get('Content-Disposition');
                $filename = 'export_' . $type . '_' . now()->format('Ymd_His');
                if ($cd && preg_match('/filename=\"?([^\";]+)\"?/', $cd, $m)) {
                    $filename = $m[1];
                }
                $dest = $storageDir . DIRECTORY_SEPARATOR . $filename;
                copy($filePath, $dest);
                $this->info("Saved: {$dest} (" . filesize($dest) . " bytes)");
                return 0;
            }

            if ($response instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
                ob_start();
                $response->sendContent();
                $content = ob_get_clean();
                $cd = $response->headers->get('Content-Disposition');
                $filename = 'export_' . $type . '_' . now()->format('Ymd_His');
                if ($cd && preg_match('/filename=\"?([^\";]+)\"?/', $cd, $m)) {
                    $filename = $m[1];
                }
                $dest = $storageDir . DIRECTORY_SEPARATOR . $filename;
                file_put_contents($dest, $content);
                $this->info("Saved: {$dest} (" . filesize($dest) . " bytes)");
                return 0;
            }

            $content = method_exists($response, 'getContent') ? $response->getContent() : null;
            $filename = 'export_' . $type . '_' . now()->format('Ymd_His') . '.txt';
            $dest = $storageDir . DIRECTORY_SEPARATOR . $filename;
            file_put_contents($dest, $content ?? '');
            $this->info("Saved: {$dest} (" . filesize($dest) . " bytes)");
            return 0;

        } catch (\Exception $e) {
            $this->error('Export failed: ' . $e->getMessage());
            // Also persist to a dedicated log so CLI runs can be inspected later
            $logPath = storage_path('logs/export-cli-error.log');
            file_put_contents($logPath, '[' . now()->toDateTimeString() . "] Export failed: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n", FILE_APPEND);
            return 1;
        }
    }
}
