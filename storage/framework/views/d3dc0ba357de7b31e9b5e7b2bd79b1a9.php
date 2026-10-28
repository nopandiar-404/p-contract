<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Masa Tenggang Kontrak</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f8f9fa; }
        .contract-info { background-color: white; padding: 15px; margin: 15px 0; border-left: 4px solid #dc3545; }
        .button { display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Peringatan Masa Tenggang Kontrak</h1>
        </div>

        <div class="content">
            <p>Halo,</p>

            <p>Kontrak berikut sedang dalam masa tenggang dan memerlukan perhatian segera:</p>

            <div class="contract-info">
                <h3>Detail Kontrak</h3>
                <p><strong>Nomor Kontrak:</strong> <?php echo e($contract->contract_number); ?></p>
                <p><strong>Nama Kontrak:</strong> <?php echo e($contract->contract_name); ?></p>
                <p><strong>Klien:</strong> <?php echo e($contract->client->name); ?></p>
                <p><strong>Tanggal Berakhir:</strong> <?php echo e($contract->end_date->format('d M Y')); ?></p>
                <p><strong>Sisa Hari Masa Tenggang:</strong> <span style="color: #dc3545; font-weight: bold;"><?php echo e($remainingDays); ?> hari</span></p>
            </div>

            <p>Mohon segera ambil tindakan yang diperlukan untuk kontrak ini sebelum masa tenggang berakhir.</p>

            <a href="<?php echo e(route('contracts.show', $contract)); ?>" class="button">Lihat Detail Kontrak</a>

            <p>Jika Anda memiliki pertanyaan, silakan hubungi tim administrasi.</p>

            <p>Salam,<br>
            Sistem Manajemen Kontrak</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem manajemen kontrak.<br>
            Jangan balas email ini.</p>
        </div>
    </div>
</body>
</html><?php /**PATH D:\DASHBOARD\p-contract\resources\views\emails\grace_period.blade.php ENDPATH**/ ?>