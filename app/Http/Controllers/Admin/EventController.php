<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserType;
use App\Models\Event;
use App\Models\EventMedia;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Collection;
class EventController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return view('admin.events.index', ['events' => Event::where('deleted_at' , null)->simplePaginate(20)]);
    }

    public function create()
    {
        return view('admin.events.edit', ['create' => 'create', 'categories' => EventCategory::whereNull('deleted_at')->get()]);
    }

     public function readOnly($id)
     {
        $event = Event::find($id);
        $images = EventMedia::Where('event_id' , $id)->get();
        $categories = EventCategory::whereNull('deleted_at')->get();
        return view('admin.events.edit', ['event' => $event, 'images' => $images, 'readonly' => 'readonly', "categories" => $categories]);
     }

     public function edit($id)
     {
        $event = Event::find($id);
        $images = EventMedia::Where('event_id' , $id)->get();
        $categories = EventCategory::whereNull('deleted_at')->get();
        return view('admin.events.edit', ['event' => $event, 'images' => $images, 'edit' => 'edit', "categories" => $categories]);
     }

     public function save()
    {
        $data = \request()->all();

        $event =  Event::create([
            'category_id' => $data['category'],
            'name' => $data['event_name'],
            'price' => $data['price'],
            'quantity_available' => $data['quantity_available'],
            'location' => $data['physical_address'],
            'description' => $data['event_description'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'active' => $data['active'] == 'yes' ? 1 : 0,
            ]);

            If(isset($data['images'])){

                if($data["imagesToRemove"][0] !== null){

                    $imgsToRemove = array_map('intval', explode(',', $data["imagesToRemove"][0]));
            
                    for($i = 0; $i < count($imgsToRemove); $i++){
                        foreach (array_keys(array_keys($data['images']), $imgsToRemove[$i]) as $key) {
                            unset($data['images'][$imgsToRemove[$i]]);
                        }
                    }
            
                    $data['images'] = array_values($data['images']);
                }
        
                    $image = $data['images'];
                    $imageNameArray = [];
                    Storage::makeDirectory('images/events');
            
                    for ($i = 0; $i < count($image); $i++) {
            
            
                        $extension = $image[$i]->getClientOriginalExtension();
                        $image_name = pathinfo($image[$i]->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
                
                        $img = Image::make($image[$i]);
            
                        EventMedia::create([
                            'event_id' => $event->id,
                            'media_path' => 'storage/images/events/' . $image_name,
                        ]);
            
                        Storage::disk('public')->put('images/events/' . $image_name, $img->encode());
            
                    }
            }

       

        return redirect('admin/events')->with('success', 'Event Saved!');

    }

    public function update(){
        $data = \request()->all();

        Event::where('id' , $data['id'])->update([
            'category_id' => $data['category'],
            'name' => $data['event_name'],
            'price' => $data['price'],
            'quantity_available' => $data['quantity_available'],
            'location' => $data['physical_address'],
            'description' => $data['event_description'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'active' => $data['active'] == 'yes' ? 1 : 0,
        ]);

        

    
        if (isset($data['images'])) {
            if($data["imagesToRemove"][0] !== null){

                $imgsToRemove = array_map('intval', explode(',', $data["imagesToRemove"][0]));
        
                for($i = 0; $i < count($imgsToRemove); $i++){
                    foreach (array_keys(array_keys($data['images']), $imgsToRemove[$i]) as $key) {
                        unset($data['images'][$imgsToRemove[$i]]);
                    }
                }
        
                $data['images'] = array_values($data['images']);
            }
    
            
            if(isset($data['recentlyUploadImages'])){
            EventMedia::where('event_id', $data['id'])->forceDelete();
                for ($i = 0; $i < count($data['recentlyUploadImages']); $i++) {
                    EventMedia::create([
                        'event_id' => $data['id'],
                        'media_path' => strstr($data['recentlyUploadImages'][$i], 'storage')
                    ]);
                }
            }
    
    

        $image = $data['images'];
        $imageNameArray = [];
        Storage::makeDirectory('images/events');

        for ($i = 0; $i < count($image); $i++) {


            $extension = $image[$i]->getClientOriginalExtension();
            $image_name = pathinfo($image[$i]->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
    
            $img = Image::make($image[$i]);

            EventMedia::create([
                'event_id' => $data['id'],
                'media_path' => 'storage/images/events/' . $image_name,
            ]);

            Storage::disk('public')->put('images/events/' . $image_name, $img->encode());

             }
        }

    return redirect('admin/events')->with('success', 'Event Updated!');

    }


    public function search()
    {
        $data = \request()->all();
        unset($data['_token']);

        $this->q = \request()->get('q');
        if ( $this->q== '') {
            return back()->with('warning', 'Please enter a value in the field');
        }

        $events = Event::whereNull('deleted_at')->where('name', 'LIKE', '%' .  $this->q . '%')->simplePaginate(20);


        if ($events->isNotEmpty()) {
            \request()->session()->now('info', count($events) . ' results found');

            return view('admin.events.index', ['events' => $events ,  'input' => $this->q]);
        } else {
            return back()->with('warning', 'No events found. Try again !');
        }
    }

    
    public function delete($id)
    {
        Event::where('id' , $id)->delete();
        return redirect('admin/events')->with('success', 'Event Deleted!');
    }

}
