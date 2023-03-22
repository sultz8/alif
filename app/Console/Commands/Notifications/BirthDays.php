<?php

namespace App\Console\Commands\Notifications;

use App\Mail\BirthDayMail;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class BirthDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:birthday {--id=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправить оповещение о днях рождениях';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ids = $this->option('id');
        $contacts = !empty($ids) ? Contact::find($ids) : Contact::all();
        $bar = $this->getOutput()->createProgressBar($contacts->count());

        $bar->start();

        foreach ($contacts as $contact) {

            if ($contact->birth_day === Carbon::now()->format('Y-m-d')) {
                Mail::to($contact->user->email)
                    ->send(new BirthDayMail([
                        'title' => __('notifications.title.birth_day', ['name' => $contact->full_name]),
                        'body' => __('notifications.body.birth_day')
                    ]));
            }

            $bar->advance();
        }

        $bar->finish();

        return Command::SUCCESS;
    }
}
