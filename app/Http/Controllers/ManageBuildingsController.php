<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room; // Import the Room model

class ManageBuildingsController extends Controller
{
    // แสดงรายการอาคาร
    public function index()
    {
        $buildings = Building::all();
        $rooms = Room::all(); // Retrieve all rooms
        return view('dashboard.manage_rooms', compact('buildings', 'rooms')); // Pass both buildings and rooms to the view
    }

    // เพิ่มอาคาร
    public function store(Request $request)
    {
        // เพิ่ม validation สำหรับ `building_name` และ `citizen_save`
        $request->validate([
            'building_name' => 'required|string|max:255',
            'citizen_save' => 'required|string|max:255', // ตรวจสอบว่า citizen_save ต้องไม่เป็นค่าว่าง
        ]);

        // Log the request data for debugging
        \Log::info('Building Store Request Data:', $request->all());

        // ส่วนของการเพิ่มข้อมูล
        $building = new Building();
        $building->building_name = $request->building_name;
        $building->citizen_save = $request->citizen_save;
        $building->date_save = now(); // ใช้เวลาในปัจจุบัน
        $building->save();

        return redirect()->route('manage.buildings')->with('success', 'Building added successfully.');
    }

    public function update(Request $request, $id)
    {
        $building = Building::find($id);

        if (!$building) {
            return response()->json(['message' => 'ไม่พบอาคาร'], 404);
        }

        $building->building_name = $request->building_name;
        $building->citizen_save = $request->citizen_save;
        $building->save();

        return redirect()->route('manage.buildings')->with('success', 'Building updated successfully.');
    }

    public function destroy($id)
    {
        // 1. หาอาคารที่ต้องการลบ
        $building = Building::findOrFail($id);

        // 2. ลบข้อมูล
        $building->delete();

        // 3. Redirect พร้อมข้อความแจ้งเตือน
        return redirect()->back()
            ->with('success', 'ลบอาคารเรียบร้อยแล้ว');
    }
}
