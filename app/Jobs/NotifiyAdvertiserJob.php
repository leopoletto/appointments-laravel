<?php

namespace App\Jobs;

use App\Repositories\Notification\NotificationRepositoryContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifiyAdvertiserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationRepository;
    protected $type;
    protected $message;
    protected $advertiserUuid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        string $advertiserUuid,
        string $type,
        string $message
    ) {
        $this->advertiserUuid = $advertiserUuid;
        $this->type = $type;
        $this->message = $message;
        $this->notificationRepository = resolve(NotificationRepositoryContract::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->notificationRepository->insertNotification(
            $this->advertiserUuid,
            $this->type,
            $this->message
        );
    }
}
