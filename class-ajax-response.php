<?php
namespace HIK\Framework\Ajax;

class Response {
	/**
	 * determine response success status
	 * @var boolean
	 */
	public $result = false;

	/**
	 * error message for description reason of failure
	 * @var string
	 */
	public $error_message = '';

	/**
	 * error code for reason of failure
	 * @var string
	 */
	public $error_code = 0;

	/**
	 * message for show to user after success of operation
	 * @var string
	 */
	public $success_message = '';

	/**
	 * user defined additional data and fields for response
	 */
	public $data = [];

	public function add_data( $name, $data ) {
		$this->data[ $name ] = $data;
	}

	public function send() {
        $response = [
			'result' => $this->result,
		];

		if ( $this->result ) {
			$response['success_message'] = $this->success_message;
		} else {
			$response['error_message'] = $this->error_message;
			$response['error_code'] = $this->error_code;
		}

		// add additional data to response
		foreach ( $this->data as $key => $value ) {
			$response[ $key ] = $value;
		}

		echo json_encode( $response, JSON_UNESCAPED_UNICODE );
		exit();
	}
}