<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Contract;

class GracePeriodEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $contract;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $contract = $this->contract;
        $remainingDays = $contract->remaining_grace_period_days;

        return (new MailMessage)
            ->subject("Peringatan: Kontrak {$contract->contract_number} Dalam Masa Tenggang {$remainingDays} Hari")
            ->greeting("Halo {$notifiable->name},")
            ->line("Kontrak berikut sedang dalam masa tenggang dan memerlukan perhatian segera:")
            ->line("**Nomor Kontrak:** {$contract->contract_number}")
            ->line("**Nama Kontrak:** {$contract->contract_name}")
            ->line("**Klien:** {$contract->client->name}")
            ->line("**Tanggal Berakhir:** {$contract->end_date->format('d M Y')}")
            ->line("**Sisa Hari Masa Tenggang:** {$remainingDays} hari")
            ->action('Lihat Detail Kontrak', route('contracts.show', $contract))
            ->line('Mohon segera ambil tindakan yang diperlukan untuk kontrak ini.')
            ->salutation('Salam, Sistem Manajemen Kontrak');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'contract_id' => $this->contract->id,
            'contract_number' => $this->contract->contract_number,
            'remaining_days' => $this->contract->remaining_grace_period_days,
        ];
    }
}