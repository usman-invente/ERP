<?php

namespace Modules\DynamicQrCode\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Utils\ModuleUtil;
use Menu;

class DataController extends Controller
{
    /**
     * Defines module as a superadmin package.
     *
     * @return array
     */
    public function superadmin_package()
    {
        return [
            [
                'name' => 'dynamicqrcode_module',
                'label' => __('dynamicqrcode::lang.dynamicqrcode_module'),
                'default' => false,
            ],
        ];
    }

    /**
     * Adds Catalogue QR menus
     *
     * @return null
     */
    public function modifyAdminMenu()
    {
        $business_id = session()->get('user.business_id');
        $module_util = new ModuleUtil();
        $is_dynamicqrcode_enabled = (bool) $module_util->hasThePermissionInSubscription($business_id, 'dynamicqrcode_module', 'superadmin_package');

        if ($is_dynamicqrcode_enabled) {
            Menu::modify('admin-sidebar-menu', function ($menu) {
                $menu->url(
                        action([\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'index']),
                        __('dynamicqrcode::lang.dyn_qr_code'),
                        ['icon' => 'fa fas fa-qrcode', 'active' => request()->segment(1) == 'dynamic-qr-code', 'style' => config('app.env') == 'demo' ? 'background-color: #ff851b;' : '']
                    )
                ->order(100);
            });
        }
    }
}
