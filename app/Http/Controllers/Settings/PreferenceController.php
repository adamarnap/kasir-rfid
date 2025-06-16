<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('preferences.read');

        $preferences = Preference::all();
        return view('settings.preferences.index', compact('preferences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->setRule('preferences.create');

        $create = true;
    
        return view('preferences.edit', compact('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->setRule('users.create');

        $request->validate([
            'name' => 'required|max:150',
            'group' => 'required|max:150',
            'value' => 'required|max:255',
        ]);

        $preference = new Preference();
        $preference->name = $request->name;
        $preference->group = $request->group;
        $preference->value = $request->value;
        $preference->save();

        return redirect()->back()->with('success', __('app.notif.successSave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Preference $preference)
    {
        $this->setRule('preferences.update');

        return view('preferences.edit', compact('preference'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Preference $preference)
    {
        $this->setRule('users.update');
        // Validasi umum
        $rules = [
            'name' => 'required|max:150',
            'is_asset' => 'required|boolean',
        ];

        if ($request->is_asset == "1") {
            $rules['path'] = 'required|max:255';
        } else {
            $rules['value'] = 'required|max:255';
        }

        $request->validate($rules);

        // Proses upload file jika is_asset bernilai "1"
        if ($request->is_asset == "1" && $request->hasFile('file_asset')) {
            try {
                $file = $request->file('file_asset');
                $fileName = $request->name . '.' . $file->getClientOriginalExtension();
                // Hapus file lama jika ada
                if ($preference->value) {
                    \Storage::disk('public')->delete($preference->value);
                }
                // Simpan file ke storage
                $file->storeAs($request->path, $fileName, 'public');
                // Set value ke path yang mengandung file, yang diunggah
                $request->merge(['value' => rtrim($request->path, '/') . '/' . $fileName]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to upload file asset: ' . $e->getMessage());
            }
        }

        // Update data preference
        $preference->update($request->only(['name', 'value']));

        return redirect()->back()->with('success', __('app.notif.successUpdate'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Preference $preference)
    {
        $this->setRule('users.delete');

        $preference->delete();
        
        return redirect('preferences')->with('success', __('app.notif.successDelete'));
    }
}
