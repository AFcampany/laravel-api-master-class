<?php

namespace App\Permissions\V1;

final class Abilities {

    public const string CreateTicket = 'ticket:create';
    public const string UpdateTicket = 'ticket:update';
    public const string ReplaceTicket = 'ticket:replace';
    public const string DeleteTicket = 'ticket:delete';

    public const string UpdateOwnTicket = 'ticket:own:update';
    public const string DeleteOwnTicket = 'ticket:own:delete';

    public const string CreateUser = 'user:create';
    public const string UpdateUser = 'user:update';
    public const string ReplaceUser = 'user:replace';
    public const string DeleteUser = 'user:delete';

    public static function getAbilities($user) {
        if ($user->is_manager) {
            return [
                self::CreateTicket,
                self::UpdateTicket,
                self::ReplaceTicket,
                self::DeleteTicket,
                self::CreateUser,
                self::UpdateUser,
                self::ReplaceUser,
                self::DeleteUser,
            ];
        } else {
            return [
                self::CreateTicket,
                self::UpdateOwnTicket,
                self::DeleteOwnTicket,
            ];
        }
    }

}
