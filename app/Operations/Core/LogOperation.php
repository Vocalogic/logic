<?php

namespace App\Operations\Core;

use App\Enums\Core\LogSeverity;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use App\Models\AppLog;

/**
 * Service class that handles log entries
 * @property Model       $model         // main object
 * @property string      $message       // human readable message
 * @property LogSeverity $logSeverity   // log severity level
 */
class LogOperation
{
    protected Model       $model;
    protected string      $message;
    protected LogSeverity $logSeverity;
    protected ?string     $detail;

    /**
     * Get the user performing the action.
     * 0 = Automated system function
     * @return int
     */
    public function getUserId(): int
    {
        return user()->id ?? 0;
    }

    /**
     * Return the model id
     * @return int
     */
    private function getModelId(): int
    {
        return $this->model->getKey();
    }

    /**
     * Attempt to get the account_id from the model given.
     * If it is an account just use the ID.
     * @return int|null
     */
    private function getAccountId(): int|null
    {
        if ($this->model instanceof Account)
        {
            return $this->model->id;
        }
        return $this->model->account_id ?? null;
    }

    /**
     * Get the string class name for the type of object
     * being sent to the logger
     * @return string
     */
    private function getType(): string
    {
        return $this->model::class;
    }

    /**
     * Get the LogLevel from the enum types.
     * @return int
     */
    private function getLogLevel(): int
    {
        return $this->logSeverity->value;
    }

    /**
     * The message is the human readable event that
     * is displayed to the user.
     * @return string
     */
    private function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Optionally save additional details for developers
     * to review. For API errors, system errors etc.
     * @return string
     */
    private function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * Inserts a log entry into database
     * @return void
     */
    private function insert(): void
    {
        AppLog::create([
            'user_id'    => $this->getUserId(),
            'account_id' => $this->getAccountId(),
            'type'       => $this->getType(),
            'type_id'    => $this->getModelId(),
            'log_level'  => $this->getLogLevel(),
            'log'        => $this->getMessage(),
            'detail'     => $this->getDetail(),
        ]);
    }

    /**
     * This method will be mainly used for log entries
     * @param Model       $model
     * @param string      $message
     * @param LogSeverity $logSeverity
     * @param string|null $detail
     * @return void
     */
    public function write(Model $model, string $message, LogSeverity $logSeverity, ?string $detail = null): void
    {
        $this->model = $model;
        $this->message = $message;
        $this->logSeverity = $logSeverity;
        $this->detail = $detail;
        $this->insert();
    }
}
