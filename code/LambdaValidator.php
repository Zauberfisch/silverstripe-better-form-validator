<?php

class LambdaValidator extends BetterValidator {
	protected $callback, $validateFields, $requireFields;

	/**
	 * @param callable $callback
	 * @param bool|string[]|\FormField[]|\FieldList $validateFields
	 * @param bool|string[]|\FormField[]|\FieldList $requireFields
	 */
	public function __construct(callable $callback, $validateFields = true, $requireFields = false) {
		$this->callback = $callback;
		parent::__construct($validateFields, $requireFields);
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	public function php($data) {
		$valid = parent::php($data);
		$valid = call_user_func($this->callback, $data, $this->form, $this) && $valid;
		return $valid;
	}
}
