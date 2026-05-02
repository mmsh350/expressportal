<?php

namespace App\Http\Controllers;

use App\Models\ComboDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComboDeviceController extends Controller
{
    public function index()
    {
        $devices = ComboDevice::latest()->paginate(10);
        return view('admin.combo-devices.index', compact('devices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'condition' => 'required|string',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('combo_devices', 'public');
                $imagePaths[] = $path;
            }
        }

        ComboDevice::create([
            'title' => $request->title,
            'price' => $request->price,
            'condition' => $request->condition,
            'description' => $request->description,
            'images' => $imagePaths,
        ]);

        return redirect()->route('admin.combo-devices.index')->with('success', 'Combo Device created successfully.');
    }

    public function edit($id)
    {
        $device = ComboDevice::findOrFail($id);
        return view('admin.combo-devices.edit', compact('device'));
    }

    public function update(Request $request, $id)
    {
        $device = ComboDevice::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'condition' => 'required|string',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imagePaths = $device->images ?? [];
        
        if ($request->hasFile('images')) {
            $newImagesCount = count($request->file('images'));
            if ((count($imagePaths) + $newImagesCount) > 5) {
                return back()->with('error', 'A maximum of 5 images is allowed for each combo device.');
            }

            foreach ($request->file('images') as $image) {
                $path = $image->store('combo_devices', 'public');
                $imagePaths[] = $path;
            }
        }

        $device->update([
            'title' => $request->title,
            'price' => $request->price,
            'condition' => $request->condition,
            'description' => $request->description,
            'images' => $imagePaths,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.combo-devices.index')->with('success', 'Combo Device updated successfully.');
    }

    public function deleteImage(Request $request, $id)
    {
        $device = ComboDevice::findOrFail($id);
        $imageToDelete = $request->image_path;
        
        $images = $device->images;

        if (count($images) <= 1) {
            return back()->with('error', 'Every device must have at least one image. You cannot delete the last image.');
        }

        if (($key = array_search($imageToDelete, $images)) !== false) {
            unset($images[$key]);
            Storage::disk('public')->delete($imageToDelete);
            
            $device->images = array_values($images);
            $device->save();
            
            return back()->with('success', 'Image deleted successfully.');
        }

        return back()->with('error', 'Image not found.');
    }

    public function destroy($id)
    {
        $device = ComboDevice::findOrFail($id);
        if ($device->images) {
            foreach ($device->images as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        $device->delete();
        return redirect()->route('admin.combo-devices.index')->with('success', 'Combo Device deleted successfully.');
    }
}
