<?php

namespace App\Livewire\Logs;

use App\Models\SystemLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SystemLogList extends Component
{
    use WithPagination;

    public $type = '';
    public $action = '';
    public $userId = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $search = '';
    public $perPage = 15;
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingType() { $this->resetPage(); }
    public function updatingAction() { $this->resetPage(); }
    public function updatingUserId() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }

    public function exportCsv(): StreamedResponse
    {
        $filename = 'system_logs_'.now()->format('Ymd_His').'.csv';
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                __('logs.table.id'), __('logs.table.date'), __('logs.table.type'), __('logs.table.action'), __('logs.table.user'), __('logs.table.route'), __('logs.table.method'), __('logs.table.ip'), __('logs.table.model'), __('logs.table.model_id'), 'Locale', 'Message'
            ]);
            $this->buildQuery()->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->id,
                        optional($row->created_at)->format('Y-m-d H:i:s'),
                        __('logs.types.' . $row->type),
                        __('logs.actions.' . $row->action),
                        optional($row->user)->name ?? optional($row->user)->username ?? $row->user_id,
                        $row->route,
                        $row->method,
                        $row->ip,
                        $row->model_type,
                        $row->model_id,
                        $row->locale,
                        $row->message,
                    ]);
                }
            });
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportExcel(): StreamedResponse
    {
        // Excel-friendly CSV (Excel يفتح CSV مباشرة)
        $filename = 'system_logs_'.now()->format('Ymd_His').'.xls';
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                __('logs.table.id'), __('logs.table.date'), __('logs.table.type'), __('logs.table.action'), __('logs.table.user'), __('logs.table.route'), __('logs.table.method'), __('logs.table.ip'), __('logs.table.model'), __('logs.table.model_id'), 'Locale', 'Message'
            ]);
            $this->buildQuery()->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->id,
                        optional($row->created_at)->format('Y-m-d H:i:s'),
                        __('logs.types.' . $row->type),
                        __('logs.actions.' . $row->action),
                        optional($row->user)->name ?? optional($row->user)->username ?? $row->user_id,
                        $row->route,
                        $row->method,
                        $row->ip,
                        $row->model_type,
                        $row->model_id,
                        $row->locale,
                        $row->message,
                    ]);
                }
            });
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);
    }

    protected function buildQuery()
    {
        return SystemLog::query()
            ->with('user')
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->action, fn($q) => $q->where('action', $this->action))
            ->when($this->userId, fn($q) => $q->where('user_id', $this->userId))
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->search, function ($q) {
                $term = "%{$this->search}%";
                $q->where(function ($qq) use ($term) {
                    $qq->where('route', 'like', $term)
                       ->orWhere('message', 'like', $term)
                       ->orWhere('model_type', 'like', $term)
                       ->orWhere('ip', 'like', $term);
                });
            })
            ->orderByDesc('id');
    }

    public function render()
    {
        $logs = $this->buildQuery()->paginate($this->perPage);
        // تحميل الترجمات لضمان عرض الاسم المحلي بدلاً من اسم المستخدم
        $users = User::with('translations')->orderBy('username')->select(['id','username'])->get();

        return view('livewire.logs.system-log-list', [
            'logs' => $logs,
            'users' => $users,
        ]);
    }
}
