<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;

class CompareController extends Controller
{
    // Lấy danh sách trong session
    public function index(Request $request)
    {
        $compare = session()->get('compare_tour', []);

        $tours = Tour::whereIn('id', $compare)->get();

        // Nếu request ?list=1 => trả về HTML list để popup load
        if ($request->has('list')) {
            return view('page.compare.list', compact('tours'));
        }

        // Trang so sánh chính
        return view('page.compare.index', compact('tours'));
    }

    // Thêm tour vào danh sách
    public function add(Request $request)
    {
        $id = $request->id;
        $compare = session()->get('compare_tour', []);

        if (count($compare) >= 3) {
            return redirect()->route('compare.index');

        }

        if (in_array($id, $compare)) {
            return redirect()->route('compare.index');

        }

        $compare[] = $id;
        session()->put('compare_tour', $compare);

       return redirect()->route('compare.index');
    }

    // Xóa tour
    public function remove(Request $request)
    {
        $id = $request->id;
        $compare = session()->get('compare_tour', []);

        if (($key = array_search($id, $compare)) !== false) {
            unset($compare[$key]);
            session()->put('compare_tour', $compare);
        }

        return redirect()->route('compare.index');
    }
}
