<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * List items yang dapat digunakan
     */
    const LIST_TOTAL_ITEMS = [
        'categories' => [
            'icon' => 'fa-database',
            'title' => 'Categories',
        ],
        'pelanggans' => [
            'icon' => 'fa-users',
            'title' => 'Customers',
        ],
        'products' => [
            'icon' => 'fa-box',
            'title' => 'Products',
        ],
        'suppliers' => [
            'icon' => 'fa-truck',
            'title' => 'Suppliers',
        ],
        'transactions' => [
            'icon' => 'fa-wallet',
            'title' => 'Transaction',
        ],
    ];

    /**
     * Fungsi untuk melakukan paginasi dan filter list data
     *
     * @param  \Illuminate\Http\Request  $request  request dari pengguna
     */
    public static function get(User $user): array
    {
        switch ($user->role) {
            case User::LEVEL_ADMIN:
                return self::dashboardAdmin();
            case User::LEVEL_KASIR:
                return self::dashboardCashier($user);
            case User::LEVEL_MANAGER:
                return self::dashboardManager($user);
            default:
                return [];
        }
    }

    /**
     * Mendapatkan data untuk dashboard admin
     */
    public static function dashboardAdmin(): array
    {
        $query = self::bindItemsToQuery(self::LIST_TOTAL_ITEMS);

        return DB::select($query);
    }

    /**
     * Mendapatkan data untuk dashboard kasir
     */
    public static function dashboardCashier(?User $user): array
    {
        // filter
        $allowedItems = ['transactions', 'products', 'pelanggans'];

        $items = array_filter(self::LIST_TOTAL_ITEMS, function ($key) use ($allowedItems) {
            return in_array($key, $allowedItems);
        }, ARRAY_FILTER_USE_KEY);

        // add custom condition
        $items['transactions']['condition'] = 'kasir_id = '.$user->id;

        $query = self::bindItemsToQuery($items);

        return DB::select($query);
    }

    /**
     * Mendapatkan data untuk dashboard manager
     */
    public static function dashboardManager(?User $user): array
    {
        // filter
        $allowedItems = ['transactions', 'products', 'suppliers'];

        $items = array_filter(self::LIST_TOTAL_ITEMS, function ($key) use ($allowedItems) {
            return in_array($key, $allowedItems);
        }, ARRAY_FILTER_USE_KEY);

        $query = self::bindItemsToQuery($items);

        return DB::select($query);
    }

    /**
     * Convert items to query sql
     */
    public static function bindItemsToQuery(array $items): string
    {
        $listTables = [];

        foreach ($items as $tableName => $item) {
            array_push($listTables, self::bind($tableName, $item));
        }

        return implode(' union all ', $listTables);
    }

    /**
     * bind item to sql query
     */
    public static function bind(string $tableName, array $item): string
    {
        $title = $item['title'];
        $icon = $item['icon'];
        $condition = isset($item['condition']) ? 'where '.$item['condition'] : '';

        return "select '{$icon}' as icon, '{$title}' as title, count($tableName.id) as total from $tableName $condition";
    }
}
