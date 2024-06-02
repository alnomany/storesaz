<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\UserStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Subscriber')){
            if (Auth::check()) {
                $store_id = \Auth::user()->current_store;
                // $current_store = session()->get('current_store');
    
                // $store = Auth::user();
                // if ($current_store) {
                //     $store_id = $current_store;
                // } else {
                //     $store_id = !empty($store->current_store) ? $store->current_store : '';
                // }
                $subs = Subscription::where('store_id', $store_id)->get();
    
                return view('Subscription.index', compact('subs'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
    
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->can('Create Subscriber')){
            return view('Subscription.create');
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Subscriber')){
            $this->validate(
                $request, ['email' => 'required']
            );

            $subscription             = new Subscription();
            $subscription->email      = $request->email;
            $subscription['store_id'] = \Auth::user()->current_store;
            $subscription->save();

            return redirect()->back()->with('success', __('Email added!'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.'); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Subscription $subscription
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Subscription $subscription
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Subscription $subscription
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Subscription $subscription
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        if(\Auth::user()->can('Delete Subscriber')){
            $subscription->delete();

            return redirect()->back()->with(
                'success', __('subscription Deleted!')
            );
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store_email(Request $request, $id)
    {
       
        $validator = \Validator::make(
            $request->all(), [
                            'email' => 'required|email',
                        ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $subscription             = new Subscription();
        $subscription['email']    = $request->email;
        $subscription['store_id'] = $id;
        $subscription->save();

        return redirect()->back()->with('success', __('Successfully Subscribe'));
       
    }   
}
