<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventMember;
use App\Models\EventPrize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OSS\Result\Result;

class EventController extends Controller
{
    //
    public function index()
    {
        $events =Event::paginate(5);

        return view('Event/index',compact('events'));
    }

    public function show(Event $event)
    {
        return view('Event/show',compact('event'));
    }

    public function edit(Event $event)
    {
        if ($event->is_prize==1){
            session()->flash('danger', '该活动已开奖,不能报名');
            return back();
        }
        $eventMembersCount = EventMember::where('events_id',$event->id)->get()->count();
        if ($event->signup_num<$eventMembersCount){
            session()->flash('danger', '报名人数已满');
            return back();
        }

        $eventMembers =EventMember::all();
        foreach ($eventMembers as $eventMember){
            if ($eventMember->events_id==$event->id&&$eventMember->member_id==Auth::user()->id){
                session()->flash('danger', '您已报名,无需重复操作');
                return back();
            }
        }
        EventMember::create([
            'member_id'=>Auth::user()->id,
            'events_id'=>$event->id
        ]);


        session()->flash('success', '报名成功');
        return redirect()->route('events.index');

    }

    public function prize(Event $event)
    {
        if ($event->is_prize==0){
            session()->flash('danger', '当前活动还未开奖,请耐心等待');
            return back();
        }
        $eventprize =EventPrize::where('events_id',$event->id)->where('member_id',Auth::id())->first();
            if (count($eventprize)==1){
                session()->flash('success', '恭喜您,你获得了'.$eventprize->name.'');
                return redirect()->route('events.index');
            }else{
                session()->flash('danger', '很遗憾,您未中奖');
                return redirect()->route('events.index');
            }

        }

}
