<?php

namespace App\Support\Database;

final class SeedCatalog
{
    /**
     * Seeders seguros para update em produção.
     *
     * @return array<int, class-string>
     */
    public static function updateSeeders(): array
    {
        return [
            \Database\Seeders\RolesPermissionsSeeder::class,
            \Modules\Talentos\Database\Seeders\TalentosDatabaseSeeder::class,
            \Modules\Homepage\Database\Seeders\HomepageDatabaseSeeder::class,
            \Modules\Chat\Database\Seeders\ChatDatabaseSeeder::class,
            \Modules\Chat\Database\Seeders\ChatConfigSeeder::class,
            \Modules\Blog\Database\Seeders\BlogDatabaseSeeder::class,
            \Modules\Blog\Database\Seeders\BlogSeeder::class,
            \Modules\Avisos\Database\Seeders\AvisosDatabaseSeeder::class,
            \Modules\Notificacoes\Database\Seeders\NotificacoesDatabaseSeeder::class,
            \Modules\Secretaria\Database\Seeders\MinuteTemplatesSeeder::class,
        ];
    }

    /**
     * Seeders de reset completo.
     *
     * @return array<int, class-string>
     */
    public static function fullSeeders(): array
    {
        return self::updateSeeders();
    }
}
