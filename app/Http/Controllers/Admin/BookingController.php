<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\BookingDetails;
use App\Models\Commission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        if($request->ajax()){
            //DB::enableQueryLog();
            $bookings = Booking::with('company', 'companyAddress', 'user')->get();
            //dd(DB::getQueryLog());
            return Datatables::of($bookings)
                ->addColumn('transaction_id', function($bookings){
                    return $bookings->transaction_id;
                })
                ->addColumn('company_name', function($bookings){
                    return $bookings->company->name;
                })
                ->addColumn('address', function($bookings){
                    return $bookings->companyAddress->address;
                })
                ->addColumn('user_name', function($bookings){
                    return $bookings->user->name;
                })
                ->addColumn('mobile_no', function($bookings){
                    return $bookings->user->country_code . ' ' . $bookings->user->mobile_no;
                })
                ->addColumn('booking_date', function($bookings){
                    return Carbon::parse($bookings->start_date)->format('d-m-Y') . '  -  ' . Carbon::parse($bookings->end_date)->format('d-m-Y');
                })
                ->addColumn('date', function($bookings){
                    return Carbon::parse($bookings->created_at)->format('d-m-Y H:i');
                })
                ->addColumn('total_day_rent', function($bookings){
                    return "$" . $bookings->total_day_rent;
                })
                ->addColumn('status', function($bookings){

                    if($bookings->status == 'Booked'){
                        $status = '<span class=" badge badge-primary">' . config('languageString.booked') . '</span>';
                    }
                    if($bookings->status == 'Completed'){
                        $status = '<span  class=" badge badge-success">' . config('languageString.completed') . '</span>';
                    }
                    if($bookings->status == 'Cancelled'){
                        $status = '<span  class=" badge badge-danger">' . config('languageString.cancelled') . '</span>';
                    }
                    if($bookings->status == 'Review'){
                        $status = '<span  class=" badge badge-warning">' . config('languageString.review') . '</span>';
                    }
                    if($bookings->status == 'Rejected'){
                        $status = '<span  class=" badge badge-info">' . config('languageString.rejected') . '</span>';
                    }
                    return $status;

                })
                ->addColumn('action', function($bookings){
                    $view_detail_button = '<button data-id="' . $bookings->id . '" id="booking-details_' . $bookings->id . '" class="booking-details btn btn-icon btn-secondary" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_details') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button>';
                    $action_button = '';
                    if($bookings->status == 'Review'){
                        $action_button = '<button data-id="' . $bookings->id . '" data-status="Cancelled" class="cancel-App\Modelsroved btn btn-icon btn-info" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.App\Modelsroved') . '"><i class="bx bx-message-square-check font-size-16 align-middle"></i></button>';
                        $action_button .= '<button data-id="' . $bookings->id . '" data-status="Rejected" class="cancel-App\Modelsroved btn btn-icon btn-danger" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.rejected') . '"><i class="bx bx-message-square-x font-size-16 align-middle"></i></button>';
                    }
                    return '<div class="btn-icon-list">' . $view_detail_button . ' ' . $action_button . '</div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.booking.index');
    }

    public function BookingDetails($id)
    {
        $value_id = $id;

        $booking = Booking::with([
            'company', 'user', 'vehicles' => function($query){
                $query->with([
                    'ryde' => function($q){
                        $q->with('brand', 'modelYear', 'color', 'rydeInstance');
                    },
                ]);
            }, 'companyAddress'           => function($query){
                $query->with('country');
            },
        ])->where('id', $value_id)->first();

        if($booking){
            $array['globalModalTitle'] = config('languageString.booking_id') . ' -' . $booking->transaction_id;

            $array['globalModalDetails'] = '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="4" class="text-center">' . config('languageString.booking_details') . ' : ' . $booking->company->name . ' | ' . $booking->companyAddress->address . ' | ' . $booking->user->name . ' | ' . $booking->user->mobile_no . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.status') . '</th><th>' . config('languageString.start_date') . '</th><th>' . config('languageString.end_date') . '</th><th>' . config('languageString.transaction_id') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td>' . $booking->status . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->start_date . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->end_date . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->transaction_id . '</td>';
            $array['globalModalDetails'] .= '</tr>';
            $array['globalModalDetails'] .= '</table>';


            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.ryde_details') . ': ' . $booking->vehicles->ryde->brand->name . ' | ' . $booking->vehicles->ryde->name . ' | ' . $booking->vehicles->ryde->modelYear->name . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.color') . '</th><th>' . config('languageString.hourly_amount') . '
        </th><th>' . config('languageString.daily_amount') . '</th><th>' . config('languageString.weekly_amount') . '</th><th>' . config('languageString.monthly_amount') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td>' . $booking->vehicles->ryde->color->name . '</td>';
            $array['globalModalDetails'] .= '<td>' . '$ ' . $booking->vehicles->hourly_amount . '</td>';
            $array['globalModalDetails'] .= '<td>' . '$ ' . $booking->vehicles->daily_amount . '</td>';
            $array['globalModalDetails'] .= '<td>' . '$ ' . $booking->vehicles->weekly_amount . '</td>';
            $array['globalModalDetails'] .= '<td>' . '$ ' . $booking->vehicles->monthly_amount . '</td>';
            $array['globalModalDetails'] .= '</tr>';
            $array['globalModalDetails'] .= '</table>';

            // further booking details
            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="4" class="text-center">' . config('languageString.booking_details') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.pick_up_location') . '</th><th>' . config('languageString.pick_up_latitude') . '</th><th>' . config('languageString.pick_up_longitude') . '</th><th>' . config('languageString.message') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td>' . $booking->pick_up_location . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->pick_up_latitude . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->pick_up_longitude . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->message . '</td>';
            $array['globalModalDetails'] .= '</tr>';
            $array['globalModalDetails'] .= '</table>';


            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="2" class="text-center">' . config('languageString.pricing') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.total_car_rent') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">$' . $booking->total_day_rent . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $extra_service_sum = BookingDetails::where('booking_id', $value_id)->sum('amount');
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.extra_service') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">$' . $extra_service_sum . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $total = $booking->sub_total;
            if($booking->total_tax > 0){
                $array['globalModalDetails'] .= '<tr>';
                $array['globalModalDetails'] .= '<td class="text-right">' . $booking->companyAddress->country->tax_name . '(' . $booking->tax_percentage . ')' . '</td>';
                $array['globalModalDetails'] .= '<td class="text-right">$' . $booking->total_tax . '</td>';
                $array['globalModalDetails'] .= '</tr>';
                $total += $booking->total_tax;
            }

            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.total') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">$' . $total . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $commission = Commission::where('booking_id', $value_id)->select('commission_percentage', 'commission_amount')->first();
            if($commission){
                $commission_percentage = $commission->commission_percentage;

                $commission_amount = ($booking->sub_total * $commission_percentage) / 100;
                $final_amount = $commission->commission_amount + $booking->adjustment;
            } else{
                $commission_percentage = 0;
                $commission_amount = 0;
                $final_amount = $booking->adjustment;
            }

            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.ryde_zilla_commissions') . ' (' . $commission_percentage . '%)</td>';
            $array['globalModalDetails'] .= '<td class="text-right">- $' . $commission_amount . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.adjustment') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">';
            $array['globalModalDetails'] .= '<div class="input-group mb-3">';
            $array['globalModalDetails'] .= '<input type="hidden" name="modal_booking_id" id="modal_booking_id"  value=' . $value_id . '>';
            $array['globalModalDetails'] .= '<input type="text" name="adjustment" id="adjustment" class="form-control" value="' . $booking->adjustment . '" placeholder="' . config('languageString.adjustment') . '" readonly>';
            $array['globalModalDetails'] .= '<div class="input-group-prepend">';
            $array['globalModalDetails'] .= '<button class="btn btn-outline-info edit_button" type="button">' . config('languageString.edit') . '</button>';
            $array['globalModalDetails'] .= '<button class="btn btn-outline-success d-none save_button" type="button">' . config('languageString.save') . '</button>';
            $array['globalModalDetails'] .= '<button class="btn btn-outline-secondary d-none cancel_button" type="button">' . config('languageString.cancel') . '</button>';
            $array['globalModalDetails'] .= '</div>';
            $array['globalModalDetails'] .= '</div>';
            $array['globalModalDetails'] .= '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.final_amount') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">$' . $final_amount . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $array['globalModalDetails'] .= '</table>';


        } else{
            $array['globalModalTitle'] = config('languageString.booking_id') . ' -' . $value_id;
            $array['globalModalDetails'] = config('languageString.error');
        }

        return response()->json(['success' => true, 'data' => $array]);
    }

    public function bookingStatusChange(Request $request)
    {
        $value_id = $request->input('value_id');
        $status = $request->input('status');

        Booking::where('id', $value_id)->update([
            'status' => $status,
        ]);

        Commission::where('booking_id', $value_id)->delete();

        return response()->json([
            'success' => true,
            'message' => config('languageString.booking_status_changed'),
        ]);
    }

    public function adjustmentSave(Request $request)
    {
        $validator_array = [
            'adjustment' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        $is_affected = Booking::where('id', $request->input('booking_id'))->update([
            'adjustment' => $request->input('adjustment'),
        ]);

        if($is_affected){
            $commission_value = Commission::where('booking_id', $request->input('booking_id'))->first();
            if($commission_value){

                Commission::where('booking_id', $request->input('booking_id'))->update([
                    'commission_amount' => $commission_value->commission_amount + $request->input('adjustment'),
                ]);
            }
        }
        return response()->json(['success' => true, 'message' => config('languageString.adjustment_successfully'),]);
    }


}
