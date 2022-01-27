<?php

namespace App\Console\Commands;

use App\Services\InvoiceService;
use Illuminate\Console\Command;

class GenerateMonthlyInvoices extends Command
{
    protected $signature = "invoice:monthly";

    private InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;

        parent::__construct();
    }

    public function handle()
    {
        $this->info("Calculating monthly bill for all users");

        $this->invoiceService->calculateMonthlyForAllUsers();
    }
}
