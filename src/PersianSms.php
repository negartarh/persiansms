<?php

namespace Negartarh\Persiansms;

use Negartarh\Persiansms\Abstracts\SmsAbstract;

class PersianSms extends SmsAbstract {
	public array $config = [];
	public function __construct( ?array $config = [] ) {

		$this->config = array_merge( [
			'Username'    => '',
			'Password'    => '',
			'TO'          => '',
			'TEXT'        => '',
			'API'         => '',
			'FROM'        => '',
			'FLASH'       => '',
			'Internation' => '',
			'DATE'        => '',
		], $config );

	}

	public function setConfig( string $key, $value ): self {

		$this->config[ $key ] = $value;

		return $this;
	}

	public function getConfig( $key ) {
		return array_key_exists( $key, $this->config ) ? $this->config[ $key ] : null;
	}

	public function from( ?string $from ): self {
		return $this->setConfig( 'From', $from );
	}

	public function withUser( string $username ): self {
		return $this->setConfig( 'Username', $username );
	}

	public function withPass( string $password ): self {
		return $this->setConfig( 'Password', $password );
	}


	public function send( ?string $text = '', array $numbers = [], array $config = [], bool $unique = true ): array {

		$result = [];

		$text = $this->prepareMessage( $text );

		$numbers = $this->revivalMobileNumbers( $numbers, $unique );

		$config = array_merge( $this->config, $config );

		foreach ( [ 'TO', 'TEXT' ] as $key ):

			if ( array_key_exists( $key, $config ) ):

				$config[ $key ] = null;

				unset( $config[ $key ] );

			endif;

		endforeach;

		if ( is_countable( $numbers ) && count( $numbers ) ):

			for ( $index = 0; $index < count( $numbers ); $index ++ ):

				try {

					$curl = curl_init();
					curl_setopt( $curl, CURLOPT_URL, 'http://persian-sms.com/API/default.aspx' );
					curl_setopt( $curl, CURLOPT_POST, 1 );
					curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( array_merge( $config, [
						'TO'   => $numbers[ $index ],
						'TEXT' => $text,
					] ) ) );
					curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
					curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );

					$response = curl_exec( $curl );

					$result[ $index ] = [
						'error'      => curl_error( $curl ),
						'info'       => curl_getinfo( $curl ),
						'reference'  => $response,
						'originator' => $numbers[ $index ],
						'message'    => $text,
						'timestamp'  => microtime( true ),
					];

					curl_close( $curl );

				} catch ( \Exception $e ) {
					error_log( sprintf( 'Error while sending SMS to %s: %s', $numbers[ $index ], $e->getMessage() ) );
				}

			endfor;

		endif;

		return $result;

	}
}