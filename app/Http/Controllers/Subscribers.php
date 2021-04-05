<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class Subscribers extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::all();

        return SubscriberResource::collection($subscribers);
    }

    public function store(CreateSubscriberRequest $request)
    {
        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->first_name = $request->first_name;
        $subscriber->last_name = $request->last_name;
        $subscriber->opt_in = $request->has('opt_in');
        $subscriber->save();

        return SubscriberResource::make($subscriber);
    }

    public function show($id)
    {
        $subscriber = Subscriber::findOrFail($id);

        return SubscriberResource::make($subscriber);
    }

    public function update(UpdateSubscriberRequest $request, $id)
    {
        $subscriber = Subscriber::findOrFail($id);
        if($request->has('email')){
            $subscriber->email = $request->email;
        }
        if($request->has('first_name')){
            $subscriber->first_name = $request->first_name;
        }
        if ($request->has('last_name')) {
            $subscriber->last_name = $request->last_name;
        }
        if($request->has('opt_in')){
            $subscriber->opt_in = (bool) $request->opt_in;
        }

        $subscriber->save();
        return SubscriberResource::make($subscriber);
    }

    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();
        return [
            'success' => true,
        ];
    }
}
