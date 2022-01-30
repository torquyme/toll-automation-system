<?php

namespace App\Console\Commands;

use App\Exceptions\PathNotFoundException;
use App\Services\InvoiceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * GenerateMonthlyUserInvoices
 */
class GenerateMonthlyUserInvoices extends Command
{
    protected $signature = "invoice:monthly-for-user {userId}";

    private InvoiceService $invoiceService;

    /**
     * @param InvoiceService $invoiceService
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;

        parent::__construct();
    }

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public function handle()
    {
        $data = Validator::validate(
            $this->arguments(),
            ['userId' => 'int|required|exists:users,id']
        );
        $userId = $data['userId'];

        $this->info("Calculating monthly bill for user $userId");

        $this->invoiceService->calculateMonthlyForUser($userId);
    }
}
