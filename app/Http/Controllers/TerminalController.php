<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTerminalRequest;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateTerminalRequest $request
     * @return void
     */
    public function create(CreateTerminalRequest $request)
    {
        $terminal = Terminal::create([
            'merchant_id' => $request->input('merchant_id'),
            'name'  => $request->input('name', null),
            'type'  => $request->input('type', null)
        ]);

        $terminal->update(['']);
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
     * @param  \App\Terminal  $terminals
     * @return \Illuminate\Http\Response
     */
    public function show(Terminal $terminals)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Terminal  $terminals
     * @return \Illuminate\Http\Response
     */
    public function edit(Terminal $terminals)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Terminal  $terminals
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Terminal $terminals)
    {
        //
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
