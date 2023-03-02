<?php

namespace App\Enums\Core;

use App\Models\ProjectTask;

enum ThreadType: string
{
    case ProjectTask = "PROJECT_TASK";
    case ProjectCategory = "PROJECT_CATEGORY";
    case Ticket = "TICKET";


    /**
     * Get a thread type based on the model provided.
     * @param string $class
     * @return ThreadType|null
     */
    static public function getByModel(string $class) : ?ThreadType
    {
        return match($class)
        {
            ProjectTask::class => self::ProjectTask,
            default => null
        };
    }
}
