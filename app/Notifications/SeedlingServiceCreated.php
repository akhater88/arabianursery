<?php

namespace App\Notifications;

use App\Enums\SeedlingServiceStatuses;
use App\Models\SeedlingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SeedlingServiceCreated extends Notification
{
    use Queueable;

    private $seedlingService;

    /**
     * Create a new notification instance.
     */
    public function __construct(SeedlingService $seedlingService)
    {
        $this->seedlingService = $seedlingService;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $title = 'تم اضافة اشتال ' . $this->seedlingService->seedType->name.' - '. $this->seedlingService->seed_class;
        if($this->seedlingService->status == SeedlingServiceStatuses::DELIVERED){
            $title = 'تم تشتيل اشتال' . $this->seedlingService->name;
        }
        return [
            "id" => $this->seedlingService->id,
            "title" => $title,
            "description" => 'عدد الصواني'. $this->seedlingService->tray_count . ' سعر التشتيل للصنية '. $this->seedlingService->price_per_tray,
            "image" => "",
            "created_at" => $this->seedlingService->created_at,
            "updated_at" => $this->seedlingService->updated_at,

        ];
    }
}
