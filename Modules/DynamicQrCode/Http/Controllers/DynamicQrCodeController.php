<?php

namespace Modules\DynamicQrCode\Http\Controllers;

use App\Business;
use App\BusinessLocation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Utils\ModuleUtil;
use Modules\DynamicQrCode\Entities\DynamicQrCode;
use DB;
use Excel;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class DynamicQrCodeController extends Controller
{
    /**
     * All Utils instance.
     */
    
    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param  ModuleUtil  $product
     * @return void
     */
    public function __construct(ModuleUtil $moduleUtil)
    {
       $this->moduleUtil = $moduleUtil;
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'dynamicqrcode_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);
        $business = Business::findOrFail($business_id);

        $dyn_qr_codes = DynamicQrCode::where('business_id', $business_id)
                                    ->where('active', true)
                                    ->get();
        
        $class_dyn_qr_code = new DynamicQrCode();

        return view('dynamicqrcode::index')
                ->with(compact('dyn_qr_codes', 'class_dyn_qr_code', 'business_locations', 'business'));
    }

    public function getListDynamicQrCodes()
    {
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'dynamicqrcode_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);
        $business = Business::findOrFail($business_id);

        $dyn_qr_codes = DynamicQrCode::where('business_id', $business_id)
                                    ->where('active', true)
                                    ->select('*');

        return Datatables::of($dyn_qr_codes)
        ->addColumn('action', function ($row) {
            $html = '
                    <a href="' . action([\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'edit'],['link' =>$row->link]) . '"class="btn btn-xs btn-primary">
                                    <i class="fa fa-edit"></i>
                                    '. __('messages.edit') .'/ '.__('messages.view'). '
                                </a>
                    '; 
            return $html;
        })
        ->rawColumns(['action'])
        ->make(true);         

        // return view('dynamicqrcode::index')
        //         ->with(compact('dyn_qr_codes','business_locations', 'business'));
    }

    public function generateQr(){
        return view('dynamicqrcode::catalogue.index');
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'dynamicqrcode_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);
        $business = Business::findOrFail($business_id);

        $dyn_qr_codes = DynamicQrCode::where('business_id', $business_id)
                                    ->get();

        return view('dynamicqrcode::create')
                ->with(compact('dyn_qr_codes','business_locations', 'business'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'redirect' => 'required',
        ]);

        $n = 8;
        $business_id = auth()->user()->business_id;

        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        
        $dyn_qr_code = DynamicQrCode::where('link',$randomString)->first();

        if(is_object($dyn_qr_code)){
            return redirect()->back() ->with('msg-alert', 'Es ist einen Fehler aufgetreten. Bitte versuchen Sie noch ein Mal!');
        }else{
            $dynamic_qr_code = new DynamicQrCode();
            $dynamic_qr_code->business_id = $business_id;
            $dynamic_qr_code->user_id = auth()->user()->id;
            $dynamic_qr_code->title = $request->title;
            $dynamic_qr_code->redirect = $request->redirect;
            $dynamic_qr_code->link = $randomString;
            $dynamic_qr_code->url = route('dynamic_qr_code_redirect', $randomString);
            $dynamic_qr_code->save();
        }

        return redirect()->route('dynamic_qr_code_list');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('dynamicqrcode::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($link)
    {
        // dd($link);
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'dynamicqrcode_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);
        $business = Business::findOrFail($business_id);

        $dynamicQrCode = DynamicQrCode::where('link', $link)->first();

        return view('dynamicqrcode::edit')
            ->with(compact('dynamicQrCode','business_locations', 'business'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $link)
    {
        // dd($link.' ketu update');
        $dynamicQrCode = DynamicQrCode::where('link', $link)->first();
        
        $dynamicQrCode->title = $request->title;
        $dynamicQrCode->redirect = $request->redirect;
        // $dynamic_qr_code->link = $randomString;
        $dynamicQrCode->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function redirectQRCode($link){

        $dynamicQrCode = DynamicQrCode::where('link', $link)->first();
        return redirect($dynamicQrCode->redirect);
        // return redirect('https://youtube.com/');
    }
}
