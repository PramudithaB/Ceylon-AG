<?php

namespace App\Repositories;

use App\Models\User;

interface ReportRepositoryInterface
{
    public function getSummaryMetrics(?User $client = null, array $filters = []): array;
    public function getMonthlySalesTrend(?User $client = null, array $filters = []): array;
    public function getMonthlyPaymentsTrend(?User $client = null, array $filters = []): array;
    public function getTopClients(int $limit = 5, array $filters = []): array;
    public function getInventoryBreakdown(?User $client = null, array $filters = []): array;
    public function getDetailedReportData(?User $client = null, array $filters = []): array;
}
