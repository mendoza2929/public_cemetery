<?php namespace App\Http\Controllers;

use App\Burial;
use App\Cemetery;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Plot;
use App\Section;
use Illuminate\Http\Request;
use Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt as FacadesCrypt;
class CemeteryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$reservationCount = \App\Reservation::count();
		$pendingCount = \App\Reservation::where('status', 'pending')->count();
		$inProcessCount = \App\Reservation::where('status', 'in_process')->count();
		$doneCount = \App\Reservation::where('status', 'done')->count();

		$monthlyReservations = \App\Reservation::select(
			DB::raw('MONTH(date) as month'),
			DB::raw('COUNT(*) as total')
		)
		->whereRaw('YEAR(date) = ?', [date('Y')])
		->groupBy(DB::raw('MONTH(date)'))
		->orderBy(DB::raw('MONTH(date)'))
		->lists('total','month');

		$monthlyData = [];
		for($m = 1; $m <= 12; $m++) {
			$monthlyData[$m] = isset($monthlyReservations[$m]) ? $monthlyReservations[$m] : 0;
		}

		$cemetery = Cemetery::with(['sections', 'sections.plots', 'sections.plots.burial'])->first(); 

		$allPlots = [];
		$section_list = Section::all(); 

		if($cemetery) {
			foreach($cemetery->sections as $section){
				foreach($section->plots as $plot){
					$allPlots[] = $plot;
				}
			}
		}

		$totalPlots = count($allPlots);
		$availablePlots = 0;
		$reservedPlots = 0;
		$soldPlots = 0;
		$restrictedPlots = 0;
		$quitclaimPlots = 0;
		$soldWithBurialPlots = 0;

		foreach($allPlots as $plot){
			// if(count($plot->burial) > 0){
			// 	$reservedPlots++;
			// } else {
			// 	$availablePlots++;
			// }
			switch($plot->status) {
				case 'available':
					$availablePlots++;
					break;
				case 'sold':
					$soldPlots++;
					break;
				case 'reserved':
					$reservedPlots++;
					break;
				case 'restricted':
					$restrictedPlots++;
					break;
				case 'quitclaim':
					$quitclaimPlots++;
					break;
				case 'sold_with_burial':
					$soldWithBurialPlots++;
					break;
			}
		}

		return view('cemetery.admin', compact(
			'reservationCount',
			'pendingCount',
			'inProcessCount',
			'doneCount',
			'monthlyData',
			'cemetery',
			'section_list',
			'totalPlots',
			'availablePlots',
			'reservedPlots',
			'soldPlots',
			'restrictedPlots',
			'quitclaimPlots',
			'soldWithBurialPlots'
		));
	}


	public function login(){
		 return view('cemetery.login');
	}

	public function show($encryptedId) {

		 $id = \Crypt::decrypt($encryptedId);

		// Find cemetery
		$cemetery = Cemetery::findOrFail($id);

        $cemetery = Cemetery::with(['sections', 'sections.plots', 'sections.plots.burial'])->findOrFail($id);
        return view('cemetery.map', compact('cemetery'));
    }

	public function showMap($id)
	{
		$cemetery = Cemetery::with(['sections', 'sections.plots', 'sections.plots.burial'])->findOrFail($id);
		$section_list = Section::all();
		return view('cemetery.admin_map', compact('cemetery','section_list'));
	}

		public function createPlot(Request $request)
	{
		$plot = Plot::updateOrCreate(
			[
				'lat' => $request->lat,
				'lng' => $request->lng,
			],
			[
				'number' => $request->number,
				'status' => $request->status,
				'section_id' => $request->section_id,
			]
		);

		return response()->json([
			'success' => true,
			'message' => $plot->wasRecentlyCreated
				? 'Plot created successfully!'
				: 'Plot updated successfully!'
		]);
	}

	public function addBurialPlot()
	{
		
		$plot_id = \Request::get('plot_id');
		$deceased_name = \Request::get('deceased_name');
		$burial_date = \Request::get('burial_date');
		$notes = \Request::get('notes');
		$burial_status = \Request::get('burial_status');

		$burial = new Burial();
		$burial->deceased_name = $deceased_name;
		$burial->burial_date = $burial_date;
		$burial->notes = $notes;
		$burial->save();


		$plot_update = Plot::find($plot_id);
		if ($plot_update) {
			$plot_update->burial_id = $burial->id;
			$plot_update->status = $burial_status; 
			$plot_update->save();
		}

		return response()->json([
			'success' => true,
			'message' => 'Burial added and plot updated successfully!'
		]);
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
