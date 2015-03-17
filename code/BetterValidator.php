<?php

/**
 * @author zauberfisch
 * @method void validationError(string $fieldName, string $message, string $messageType)
 */
class BetterValidator extends Validator {
	protected $validateFields, $requireFields;

	/**
	 * @param bool|string[]|\FormField[]|\FieldList $validateFields
	 * @param bool|string[]|\FormField[]|\FieldList $requireFields
	 */
	public function __construct($validateFields = true, $requireFields = false) {
		$this->validateFields = $validateFields;
		$this->requireFields = $requireFields;
		parent::__construct();
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	public function php($data) {
		$valid = true;
		if ($this->validateFields) {
			$valid = $this->validateFields($this->validateFields) && $valid;
		}
		if ($this->requireFields) {
			$valid = $this->requireFields($this->requireFields, $data) && $valid;
		}
		return $valid;
	}

	/**
	 * @param null|string[]|\FormField[]|\FieldList $fields
	 * @return bool
	 */
	public function validateFields($fields = null) {
		if (!is_array($fields) && !(is_object($fields) && $fields->is_a('FieldList'))) {
			$fields = $this->form->Fields();
		}
		$valid = true;
		foreach ($fields as $field) {
			$valid = $this->validateField($field) && $valid;
		}
		return $valid;
	}

	/**
	 * @param string|\FormField $field
	 * @return bool
	 */
	public function validateField($field) {
		return $this->getField($field)[0]->validate($this);
	}

	/**
	 * @param null|string[]|\FormField[]|\FieldList $fields
	 * @param array $data
	 * @return bool
	 */
	public function requireFields($fields = null, $data) {
		if (!is_array($fields) && !(is_object($fields) && $fields->is_a('FieldList'))) {
			$fields = $this->form->Fields();
		}
		$valid = true;
		foreach ($fields as $field) {
			$valid = ($this->requireField($field, $data) && $valid);
		}
		return $valid;
	}

	/**
	 * @param string|\FormField $field
	 * @param array $data
	 * @return bool
	 */
	public function requireField($field, $data) {
		list($field, $fieldName) = $this->getField($field);
		if (!isset($data[$fieldName]) || !$data[$fieldName]) {
			$this->validationError(
				$fieldName,
				_t(
					'Form.FIELDISREQUIRED',
					'{name} is required',
					['name' => strip_tags('"' . ($field->Title() ? $field->Title() : $fieldName) . '"')]
				),
				'required'
			);
			return false;
		}
		return true;
	}

	/**
	 * @param \FormField|string $field
	 * @return array
	 */
	protected function getField($field) {
		if (is_object($field) && $field->is_a('FormField')) {
			$fieldName = $field->getName();
		} else {
			$fieldName = $field;
			$field = $this->form->Fields()->dataFieldByName($field);
		}
		return [$field, $fieldName];
	}
}
