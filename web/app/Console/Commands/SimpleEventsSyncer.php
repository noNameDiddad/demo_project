<?php

namespace App\Console\Commands;

use App\Connectors\SimpleConnector;
use App\Services\EventObserverService;
use App\Services\SimpleConnectorService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class SimpleEventsSyncer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:events {--env=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param SimpleConnectorService $service
     * @return int
     */
    public function handle(SimpleConnectorService $service)
    {
        if ($this->option('env') === null) {
            $this->info('Укажите опцию в виде "--env"');
            return 0;
        }
        if (!in_array($this->option('env'), SimpleConnector::LISTENER_ENV)) {
            $this->info('Данной опции не существует!');
            return 0;
        }
        $service->setEnv($this->option('env'));
        $this->info("Слушатель запущен по адресу: " . $service->connector->serviceUrl);
        while (true) {
            try {
                $events = $service->getEvents();
                if (!empty($events)) {
                    $service->confirmEvents(EventObserverService::handleEvents($events));
                }
            } catch (Exception|Throwable $exception) {
                Log::error($exception->getMessage());
            }

        }
    }
}
