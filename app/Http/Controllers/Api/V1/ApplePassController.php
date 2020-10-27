<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Booking;
use Illuminate\Http\Response;
use Thenextweb\PassGenerator;
use App\Http\Controllers\Controller;

class ApplePassController extends Controller
{

    public function index($id)
    {
        //pass.com.hi5.rydezilla
        //ZP6CHP5BS5

        $booking = Booking::with([
            'company', 'vehicles' => function($query){
                $query->with([
                    'ryde' => function($query){
                        $query->with(['brand', 'modelYear', 'rydeInstance']);
                    },
                ]);
            },
        ])->where('id', $id)->first();
        if($booking){

            //dd($booking->vehicles->ryde->brand->translate('en')->name);

            $pass_identifier = rand();  // This, if set, it would allow for retrieval later on of the created Pass

            $pass = new PassGenerator($pass_identifier);

            $pass_definition = [
                "formatVersion"       => 1,
                "passTypeIdentifier"  => "pass.com.hi5.rydezilla",
                "serialNumber"        => "ryde" . $id,
                "teamIdentifier"      => "ZP6CHP5BS5",
                "webServiceURL"       => "http://dealer.rydezilla.co/passes/",
                "authenticationToken" => "vxwxd7J8AlNNFPS8k0a0FfUFtq0ewzFdc",
                "relevantDate"        => "2015-12-17T13:00-08:00",
                "organizationName"    => "Rydezilla",
                "description"         => "Pass",
                "logoText"            => "Rydezilla",
                "foregroundColor"     => "rgb(255, 255, 255)",
                "backgroundColor"     => "rgb(44, 44, 44)",
                "labelColor"          => "rgb(255, 255, 255)",
                "locations"           => [
                    [
                        "latitude"  => (float)$booking->pick_up_latitude,
                        "longitude" => (float)$booking->pick_up_longitude,
                    ],
                ],
                "eventTicket"         => [
                    "headerFields"    => [
                        [
                            "key"   => "destinationDate",
                            "label" => '',
                            "value" => '',
                        ],
                    ],
                    "primaryFields"   => [
                        [
                            "key"   => "event-name",
                            "label" => $booking->vehicles->ryde->name,
                            "value" => $booking->vehicles->ryde->modelYear->name,
                        ],
                    ],
                    "secondaryFields" => [

                        [
                            "key"   => "event-name",
                            "label" => "Pickup Date",
                            "value" => date("d.m.Y H:i A", strtotime($booking->start_date)),
                        ],
                        [
                            "key"   => "event-name",
                            "label" => "Drop Off Date",
                            "value" => date("d.m.Y H:i A", strtotime($booking->end_date)),
                        ],
                    ],
                    "auxiliaryFields" => [
                        [
                            "key"   => "train",
                            "label" => $booking->company->name,
                            "value" => $booking->pick_up_location,
                        ],
                    ],
                ],
            ];
            //dd($pass_definition)

            $pass->setPassDefinition($pass_definition);

            // Definitions can also be set from a JSON string
            // $pass->setPassDefinition(file_get_contents('/path/to/pass.json));

            // Add assets to the PKPass package

            // $image_name = $booking->vehicles->ryde->image;
            $pass->addAsset(base_path('public/certificate/background.png'));
            //  $pass->addAsset(base_path('public/certificate/thumbnail.png'));
            $pass->addAsset(base_path('public/certificate/icon.png'));
            $pass->addAsset(base_path('public/certificate/logo.png'));

            //dd($pass);
            $pkpass = $pass->create();
            return new Response($pkpass, 200, [
                'Content-Transfer-Encoding' => 'binary',
                'Content-Description'       => 'File Transfer',
                'Content-Disposition'       => 'attachment; filename="pass.pkpass"',
                'Content-length'            => strlen($pkpass),
                'Content-Type'              => PassGenerator::getPassMimeType(),
                'Pragma'                    => 'no-cache',
            ]);
        } else{
            return response()->json([
                'success' => true,
                'message' => Config('languageString.error'),
            ], 422);
        }
    }
}
