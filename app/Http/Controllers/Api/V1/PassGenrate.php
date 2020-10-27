<?php

namespace App\Http\Controllers\Api\V1;

use Thenextweb\PassGenerator;
use App\Http\Controllers\Controller;

class PassGenrate extends Controller
{
    public function index()
    {

        $pass_identifier = '11';

        $pass = new PassGenerator($pass_identifier);

        $pass_definition = [
            "description"        => "description",
            "formatVersion"      => 1,
            "organizationName"   => "organization",
            "passTypeIdentifier" => "pass.com.hi5.rydezilla",
            "serialNumber"       => "123456",
            "teamIdentifier"     => "teamid",
            "foregroundColor"    => "rgb(99, 99, 99)",
            "backgroundColor"    => "rgb(212, 212, 212)",
            "barcode"            => [
                "message"         => "encodedmessageonQR",
                "format"          => "PKBarcodeFormatQR",
                "altText"         => "altextfortheQR",
                "messageEncoding" => "utf-8",
            ],
            "boardingPass"       => [
                "headerFields"    => [
                    [
                        "key"   => "destinationDate",
                        "label" => "Trip to: BCN-SANTS",
                        "value" => "15/09/2015",
                    ],
                ],
                "primaryFields"   => [
                    [
                        "key"           => "boardingTime",
                        "label"         => "MURCIA",
                        "value"         => "13:54",
                        "changeMessage" => "Boarding time has changed to %@",
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
                    ],
                    [
                        "key"   => "bookingref",
                        "label" => "Booking Reference",
                        "value" => "4ZK6FG",
                    ],
                ],
                "auxiliaryFields" => [
                    [
                        "key"   => "train",
                        "label" => "Train TALGO",
                        "value" => "00264",
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
                    ],
                    [
                        "key"   => "classfront",
                        "label" => "Class",
                        "value" => "Tourist",
                    ],
                ],
                "backFields"      => [
                    [
                        "key"   => "ticketNumber",
                        "label" => "Ticket Number",
                        "value" => "7612800569875",
                    ], [
                        "key"   => "passenger-name",
                        "label" => "Passenger",
                        "value" => "John Doe",
                    ], [
                        "key"   => "classback",
                        "label" => "Class",
                        "value" => "Tourist",
                    ],
                ],
                "locations"       => [
                    [
                        "latitude"     => 37.97479,
                        "longitude"    => -1.131522,
                        "relevantText" => "Departure station",
                    ],
                ],
                "transitType"     => "PKTransitTypeTrain",
            ],
        ];

        $pass->setPassDefinition($pass_definition);

        $pass->addAsset(base_path('assets/img/brand/logo-white.png'));
        $pass->addAsset(base_path('assets/img/brand/logo-white.png'));
        $pass->addAsset(base_path('assets/img/brand/logo-white.png'));
        $pass->addAsset(base_path('assets/img/brand/logo-white.png'));

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
