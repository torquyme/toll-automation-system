<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\DeviceService;
use App\Services\InvoiceService;
use App\Services\PathService;
use App\Types\StationLogStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class GenerateMonthlyUserInvoices extends Command
{
    protected $signature = "invoice:monthly-for-user {userId}";

    private InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;

        parent::__construct();
    }

    public function handle()
    {
        $data = Validator::validate(
            $this->arguments(),
            ['userId' => 'int|required|exists:users,id']
        );
        $userId = $data['userId'];

        $this->info("Calculating monthly bill for user {$userId}");

        $this->invoiceService->calculateMonthlyForUser($userId);

        $this->info(json_encode($completeRoute, JSON_PRETTY_PRINT));
    }
}
