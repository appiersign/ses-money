<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMerchant;
use App\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'Merchant Registration';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateMerchant $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMerchant $request)
    {
        try {
            $job = new \App\Jobs\CreateMerchant($request->all());
            $this->dispatch($job);
            return Merchant::latest('created_at')->first();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param Merchant $merchant
     * @return void
     */
    public function show(Merchant $merchant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Merchant $merchant)
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
    public function update(CreateMerchant $request, Merchant $merchant)
    {
        $merchant->setEmailAttribute($request->input('email', $merchant->getEmailAttribute()));
        $merchant->setNameAttribute($request->input('name', $merchant->getNameAttribute()));
        $merchant->setAddressAttribute($request->input('address', $merchant->getAddressAttribute()));
        $merchant->setPhoneNumberAttribute($request->input('phone_number', $merchant->getPhoneNumberAttribute()));

        try {
            $merchant->save();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Merchant $merchant
     * @return string
     */
    public function destroy(Merchant $merchant)
    {
        try {
            $merchant->delete();
            return 'deleted';
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    public function resetPassword($ses_money_id)
    {
        if (Auth::user()->role === 'admin') {
            $merchant = Merchant::where('ses_money_id', $ses_money_id)->first();
            if ($merchant <> null) {
                $merchant->password = bcrypt('admin');
                $merchant->save();
                return 'password reset successful';
            } else {
                return 'merchant does not exist';
            }
        }

        return 'you are not allowed to perform this action';
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old' => 'required|min:5|max:20',
            'password' => 'required|confirmed|min:5|max:20',
        ]);
    }

    public function toggleStatus($ses_money_id)
    {
        if (Auth::user()->role === 'admin') {
            $merchant = Merchant::where('ses_money_id', $ses_money_id)->first();
            if ($merchant <> null) {
                if ($merchant->is_active) {
                    $merchant->is_active = false;
                } else {
                    $merchant->is_active = true;
                }

                $merchant->save();
                return $merchant->name. " status updated";
            } else {
                return 'status could not be updated';
            }
        }
        return "you are not allowed to perform this action";
    }
}
