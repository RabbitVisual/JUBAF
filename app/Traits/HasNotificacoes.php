<?php

namespace App\Traits;

use Modules\Notificacoes\App\Services\NotificacaoService;
use App\Models\User;

trait HasNotificacoes
{
    /**
     * Send notification to specific user
     */
    protected function notifyUser(
        User|int $user,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?array $data = null
    ) {
        $service = app(NotificacaoService::class);
        $userId = $user instanceof User ? $user->id : $user;

        $moduleName = $this->getModuleName();

        return $service->sendToUser(
            $userId,
            $type,
            $title,
            $message,
            $actionUrl,
            $data,
            $moduleName,
            $this->getEntityType(),
            $this->getEntityId()
        );
    }

    /**
     * Send notification to role
     */
    protected function notifyRole(
        string $roleName,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?array $data = null
    ) {
        $service = app(NotificacaoService::class);
        $moduleName = $this->getModuleName();

        return $service->sendToRole(
            $roleName,
            $type,
            $title,
            $message,
            $actionUrl,
            $data,
            $moduleName,
            $this->getEntityType(),
            $this->getEntityId()
        );
    }

    /**
     * Send notification to all users
     */
    protected function notifyAll(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?array $data = null
    ) {
        $service = app(NotificacaoService::class);
        $moduleName = $this->getModuleName();

        return $service->sendToAll(
            $type,
            $title,
            $message,
            $actionUrl,
            $data,
            $moduleName,
            $this->getEntityType(),
            $this->getEntityId()
        );
    }

    /**
     * Get module name from class namespace
     */
    protected function getModuleName(): ?string
    {
        $className = get_class($this);

        if (preg_match('/Modules\\\\([^\\\\]+)\\\\/', $className, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get entity type (model name)
     */
    protected function getEntityType(): ?string
    {
        return class_basename($this);
    }

    /**
     * Get entity ID
     */
    protected function getEntityId(): ?int
    {
        return $this->id ?? null;
    }

    /**
     * Send notification when model is created
     */
    public static function bootHasNotificacoes()
    {
        static::created(function ($model) {
            // Override this method in your model to send notifications on create
        });

        static::updated(function ($model) {
            // Override this method in your model to send notifications on update
        });
    }
}

