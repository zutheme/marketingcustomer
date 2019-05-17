<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Auth; 
use Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\ControllerName;
class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
       
        //View::composer(['admin.dashboard','dashboard'], function ($view) {
        View::composer(array('admin.dashboard','dashboard','topnav','admin.topnav'), function ($view) {
            $_namecattype="website";
            $rs_catbytype = DB::select('call ListAllCatByTypeProcedure(?)',array($_namecattype));
            $catbytypes = json_decode(json_encode($rs_catbytype), true);
            $iduser = Auth::id();
            $qr_select_profile = DB::select('call SelectProfileProcedure(?)',array($iduser));
            $profile = json_decode(json_encode($qr_select_profile), true);
            $view->with(compact('catbytypes','profile','iduser'));
        });
        //View::composer(['partials.nav', 'partials.top-nav'], handler)
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
