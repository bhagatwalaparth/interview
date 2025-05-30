<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Prize;
use App\Http\Requests\PrizeRequest;
use Illuminate\Http\Request;



class PrizesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $prizes = Prize::all();

        return view('prizes.index', ['prizes' => $prizes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $currentProbability = floatval(Prize::sum('probability'));
        $remainingProbability = 100 - $currentProbability;

        if ($remainingProbability <= 0) {
            return redirect()->back()->withInput()->withErrors(['probability' => 'No room for additional probabilities.']);
        }
        return view('prizes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PrizeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PrizeRequest $request)
    {
        $newProbability = floatval($request->input('probability'));
        $currentProbability = floatval(Prize::sum('probability'));
        $remainingProbability = 100 - $currentProbability;

        if ($remainingProbability <= 0) {
            return redirect()->back()->withInput()->withErrors(['probability' => 'No room for additional probabilities.']);
        }

        if ($newProbability > $remainingProbability) {
            return redirect()->back()->withInput()->withErrors(['probability' => 'Adding this probability will exceed the total limit. You can add up to ' . $remainingProbability . '% more.']);
        }

        $prize = new Prize;
        $prize->title = $request->input('title');
        $prize->probability = floatval($request->input('probability'));
        $prize->save();

        return to_route('prizes.index');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $prize = Prize::findOrFail($id);
        return view('prizes.edit', ['prize' => $prize]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PrizeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PrizeRequest $request, $id)
    {
        $prize = Prize::findOrFail($id);
        $remainingProbability = 100 - Prize::where('id', '!=', $id)->sum('probability');
        $newProbability = floatval($request->input('probability'));

        if ($newProbability > $remainingProbability) {
            return redirect()->back()->withInput()->withErrors(['probability' => 'Updating this probability will exceed the total limit. You can update up to ' . $remainingProbability . '% more.']);
        }

        $prize->title = $request->input('title');
        $prize->probability = floatval($request->input('probability'));
        $prize->save();

        return to_route('prizes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $prize = Prize::findOrFail($id);
        $prize->delete();

        return to_route('prizes.index');
    }


    public function simulate(Request $request)
    {
        
        for ($i = 0; $i < $request->number_of_prizes ?? 10; $i++) {
            Prize::nextPrize();
        }

        return to_route('prizes.index');
    }

    public function reset()
    {
        $prizes = Prize::all();

        foreach ($prizes as $key => $prize) {
            $prize->awarded = 0;
            $prize->update();
        }
        return to_route('prizes.index');
    }
}
