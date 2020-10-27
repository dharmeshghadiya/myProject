<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Thenextweb\PassGenerator;

class PassGenerateController extends Controller
{

    public function passGenerate()
    {
        //pass.com.hi5.rydezilla
        $pass_identifier =rand();  // This, if set, it would allow for retrieval later on of the created Pass

        $pass = new PassGenerator($pass_identifier);

        $pass_definition = [
            "formatVersion"      => 1,
            "passTypeIdentifier" => "pass.com.hi5.rydezilla",
            "serialNumber"       => "gT6zrHkaW",
            "teamIdentifier"     => "ZP6CHP5BS5",
            "webServiceURL"     =>  "https://example.com/passes/",
            "authenticationToken" =>  "vxwxd7J8AlNNFPS8k0a0FfUFtq0ewzFdc",
            "relevantDate"        => "2015-12-17T13:00-08:00",
            "organizationName"   => "Rydezilla",
            "description"   => "Boarding pass sample",
            "logoText"   => "ITechRoof",
            "foregroundColor"    => "rgb(255, 255, 255)",
            "backgroundColor"    => "rgb(206, 140, 53)",
            "locations"            => [[
                "latitude" => 37.97479,
                "longitude" => -1.131522,
            ]],
			"barcode"            => [
                "message"         => "Hello World",
                "format"          => "PKBarcodeFormatQR",
                "messageEncoding" => "iso-8859-1",
            ],
            "boardingPass"       => [
				"transitType"     => "PKTransitTypeTrain",
                "headerFields"    => [
                    [
                        "key"   => "destinationDate",
                        "label" => "Trip to: BCN-SANTS",
                        "value" => "15/09/2015",
						"changeMessage" => "Gate changed to %@."
                    ],
                ],
                "primaryFields"   => [
                    [
                        "key"           => "boardingTime",
                        "label"         => "MURCIA",
                        "value"         => "13:54",
                    ],
                    [
                        "key"   => "destination",
                        "label" => "BCN-SANTS",
                        "value" => "21:09",
                    ],

                ],
                "secondaryFields" => [
                    [
                        "key"   => "passenger",
                        "label" => "Passenger",
                        "value" => "J.DOE",
                    ]
                ],
                "auxiliaryFields" => [
                    [
                        "key"   => "train",
                        "label" => "Train TALGO",
                        "value" => "00264",
						"changeMessage" => "Boarding time changed to %@."
                    ],
                    [
                        "key"   => "car",
                        "label" => "Car",
                        "value" => "009",
                    ],
                    [
                        "key"   => "seat",
                        "label" => "Seat",
                        "value" => "04A",
                    ]
                ],
                "backFields"      => [
                    [
                        "key"   => "ticketNumber",
                        "label" => "Ticket Number",
                        "value" => "Extension of Validity\nIf after having commenced your journey, you are prevented from travelling within the period of validity of the Ticket by reason of illness, we may extend the period of validity of your Ticket until the date when you become fit to travel or until our first flight after such date, from the point where the journey is resumed on which space is available in the class of service for which the fare has been paid. Such illness must be attested to by a medical certificate. When the flight coupons remaining in the Ticket involve one or more Stopovers, the validity of such Ticket may be extended for not more than three months from the date shown on such a certificate. In such circumstances, we will similarly extend the period of validity of Tickets of the other members of your immediate family accompanying you.",
                    ]
                ],
            ],
        ];

        $pass->setPassDefinition($pass_definition);

        // Definitions can also be set from a JSON string
        // $pass->setPassDefinition(file_get_contents('/path/to/pass.json));

        // Add assets to the PKPass package


        $pass->addAsset(base_path('public/certificate/background.png'));
        $pass->addAsset(base_path('public/certificate/thumbnail.png'));
        $pass->addAsset(base_path('public/certificate/icon.png'));
        $pass->addAsset(base_path('public/certificate/logo.png'));

		//dd($pass);
        $pkpass = $pass->create();
        return new Response($pkpass, 200, [
            'Content-Transfer-Encoding' => 'binary',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="pass.pkpass"',
            'Content-length' => strlen($pkpass),
            'Content-Type' => PassGenerator::getPassMimeType(),
            'Pragma' => 'no-cache',
        ]);
    }
}
