<?php namespace App\Http\Controllers;

use App\Cemetery;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PDF;

class ReservationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$cemetery = Cemetery::with(['sections.plots' => function ($q) {
			$q->where('status', 'available');
		}])->first(); 

		return view('reservation.create', compact('cemetery'));
	}

	public function postCreate()
	{
		$name = \Request::get('name');
		$name_deceased = \Request::get('name_deceased');
		$relationship = \Request::get('relationship');
		$address = \Request::get('address');
		$number = \Request::get('number');
		$plot_id = \Request::get('plot_id');
		$payment_method = \Request::get('payment_method');
		// dd($payment_method);
		// dd(\Request::all());
		$date = date('Ymd'); 
		$prefix = 'R-' . $date . '-';

	
		$lastReservation = \App\Reservation::where('reservation_no', 'like', $prefix . '%')
			->orderBy('reservation_no', 'desc')
			->first();

		if ($lastReservation) {
			$lastNumber = (int) substr($lastReservation->reservation_no, -3);
			$newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
		} else {
			$newNumber = '001';
		}

		$reservation_no = $prefix . $newNumber;


		$reservation = new \App\Reservation();
		$reservation->name = $name;
		$reservation->name_deceased = $name_deceased;
		$reservation->relationship = $relationship;
		$reservation->address = $address;
		$reservation->number = $number;
		$reservation->plot_id = $plot_id;
		$reservation->payment_method = $payment_method;
		$reservation->status = 'pending';
		$reservation->reservation_no = $reservation_no;
		$reservation->date = date('Y-m-d'); 
		$reservation->save();

		// dd($reservation);

		$plot = Reservation::join('plots','reservation.plot_id','=','plots.id')->where('reservation.id',$reservation->id)
		->select('plots.number')->first();
		// dd($plot);

		return view('reservation.confirmation', [
			'reservation' => $reservation,
			'plot' => $plot
		]);
	}


	public function trackForm()
	{
		return view('reservation.track');
	}

	public function track()
	{
		$reservationNo = Input::get('reservation_no');
		$reservation = Reservation::where('reservation_no', $reservationNo)->first();

		if (!$reservation) {
			return back()->with('error', 'Reservation not found.');
		}

		return view('reservation.status', compact('reservation'));
	}

	public function gcashForm(Request $request)
	{
		// You can get all query params if needed
		$data = $request->all(); 
		

		// Show a view for Gcash payment confirmation
		return view('reservation.gcash', compact('data'));
	}

	public function paymayaForm(Request $request){
		// You can get all query params if needed
		$data = $request->all(); 
		

		// Show a view for Gcash payment confirmation
		return view('reservation.paymaya', compact('data'));
	}

	public function bankForm(Request $request){
		// You can get all query params if needed
		$data = $request->all(); 
		

		// Show a view for Gcash payment confirmation
		return view('reservation.bank', compact('data'));
	}

	public function viewCertificate($id)
	{
		$reservation = \App\Reservation::join('plots', 'reservation.plot_id', '=', 'plots.id')
			->select('reservation.*', 'plots.number as plot_number')
			->find($id);

		if (!$reservation) {
			abort(404, 'Reservation not found.');
		}

		$pdf = PDF::loadView('reservation.certificate', compact('reservation'));
		return $pdf->stream('Reservation-Certificate-' . $reservation->reservation_no . '.pdf');
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
	public function show($id)
	{
		//
	}

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
