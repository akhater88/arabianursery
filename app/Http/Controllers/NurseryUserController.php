<?php

namespace App\Http\Controllers;

use App\Models\Nursery;
use App\Models\NurseryUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class NurseryUserController extends Controller
{
    public function createCompleteRegistration(Request $request)
    {
        $user = $request->user('nursery_web');

        return view('auth.register', [
            'is_complete_action' => true,
            'nursery_user_name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function storeCompleteRegistration(Request $request)
    {
        $user = $request->user('nursery_web');

        $request->validate([
            'nursery_name' => ['required', 'string', 'max:255'],
            'nursery_user_name' => ['required', 'string', 'max:255'],
            'email' => is_null($user->email) ? ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . NurseryUser::class] : [],
            'mobile_number' => ['required', 'string', 'max:255', 'unique:' . NurseryUser::class],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-90,90'],
            'nursery_address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $nursery = Nursery::create([
            'name' => $request->nursery_name,
            'location' => new Point($request->lat, $request->lng),
            'address' => $request->nursery_address,
        ]);

        $request->user()->update([
            'name' => $request->nursery_user_name,
            'email' => $user->email ?? $request->email,
            'country_code' => '+962',
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'nursery_id' => $nursery->id,
            'status' => NurseryUser::STATUS_ENABLED
        ]);

        return redirect()->route('dashboard');
    }

    public function showNurseriesUsers(Request $request){
        $user = $request->user();
        if(!$user->hasRole('nursery-admin')){
           return abort(403);
        }

        $operators = NurseryUser::where('nursery_id',$user->nursery_id)->where('id','<>', $user->id)->get();
        return view('nursery-operators.index', [
            'operators' => $operators,
            'page_title' => 'مشغلين المشتل'
        ]);
    }

    public function createNurseriesUsers(Request $request){
        $user = $request->user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        return view('nursery-operators.create',[
        'page_title' => 'إضافة مشغل للمشتل'
        ]);

    }

    public function storeNurseriesUsers(Request $request){
        $user = $request->user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:255', 'unique:' . NurseryUser::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . NurseryUser::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $nurseryUser = NurseryUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'country_code' => '+962',
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'nursery_id' => $user->nursery->id
        ]);

        $nurseryUser->syncRoles('nursery-operator');
        event(new Registered($nurseryUser));

        return redirect(route('nursery-operators'))->with('status', 'تم إضافة مشغل بنجاح');
    }

    public function editNurseriesUsers(Request $request,NurseryUser $nursery_user){
        $user = $request->user();

        if(!$user->hasRole('nursery-admin') || $user->nursery_id != $nursery_user->nursery_id){
            return abort(403);
        }
        return view('nursery-operators/edit', [
            'page_title' => 'تعديل خدمة تشتيل',
            'operator' => $nursery_user,
        ]);

    }

    public function updateNurseriesUsers(Request $request,NurseryUser $nursery_user){
        $user = $request->user();

        if(!$user->hasRole('nursery-admin') || $user->nursery_id != $nursery_user->nursery_id){
            return abort(403);
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:255', 'unique:' . NurseryUser::class.',mobile_number,'.$nursery_user->id],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . NurseryUser::class.',email,'.$nursery_user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $nursery_user->name = $request->name;
        $nursery_user->email = $request->email;
        $nursery_user->mobile_number = $request->mobile_number;
        if($request->password != null){
            $nursery_user->password = Hash::make($request->password);
        }
        $nursery_user->save();
        return redirect(route('nursery-operators'))->with('status', 'تم تعديل المشغل بنجاح');
    }

    public function destroyNurseriesUsers(Request $request,NurseryUser $nursery_user){
        $user = $request->user();

        if(!$user->hasRole('nursery-admin') || $user->nursery_id != $nursery_user->nursery_id){
            return abort(403);
        }
        if($nursery_user->status == 1){
            $nursery_user->status = 0;
            $message = 'تم ايقاف '.$nursery_user->name.' المشغل بنجاح ';
        } else {
            $nursery_user->status = 1;
            $message = 'تم تفعيل '.$nursery_user->name.' المشغل بنجاح ';
        }
        $nursery_user->save();
        return redirect(route('nursery-operators'))->with('status', $message);

    }
}
