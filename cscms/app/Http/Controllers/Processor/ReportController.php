<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function application(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        $stats = $this->getApplicationStats($dateRange);
        $companiesByStatus = $this->getCompaniesByStatus($dateRange);
        $chartData = $this->getChartData($dateRange);
        $recentApplications = $this->getRecentApplications();

        Log::info('Final Stats in application method', $stats);
        return view('processor.reports.application', compact(
            'stats',
            'companiesByStatus',
            'chartData',
            'recentApplications',
            'dateRange'
        ));
    }

    private function getDateRange($request)
    {
        $period = $request->get('period', 'last_30_days');

        switch($period) {
            case 'last_7_days':
                return [
                    'start' => Carbon::now()->subDays(7)->startOfDay(),
                    'end' => Carbon::now()->endOfDay(),
                    'label' => 'Last 7 Days'
                ];
            case 'last_30_days':
                return [
                    'start' => Carbon::now()->subDays(30)->startOfDay(),
                    'end' => Carbon::now()->endOfDay(),
                    'label' => 'Last 30 Days'
                ];
            case 'this_month':
                return [
                    'start' => Carbon::now()->startOfMonth(),
                    'end' => Carbon::now()->endOfDay(),
                    'label' => 'This Month'
                ];
            case 'last_month':
                return [
                    'start' => Carbon::now()->subMonth()->startOfMonth(),
                    'end' => Carbon::now()->subMonth()->endOfMonth(),
                    'label' => 'Last Month'
                ];
            case 'this_year':
                return [
                    'start' => Carbon::now()->startOfYear(),
                    'end' => Carbon::now()->endOfDay(),
                    'label' => 'This Year'
                ];
            case 'custom':
                $start = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
                $end = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::now()->endOfDay();
                return [
                    'start' => $start,
                    'end' => $end,
                    'label' => 'Custom Range'
                ];
            default:
                return [
                    'start' => Carbon::now()->subDays(30)->startOfDay(),
                    'end' => Carbon::now()->endOfDay(),
                    'label' => 'Last 30 Days'
                ];
        }
    }

    private function getApplicationStats($dateRange)
    {
        $currentPeriod = Company::whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        Log::info('Date Range', [
            'start' => $dateRange['start']->toDateTimeString(),
            'end' => $dateRange['end']->toDateTimeString(),
            'label' => $dateRange['label']
        ]);
        Log::info('Companies Count: ' . $currentPeriod->count());

        // Calculate averages first
        $avgFinancialRisk = $currentPeriod->avg('financial_risk_rating') ?? 0;
        $avgReputationalRisk = $currentPeriod->avg('reputational_risk_rating') ?? 0;
        $avgComplianceRisk = $currentPeriod->avg('compliance_risk_rating') ?? 0;

        Log::info('Initial Financial Risk Avg: ' . $avgFinancialRisk);
        Log::info('Initial Reputational Risk Avg: ' . $avgReputationalRisk);
        Log::info('Initial Compliance Risk Avg: ' . $avgComplianceRisk);

        // Current period stats (use fresh queries to avoid modifying $currentPeriod)
        $totalApplications = $currentPeriod->count();
        $acceptedApplications = Company::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                                      ->where('acceptance_status', 'accepted')->count();
        $pendingApplications = Company::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                                      ->where('acceptance_status', 'pending')->count();
        $rejectedApplications = Company::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                                       ->where('acceptance_status', 'rejected')->count();
        $visitScheduledApplications = Company::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                                            ->where('acceptance_status', 'visit_scheduled')->count();

        // Previous period stats
        $previousStart = $dateRange['start']->copy()->subDays($dateRange['start']->diffInDays($dateRange['end']));
        $previousPeriod = Company::whereBetween('created_at', [$previousStart, $dateRange['start']]);
        $prevTotalApplications = $previousPeriod->count();
        $prevAcceptedApplications = $previousPeriod->where('acceptance_status', 'accepted')->count();
        $prevPendingApplications = $previousPeriod->where('acceptance_status', 'pending')->count();
        $prevRejectedApplications = $previousPeriod->where('acceptance_status', 'rejected')->count();
        $prevVisitScheduledApplications = $previousPeriod->where('acceptance_status', 'visit_scheduled')->count();

        // Calculate acceptance rate
        $acceptanceRate = $totalApplications > 0 ? ($acceptedApplications / $totalApplications) * 100 : 0;
        $prevAcceptanceRate = $prevTotalApplications > 0 ? ($prevAcceptedApplications / $prevTotalApplications) * 100 : 0;

        // Log averages before return
        Log::info('Final Averages Before Return', [
            'financial' => $avgFinancialRisk,
            'reputational' => $avgReputationalRisk,
            'compliance' => $avgComplianceRisk
        ]);

        $stats = [
            'total_applications' => [
                'value' => $totalApplications,
                'change' => $this->calculatePercentageChange($totalApplications, $prevTotalApplications),
                'icon' => 'fas fa-file-alt',
                'color' => 'var(--info)'
            ],
            'accepted_applications' => [
                'value' => $acceptedApplications,
                'change' => $this->calculatePercentageChange($acceptedApplications, $prevAcceptedApplications),
                'icon' => 'fas fa-check-circle',
                'color' => 'var(--success)'
            ],
            'pending_applications' => [
                'value' => $pendingApplications,
                'change' => $this->calculatePercentageChange($pendingApplications, $prevPendingApplications),
                'icon' => 'fas fa-clock',
                'color' => 'var(--warning)'
            ],
            'rejected_applications' => [
                'value' => $rejectedApplications,
                'change' => $this->calculatePercentageChange($rejectedApplications, $prevRejectedApplications),
                'icon' => 'fas fa-times-circle',
                'color' => 'var(--danger)'
            ],
            'visit_scheduled' => [
                'value' => $visitScheduledApplications,
            'change' => $this->calculatePercentageChange($visitScheduledApplications, $prevVisitScheduledApplications),
                'icon' => 'fas fa-calendar-check',
                'color' => 'var(--info)'
            ],
            'acceptance_rate' => [
                'value' => $acceptanceRate,
                'change' => $this->calculatePercentageChange($acceptanceRate, $prevAcceptanceRate),
                'icon' => 'fas fa-percentage',
                'color' => 'var(--coffee-medium)'
            ],
            'avg_financial_risk' => [
                'value' => $avgFinancialRisk,
                'icon' => 'fas fa-chart-line',
                'color' => 'var(--coffee-light)'
            ],
            'avg_reputational_risk' => [
                'value' => $avgReputationalRisk,
                'icon' => 'fas fa-star',
                'color' => 'var(--accent)'
            ],
            'avg_compliance_risk' => [
                'value' => $avgComplianceRisk,
                'icon' => 'fas fa-balance-scale',
                'color' => 'var(--info)'
            ]
        ];

        Log::info('Stats Array', $stats);
        return $stats;
    }

    private function getCompaniesByStatus($dateRange)
    {
        $statuses = ['pending', 'accepted', 'rejected', 'visit_scheduled'];
        $companiesByStatus = [];

        foreach ($statuses as $status) {
            $companies = Company::where('acceptance_status', $status)
                               ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                               ->orderBy('created_at', 'desc')
                               ->get();

            $companiesByStatus[$status] = [
                'companies' => $companies,
                'count' => $companies->count()
            ];
        }

        return $companiesByStatus;
    }

    private function getChartData($dateRange)
    {
        $applicationsTrend = Company::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                  ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                                  ->groupBy('date')
                                  ->orderBy('date')
                                  ->get()
                                  ->pluck('count', 'date');

        $applicationsByType = Company::selectRaw('company_type, COUNT(*) as count')
                                   ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                                   ->groupBy('company_type')
                                   ->get()
                                   ->pluck('count', 'company_type');

        $applicationsByStatus = Company::selectRaw('acceptance_status, COUNT(*) as count')
                                     ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                                     ->groupBy('acceptance_status')
                                     ->get()
                                     ->pluck('count', 'acceptance_status');

        return [
            'applications_trend' => $applicationsTrend,
            'applications_by_type' => $applicationsByType,
            'applications_by_status' => $applicationsByStatus
        ];
    }

    private function getRecentApplications()
    {
        return Company::latest()
                     ->take(10)
                     ->get();
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
