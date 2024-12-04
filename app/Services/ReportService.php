<?php

namespace App\Services;

use App\Repositories\ReportRepository;

class ReportService
{
    protected $reportRepository;
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }
    public function getReports($userId, $startDate, $endDate, $walletId)
    {
        return $this->reportRepository->getReports($userId, $startDate, $endDate, $walletId);
    }

    public function getCategoryReports($userId, $startDate, $endDate, $categoryId)
    {
        return $this->reportRepository->getCategoryReports($userId, $startDate, $endDate, $categoryId);
    }
}
