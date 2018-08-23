<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTerminalRequest;
use App\Merchant;
use App\Terminal;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terminals = Terminal::with('merchant')->paginate(20);
        return view('pages.terminal.index', compact('terminals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $merchants = Merchant::all(['id', 'name']);
        return view('pages.terminal.create', compact('merchants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTerminalRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateTerminalRequest $request)
    {
        $terminal = new Terminal();
        $terminal->merchant()->associate($request->merchant);
        $terminal->name = $request->name;
        $terminal->type = $request->type;

        try {
            $terminal->save();
            $terminal->setSesMoneyIdAttribute()->save();
            session()->flash('success', 'Terminal created!');
            return redirect()->route('terminals.index');
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            session()->flash('error', 'Sorry, we\'re unable to create Terminal. Please try again later!');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $ses_money_id
     * @return \Illuminate\Http\Response
     */
    public function show(string $ses_money_id)
    {
        $terminal = Terminal::with('merchant')->where('ses_money_id', $ses_money_id)->first();
        if ($terminal) {
            return view('pages.terminal.show', compact('terminal'));
        }
        session()->flash('error', 'Terminal does not exist!');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $ses_money_id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $ses_money_id)
    {
        $merchants = Merchant::all();
        $terminal = Terminal::with('merchant')->where('ses_money_id', $ses_money_id)->first();
        if ($terminal) {
            return view('pages.terminal.edit', compact('terminal', 'merchants'));
        }
        session()->flash('error', 'Terminal does not exist!');
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CreateTerminalRequest $request
     * @param string $ses_money_id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTerminalRequest $request, string $ses_money_id)
    {
        $terminal = Terminal::where('ses_money_id', $ses_money_id)->first();
        if ($terminal){
            $terminal->name = $request->input('name');
            $terminal->type = $request->input('type');
            $terminal->merchant_id = $request->input('merchant');

            try {
                $terminal->update();
                session()->flash('success', 'Terminal updated!');
                return redirect()->route('terminals.index');
            } catch (\Exception $exception) {
                session()->flash('error', 'We could not upated Terminal. Please try again later!');
                return redirect()->back()->withInput();
            }
        }
        session()->flash('error', 'Terminal does not exists!');
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Terminal  $terminals
     * @return \Illuminate\Http\Response
     */
    public function destroy(Terminal $terminals)
    {
        //
    }
}
