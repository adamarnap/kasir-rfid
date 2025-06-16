<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Navigation;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('navs.read');

        $navigations = Navigation::where('parent_id', null)->with('child')->get();
        return view('settings.navigation.index', compact('navigations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->setRule('navs.create');

        $create = true;
        $navigations = Navigation::all();
        return view('settings.navigation.edit', compact('create', 'navigations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->setRule('navs.create');

        $request->validate([
            'name' => 'required',
            'url' => 'required',
            'slug' => 'required',
            'icon' => 'nullable',
            'parent_id' => 'nullable',
            'order' => 'nullable',
            'active' => 'nullable',
            'display' => 'nullable',
        ]);
        //
        $navigation = new Navigation();
        $navigation->name = $request->name;
        $navigation->url = $request->url;
        $navigation->slug = $request->slug;
        $navigation->icon = $request->icon;
        $navigation->parent_id = $request->parent_id;
        $navigation->order = $request->order;
        $navigation->active = $request->active == 1 ? 1 : 0;
        $navigation->display = $request->display == 1 ? 1 : 0;
        $navigation->save();
        return redirect()->back()->with('success', __('app.notif.successSave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->setRule('navs.update');

        $navigation = Navigation::find($id);
        $navigations = Navigation::all();
        return view('settings.navigation.edit', compact('navigation', 'navigations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $this->setRule('navs.update');

        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'url' => 'required',
            'icon' => 'nullable',
            'parent_id' => 'nullable',
            'order' => 'nullable',
            'active' => 'nullable',
            'display' => 'nullable',
        ]);
        //
        $navigation = Navigation::find($id);
        $navigation->name = $request->name;
        $navigation->url = $request->url;
        $navigation->icon = $request->icon;
        $navigation->parent_id = $request->parent_id;
        $navigation->order = $request->order;
        $navigation->active = $request->active;
        $navigation->display = $request->display;
        if(!$request->has('active')){
            $navigation->active = 0;
        }
        if(!$request->has('display')){
            $navigation->display = 0;
        }
        $navigation->save();
        return redirect()->back()->with('success', __('app.notif.successUpdate'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->setRule('navs.delete');

        $navigation = Navigation::find($id);
        $navigation->delete();
        return redirect('navs')->with('success', __('app.notif.successDelete'));
    }
}
