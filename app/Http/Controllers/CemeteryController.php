<?php namespace App\Http\Controllers;

use App\Burial;
use App\Cemetery;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Plot;
use Illuminate\Http\Request;
use Crypt;
use Illuminate\Support\Facades\Crypt as FacadesCrypt;
class CemeteryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	public function show($encryptedId) {

		 $id = \Crypt::decrypt($encryptedId);

		// Find cemetery
		$cemetery = Cemetery::findOrFail($id);

        $cemetery = Cemetery::with(['sections', 'sections.plots', 'sections.plots.burial'])->findOrFail($id);
        return view('cemetery.map', compact('cemetery'));
    }

    public function guide($plotId) {
        $plot = Plot::with(['section', 'section.cemetery'])->findOrFail($plotId);
        return view('cemetery.guide', ['lat' => $plot->lat, 'lng' => $plot->lng]);
    }

	public function searchBurials($id, Request $request)
	{
		$query = $request->input('query');
		if (empty($query)) {
			return response()->json([]);
		}

		$burials = Burial::where('deceased_name', 'LIKE', '%' . $query . '%')
                     ->whereHas('plot.section.cemetery', function ($q) use ($id) {
                         $q->where('cemeteries.id', $id);
                     })
                     ->with(['plot' => function ($q) {
                         $q->select('id', 'lat', 'lng', 'number', 'section_id', 'burial_id');
                     }])
                     ->get(['id', 'deceased_name']);

		return response()->json($burials);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
