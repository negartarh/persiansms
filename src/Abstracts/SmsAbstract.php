<?php

namespace Negartarh\Persiansms\Abstracts;

use Negartarh\Persiansms\Interfaces\SmsInterface;

abstract class SmsAbstract implements SmsInterface {

	public function prepareMessage( ?string $text = '' ): string {

		return trim( str_replace( "\r\n", "\n", $text ) );

	}

	public function validateMobileNumber( ?string $number = '' ): bool {

        return self::validateMobileNumbers( [ $number ] )[0];
        
	}

	public function validateMobileNumbers( ?array $numbers = [] ): array {

		return array_map( function ( $number ) {

			$number = trim( strval( $number ) );

			return count( self::revivalMobileNumbers( [ $number ] ) ) === 1;

		}, $numbers );

	}

	public function revivalMobileNumbers( ?array $numbers = [], bool $unique = true ): array {

		$numbers = is_array( $numbers ) ? $numbers : [ strval( $numbers ) ];

		$numbers = array_map( fn( $number ) => strval( $number ), $numbers );

		$numbers = array_map( fn( $number ) => self::removeWhiteSpaces( $number ), $numbers );

		$numbers = array_map( fn( $number ) => self::removeDashes( $number ), $numbers );

		$numbers = array_map( fn( $number ) => self::enDigits( $number ), $numbers );

		$numbers = array_map( fn( $number ) => self::removeNonDigits( $number ), $numbers );

		$numbers = array_map( fn( $number ) => self::trim( $number ), $numbers );

		$numbers = array_map( fn( $number ) => self::fixIranNumbersPrefix( $number ), $numbers );

		$numbers = array_filter( $numbers, fn( $number ) => strlen( $number ) == 10 );

		$numbers = array_filter( $numbers, fn( $number ) => substr( $number, 0, 1 ) == '9' );

		$numbers = array_map( fn( $number ) => self::prependIranCallingCode( $number ), $numbers );

		if ( $unique ):
			$numbers = array_unique( $numbers );
		endif;

		return $numbers;
	}

	public function removeWhiteSpaces( ?string $text = '' ): string {
		return str_replace( ' ', '', preg_replace( '/\s+/', '', (string) $text ) );
	}

	public function removeDashes( ?string $text = '' ): string {
		return str_replace( [ '-', '_' ], '', (string) $text );
	}

	public function enDigits( ?string $text = '' ): string {

		foreach (
			[
				[ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ],
				[ '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' ],
			]
			as $numbers
		):
			$text = str_replace( $numbers, [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ], (string) $text );
		endforeach;

		return (string) $text;
	}

	public function removeNonDigits( string $text = '' ): string {
		return preg_replace( '/[^0-9]/', '', (string) $text );
	}

	public function trim( ?string $text ): string {
		return trim( strval( $text ) );
	}

	public function fixIranNumbersPrefix( ?string $number = '' ): string {

		$number = trim( strval( $number ) );

		if ( substr( $number, 0, 5 ) == '%2B98' ):
			return substr( $number, 5 );
		elseif ( substr( $number, 0, 5 ) == '%2b98' ):
			return substr( $number, 5 );
		elseif ( substr( $number, 0, 4 ) == '0098' ):
			return substr( $number, 4 );
		elseif ( substr( $number, 0, 3 ) == '098' ):
			return substr( $number, 3 );
		elseif ( substr( $number, 0, 3 ) == '+98' ):
			return substr( $number, 3 );
		elseif ( substr( $number, 0, 2 ) == '98' ):
			return substr( $number, 2 );
		elseif ( substr( $number, 0, 2 ) == '09' ):
			return substr( $number, 1 );
		endif;

		return $number;
	}

	public function prependIranCallingCode( ?string $number = '' ): string {
		return self::prependCallingCode( $number, '+98' );
	}

	public function prependCallingCode( ?string $number = '', ?string $code = '' ): string {

		$number = ltrim( $number, ' 0+' );

		return $code . $this->trim( $number );
	}

}