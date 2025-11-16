<?php namespace App\Http\Controllers;

use App\Burial;
use App\Cemetery;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Plot;
use App\Reservation;
use App\Section;
use Illuminate\Http\Request;
use Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt as FacadesCrypt;
use Yajra\Datatables\Datatables;
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

	public function reservation(){
		return view('cemetery.reservation');
	}

	public function reservationFetch()
	{
		$reservation_list = \App\Reservation::join('plots', 'reservation.plot_id', '=', 'plots.id')
			->select(
				'reservation.id',
				'reservation.name',
				'reservation.name_deceased',
				'reservation.relationship',
				'reservation.address',
				'reservation.number',
				'reservation.date',
				'reservation.reservation_no',
				'reservation.status',
				'reservation.payment_method',
				'plots.number as plot_number',
				'plots.price as plot_price'
			)
			->get();

		 return Datatables::of($reservation_list)->make(true);
	}

	public function reservationUpdateStatus()
	{
		$id = \Request::get('id');
		$status = \Request::get('status');

		$reservation = \App\Reservation::find($id);

		if (!$reservation) {
			return response()->json([
				'success' => false,
				'message' => 'Reservation not found.'
			], 404);
		}


		$reservation->status = $status;
		$reservation->save();

		return response()->json([
			'success' => true,
			'message' => 'Reservation status updated successfully.',
			'status' => $status
		]);
	}



	public function login(){
		 return view('cemetery.login');
	}

	public function show($encryptedId) {

		 $id = \Crypt::decrypt($encryptedId);

		// Find cemetery
		$cemetery = Cemetery::findOrFail($id);

        $cemetery = Cemetery::with(['sections', 'sections.plots', 'sections.plots.burial'])->findOrFail($id);
		// dd($cemetery);

		$statusColors = [
			'available'        => ['color' => '#30a52aff', 'label' => 'Available'],
			'sold'             => ['color' => '#ff000053', 'label' => 'Sold'],
			'reserved'         => ['color' => '#FFFF00',   'label' => 'Reserved'],
			'quitclaim'        => ['color' => '#ff00e6ff', 'label' => 'Quitclaim'],
			'restricted'       => ['color' => '#ff0000ff', 'label' => 'Restricted'],
			'sold_with_burial' => ['color' => '#800080',   'label' => 'Sold with Burial'],
		];
        return view('cemetery.map', compact('cemetery','statusColors'));
    }

	public function showMap($id)
	{
		$cemetery = Cemetery::with(['sections', 'sections.plots', 'sections.plots.burial'])->findOrFail($id);
		$section_list = Section::all();
		return view('cemetery.admin_map', compact('cemetery','section_list'));
	}

		public function createPlot(Request $request)
	{
		
		// dd(\Request::all());
		$plotData = [
			'number'           => $request->number,
			'section_id'       => $request->section_id,
			'lat'              => $request->lat,
			'lng'              => $request->lng,
			'status'           => $request->status,
			'remarks'          => $request->remarks,
			'cemetery_id'      => $request->cemetery_id,
			'owner_name'       => $request->owner_name,
			'owner_contact'    => $request->owner_contact,
			'date_purchased'   => $request->date_purchased,
			'transaction_type' => $request->transaction_type,
			'applicant_name' => $request->applicant_name,
			'applicant_contact' => $request->applicant_contact,
			'reservation_date' => $request->reservation_date,
			'reservation_expiry' => $request->reservation_expiry,
			'payment_status' => $request->payment_status,
			'prev_owner' => $request->prev_owner,
			'quitclaim_date' => $request->quitclaim_date,
			'restriction_reason' => $request->restriction_reason,
		];

		$plot = Plot::updateOrCreate(
			['lat' => $request->lat, 'lng' => $request->lng],
			$plotData
		);
		if ($request->status === 'sold_with_burial') {
			$plot->burials()->delete();
			foreach ($request->deceased as $d) {
				$plot->burials()->create([
					'deceased_name' => $d['name'],
					'date_of_birth'           => $d['dob'] ? : null,
					'date_of_death'           => $d['dod'] ? : null,
					'burial_date'   => $d['burial_date'] ? : null,
					'sex'           => $d['sex'],
				]);
			}
		}

		return response()->json([
			'success' => true,
			'message' => $plot->wasRecentlyCreated ? 'Plot created!' : 'Plot updated!',
			'plot'    => $plot->load('burials')
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
