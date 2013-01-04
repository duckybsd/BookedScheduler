<?php
/**
Copyright 2012 Nick Korbel

This file is part of phpScheduleIt.

phpScheduleIt is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

phpScheduleIt is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with phpScheduleIt.  If not, see <http://www.gnu.org/licenses/>.
 */

class ReservationCreatedResponse extends RestResponse
{
	public $referenceNumber;

	public function __construct(IRestServer $server, $referenceNumber)
	{
		$this->message = 'The reservation was created';
		$this->referenceNumber = $referenceNumber;
		$this->AddService($server, WebServices::GetReservation, array(WebServiceParams::ReferenceNumber => $referenceNumber));
	}

	public static function Example()
	{
		return new ExampleReservationCreatedResponse();
	}
}

class ReservationUpdatedResponse extends RestResponse
{
	public $referenceNumber;

	public function __construct(IRestServer $server, $referenceNumber)
	{
		$this->message = 'The reservation was updated';
		$this->referenceNumber = $referenceNumber;
		$this->AddService($server, WebServices::GetReservation, array(WebServiceParams::ReferenceNumber => $referenceNumber));
	}

	public static function Example()
	{
		return new ExampleReservationCreatedResponse();
	}
}

class ReservationDeletedResponse extends RestResponse
{
	public function __construct()
	{
		$this->message = 'The reservation was deleted';
	}

	public static function Example()
	{
		return new ReservationDeletedResponse();
	}
}

class ExampleReservationCreatedResponse extends ReservationCreatedResponse
{
	public function __construct()
	{
		$this->referenceNumber = 'referenceNumber';
		$this->AddLink('http://url/to/reservation', WebServices::GetReservation);
	}
}
?>