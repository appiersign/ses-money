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
        $merchants = Merchant::paginate(10);
        return view('pages.merchant.index', compact('merchants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.merchant.create');
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
            session()->flash('success', 'Merchant created!');
            return redirect()->route('merchants.index');
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            session()->flash('error', 'We failed trying to create merchant, please try again later!');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $ses_money_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(string $ses_money_id)
    {
        $merchant = Merchant::where('ses_money_id', $ses_money_id)->first();
        if ($merchant) {

        }
        return $this->fake();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $ses_money_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(string $ses_money_id)
    {
        $merchant = Merchant::where('ses_money_id', $ses_money_id)->first();
        if ($merchant){
            return view('pages.merchant.edit', compact('merchant'));
        }
        return $this->fake();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $ses_money_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $ses_money_id)
    {
        $merchant = Merchant::where('ses_money_id', $ses_money_id)->first();
        if ($merchant) {
            $merchant->setEmailAttribute($request->input('email', $merchant->getEmailAttribute()));
            $merchant->setNameAttribute($request->input('name', $merchant->getNameAttribute()));
            $merchant->setAddressAttribute($request->input('address', $merchant->getAddressAttribute()));
            $merchant->setPhoneNumberAttribute($request->input('phone_number', $merchant->getPhoneNumberAttribute()));

            try {
                $merchant->save();
                session()->flash('success', 'Merchant updated');
                return redirect()->route('merchants.index');
            } catch (\Exception $exception) {
                logger($exception->getMessage());
                session()->flash('error', 'Merchant update failed, please try again later!');
                return redirect()->back();
            }
        }
        return $this->fake();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fake()
    {
        session()->flash('error', 'The ID does not match any merchant!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $ses_money_id
     * @return string
     */
    public function destroy(string $ses_money_id)
    {
        $merchant = Merchant::where('ses_money_id', $ses_money_id)->first();
        if ($merchant) {
            try {
                $merchant->delete();
                session()->flash('success', 'Merchant deleted!');
                return redirect()->route('merchants.index');
            } catch (\Exception $exception) {
                logger($exception->getMessage());
                session()->flash('error', 'Merchant deletion failed, please try again!');
                return redirect()->back();
            }
        }
        return $this->fake();
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
