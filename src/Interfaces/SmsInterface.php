<?php

namespace Negartarh\Persiansms\Interfaces;

interface SmsInterface {


	public function prepareMessage( ?string $text = '' ): string;

	public function validateMobileNumber( ?string $number = '' ): bool;

	public function validateMobileNumbers( ?array $numbers = [] ): array;

	public function revivalMobileNumbers( ?array $numbers = [] ): array;

	public function removeWhiteSpaces( ?string $text = '' ): string;

	public function removeDashes( ?string $text = '' ): string;

	public function enDigits( ?string $text = '' ): string;

	public function removeNonDigits( string $text = '' ): string;

	public function trim( ?string $text ): string;

	public function fixIranNumbersPrefix( ?string $number = '' ): string;

	public function prependIranCallingCode( ?string $number = '' ): string;

	public function prependCallingCode( ?string $number = '', ?string $code = '' ): string;

	public function send(?string $text = '', array $numbers = [], array $options = []):array;

}