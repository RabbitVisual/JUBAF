<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels - Main Configuration
|--------------------------------------------------------------------------
*/

// =========================================================================
// Context 1: Core System Channels
// =========================================================================

/**
 * Universal User Channel
 * Used for personal notifications and direct messages to a specific user.
 */
Broadcast::channel('user.{userId}', function ($user, $userId) {
    // Strict integer comparison for enhanced safety
    return (int) $user->id === (int) $userId;
});

/**
 * Global Notifications Channel
 * Publicly accessible to any authenticated user.
 */
Broadcast::channel('notifications', function ($user) {
    return $user !== null;
});

// =========================================================================
// Context 2: Roles & Permissions
// =========================================================================

/**
 * Role-Based Channel
 * Allows broadcasting to all users within a specific role (e.g., role.admin).
 */
Broadcast::channel('role.{roleName}', function ($user, $roleName) {
    return $user->hasRole($roleName);
});

// =========================================================================
// Context 3: Modules (Conditional Loading)
// =========================================================================

// --- Módulo Chat ---
if (module_enabled('Chat')) {

    /**
     * Chat Session Channel (Public/Private Hybrid)
     * Handles presence and messages for specific chat sessions.
     */
    Broadcast::channel('chat.session.{sessionId}', function ($user, $sessionId) {
        // Optimized query: Only look up session if user has basic permission traits
        $session = \Modules\Chat\App\Models\ChatSession::where('id', $sessionId)
            ->orWhere('session_id', $sessionId)
            ->first();

        if (!$session) {
            return false;
        }

        // Public sessions: Access allowed via session_id string
        if ($session->type === 'public' && $session->session_id === $sessionId) {
            return true;
        }

        // Authenticated access: Must be owner, assigned agent, or administrator
        if ($user) {
            return $user->hasAnyRole(jubaf_chat_agent_role_names())
                || (int) $session->assigned_to === (int) $user->id
                || (int) $session->user_id === (int) $user->id;
        }

        return false;
    });

    /**
     * Private Chat Session (High Security)
     * Strictly for authenticated interactions.
     */
    Broadcast::channel('private-chat.session.{sessionId}', function ($user, $sessionId) {
        if (!$user) {
            return false;
        }

        $session = \Modules\Chat\App\Models\ChatSession::find($sessionId);

        if (!$session) {
            return false;
        }

        // Authorization logic for private data
        if ($user->hasAnyRole(jubaf_chat_agent_role_names())) {
            return true;
        }

        return (int) $session->assigned_to === (int) $user->id
            || (int) $session->user_id === (int) $user->id;
    });

    /**
     * Agents Collective Channel
     * Exclusive channel for customer support agents.
     */
    Broadcast::channel('chat.agents', function ($user) {
        return $user && $user->hasAnyRole(jubaf_chat_agent_role_names());
    });
}
