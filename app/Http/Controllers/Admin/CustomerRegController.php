<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\Files;
use File;
class CustomerRegController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            $_start_date="";
            $_end_date="";
            $_idcategory="2";
            $_id_post_type="3";
            $_id_status_type="1";
            $result = DB::select('call ListCustomerRegister(?,?,?,?,?)',array($_start_date,$_end_date, $_idcategory, $_id_post_type, $_id_status_type));
            $customer_reg = json_decode(json_encode($result), true);
            return view('admin.customerreg.index',compact('customer_reg'));
        } catch (\Illuminate\Database\QueryException $ex) {
            $errors = new MessageBag(['errorlogin' => $ex->getMessage()]);
            return redirect()->route('admin.customerreg.index')->with('error',$errors);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idimppost)
    {
        $_namecattype="website";
        $rs_catbytype = DB::select('call ListAllCatByTypeProcedure(?)',array($_namecattype));
        $catbytypes = json_decode(json_encode($rs_catbytype), true);
        $rsdetailInteractive = DB::select('call DetailInteractive(?)',array($idimppost));
        $detailpost = json_decode(json_encode($rsdetailInteractive), true);

        $rs_activity = DB::select('call activity_interactive(?)',array($idimppost));
        $activitys = json_decode(json_encode($rs_activity), true);
        $_idinter = 4;
        $rs_post_type_inter = DB::select('call ListPostTypeByIdcatProcedure(?)',array($_idinter));
        $post_type_inter = json_decode(json_encode($rs_post_type_inter), true);
        return view('admin.customerreg.show',compact('post_type_inter','detailpost','catbytypes','activitys','idimppost'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function ListCustomerByCat(Request $request,$_idcategory='0',$_id_post_type='0',$_id_status_type='0',$_start_date="",$_end_date=""){
        try {
            $_namecattype="website";
            $rs_catbytype = DB::select('call ListAllCatByTypeProcedure(?)',array($_namecattype));
            $catbytypes = json_decode(json_encode($rs_catbytype), true);
            $_idparentcat = 1;
            $rs_post_type = DB::select('call ListPostTypeByIdcatProcedure(?)',array($_idparentcat));
            $post_types = json_decode(json_encode($rs_post_type), true);
            $_idinter = 4;
            $rs_post_type_inter = DB::select('call ListPostTypeByIdcatProcedure(?)',array($_idinter));
            $post_type_inter = json_decode(json_encode($rs_post_type_inter), true);
            $_idparent_status = 0;
            $rs_status_type = DB::select('call ListStatusTypeProcedure(?)',array($_idparent_status));
            $status_types = json_decode(json_encode($rs_status_type), true);

            $_start_date = $request->get('_start_date');
            $_end_date = $request->input('_end_date');
            $_sel_receive = 0;
            $rs_selected = array('_start_date'=>$_start_date,'_end_date'=>$_end_date,'_idcategory'=>$_idcategory,'_id_post_type'=>$_id_post_type,'_id_status_type'=>$_id_status_type,'_sel_receive'=>$_sel_receive);
            $list_selected = json_encode($rs_selected);
            $result = DB::select('call ListCustomerRegister(?,?,?,?,?,?)',array($_start_date,$_end_date, $_idcategory, $_id_post_type, $_id_status_type,$_sel_receive));
            $customer_reg = json_decode(json_encode($result), true);
            return view('admin.customerreg.index',compact('customer_reg','catbytypes','post_types','post_type_inter','status_types','list_selected'));
        } catch (\Illuminate\Database\QueryException $ex) {
            $errors = new MessageBag(['errorsql' => $ex->getMessage()]);
            return redirect()->route('admin.customerreg.index')->with('error',$errors);
        }
        return view('admin.customerreg.index',compact('customer_reg','data'));
    }
    public function make_interactive(Request $request)
    {
        $parent_idpost = $request->get('idpost');
        $body = $request->get('body');
        $id_post_type = $request->get('sel_idposttype');
        $id_status_type = $request->get('id_status_type');

        $idemployee = Auth::id();
        try {
            $result = DB::select('call customer_interactive(?,?,?,?,?)',array($parent_idpost,$body, $id_post_type,$id_status_type,$idemployee));
            $cus_interactive = json_decode(json_encode($result), true);
            $success = array('success'=>$cus_interactive);
            return response()->json($success); 
        } catch (\Illuminate\Database\QueryException $ex) {
            $errors = new MessageBag(['error' => $ex->getMessage()]);
            return response()->json($errors);
        }
        return response()->json(array('error' =>'')); 
    }
    public function ListCustomerByDate(Request $request){
        try {
            $_namecattype="website";
            $rs_catbytype = DB::select('call ListAllCatByTypeProcedure(?)',array($_namecattype));
            $catbytypes = json_decode(json_encode($rs_catbytype), true);
            $_idparentcat = 1;
            $rs_post_type = DB::select('call ListPostTypeByIdcatProcedure(?)',array($_idparentcat));
            $post_types = json_decode(json_encode($rs_post_type), true);
            $_idinter = 4;
            $rs_post_type_inter = DB::select('call ListPostTypeByIdcatProcedure(?)',array($_idinter));
            $post_type_inter = json_decode(json_encode($rs_post_type_inter), true);
            $_idparent_status = 0;
            $rs_status_type = DB::select('call ListStatusTypeProcedure(?)',array($_idparent_status));
            $status_types = json_decode(json_encode($rs_status_type), true);           
            $_start_date = $request->get('_start_date');
            $_end_date = $request->input('_end_date');
            $_idcategory = $request->get('sel_idcategory');
            $_id_post_type = $request->get('sel_id_post_type');
            $_id_status_type = $request->get('sel_id_status_type');
            $_sel_receive = $request->get('sel_receive');
            $result = DB::select('call ListCustomerRegister(?,?,?,?,?,?)',array($_start_date,$_end_date, $_idcategory, $_id_post_type, $_id_status_type,$_sel_receive));
            $customer_reg = json_decode(json_encode($result), true);
            $rs_selected = array('_start_date'=>$_start_date,'_end_date'=>$_end_date,'_idcategory'=>$_idcategory,'_id_post_type'=>$_id_post_type,'_id_status_type'=>$_id_status_type,'_sel_receive' => $_sel_receive);
            $list_selected = json_encode($rs_selected);
            return view('admin.customerreg.index',compact('customer_reg','catbytypes','post_types','post_type_inter','status_types','list_selected'));
        } catch (\Illuminate\Database\QueryException $ex) {
            $errors = new MessageBag(['error' => $ex->getMessage()]);
            return redirect()->route('admin.customerreg.index')->with('error',$errors);
        }
        return view('admin.customerreg.index',compact('customer_reg','data'));
    }
    public function interactive_customer(Request $request)
    {
        //show detail
        $idimppost = $request->get('idimppost');
        $_namecattype="website";
        $rs_catbytype = DB::select('call ListAllCatByTypeProcedure(?)',array($_namecattype));
        $catbytypes = json_decode(json_encode($rs_catbytype), true);
        $rsdetailInteractive = DB::select('call DetailInteractive(?)',array($idimppost));
        $detailpost = json_decode(json_encode($rsdetailInteractive), true);

        $rs_activity = DB::select('call activity_interactive(?)',array($idimppost));
        $activitys = json_decode(json_encode($rs_activity), true);
        $_idinter = 4;
        $rs_post_type_inter = DB::select('call ListPostTypeByIdcatProcedure(?)',array($_idinter));
        $post_type_inter = json_decode(json_encode($rs_post_type_inter), true);
        //end detail
        $parent_idpost = $request->get('idpost');
        $body = $request->get('body');
        $id_post_type = $request->get('sel_idposttype');
        $id_status_type = $request->get('id_status_type');
        $idemployee = Auth::id();
        try {
            $result = DB::select('call customer_interactive(?,?,?,?,?)',array($parent_idpost,$body, $id_post_type,$id_status_type,$idemployee));
            $success = json_decode(json_encode($result), true);
            return redirect()->action('Admin\CustomerRegController@show', ['idimppost' => $idimppost]); 
        } catch (\Illuminate\Database\QueryException $ex) {
            $errors = new MessageBag(['error' => $ex->getMessage()]);
            return view('admin.customerreg.show',compact('errors'));
        }
        return redirect()->action('Admin\CustomerRegController@show', ['idimppost' => $idimppost]);  
    }
}
