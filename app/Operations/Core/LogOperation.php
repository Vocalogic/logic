<?php

namespace App\Operations\Core;

use App\Enums\Core\LogSeverity;
use Illuminate\Database\Eloquent\Model;
use App\Models\AppLog;

/**
 * Service class that handles log entries
 * @property Model        $model         // main object
 * @property string       $message       // human readable message
 * @property LogSeverity  $logSeverity   // log severity level
 */
class LogOperation
{
    protected Model $model;
    protected string $message;
    protected LogSeverity $logSeverity;

    public function __construct(Model $model, string $message, LogSeverity $logSeverity)
    {
        $this->model = $model;
        $this->message = $message;
        $this->logSeverity = $logSeverity;
    }

    public function getUserId(): int
    {
        return user()->id ?? 0;
    }

    private function getModelId(): int
    {
        return $this->model->getKey();
    }

    private function getAccountId(): int|null
    {
        return $this->model->account_id ?? null;
    }

    private function getType(): string
    {
        return $this->model::class;
    }

    private function getLogLevel(): int
    {
        return $this->logSeverity->value;
    }

    private function getMessage(): string
    {
        return $this->message;
    }

    private function getDetail(): string
    {
        return ''; // TODO to be implemented later
    }

    /**
     * Inserts a log entry into database
     * @return void
     */
    private function insert(): void
    {
        AppLog::create([
            'user_id' => $this->getUserId(),
            'account_id' => $this->getAccountId(),
            'type' => $this->getType(),
            'type_id' => $this->getModelId(),
            'log_level' => $this->getLogLevel(),
            'log' => $this->getMessage(),
            'detail' => $this->getDetail(),
        ]);
    }

    /**
     * This function will be mainly used for log entries
     * @param  Model  $model
     * @param  string  $message
     * @param  LogSeverity|null  $logSeverity
     * @return void
     */
    public static function write(Model $model, string $message, LogSeverity $logSeverity = null): void
    {
        if ($logSeverity === null) {
            $logSeverity = LogSeverity::Info;
        }
        $service = new self($model, $message, $logSeverity);
        $service->insert();
    }
}