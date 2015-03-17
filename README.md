# SilverStripe Form Validator

## Work in progress

This module is still work in progress.
This module exists because of missing features in SilverStripe and might 
change its API or be completely obsolete once similar functionality is 
provided by SilverStripe.

## Installation

#### Installing the module

1. Install the module using composer or download and place in your project folder
2. Run `?flush=1`

## Usage

	$fields = new FieldList([
		new TextField('Name', _t('MyForm.Name', 'Name')),
		new CheckboxField('CallMe', _t('MyForm.CallMe', 'Do you want us to call you on your phone number?')),
		new TextField('Phone', _t('MyForm.Phone', 'Phone')),
		new EmailField('Email', _t('MyForm.Email', 'Email')),
		new TextareaField('Message', _t('MyForm.Message', 'Message')),
	]);
	
	$actions = new FieldList([
		new FormAction('SubmitMyForm', _t('MyForm.Submit', 'Submit')),
	]);

	$validateCallback = function ($data, Form $form, LambdaValidator $validator) {
		// using $valid is currently optional because a form is automatically 
		// considered invalid if there are any error messages 
		$valid = true;

		if (isset($data['CallMe']) && $data['CallMe']) {
			// if the call me checkbox is ticked, then a phone number is required
			if ($validator->requireField('Phone')) {
				$valid = false;
			}
			// requireField will automatically add an error message to the form and set it as invalid
		}

		if (isset($data['Message'])) {
			// Message is automatically required, but this code will still run even if its not set
			// so we have to check that it's set
			if (stripos($data['Message'], 'viagra') !== false && stripos($data['Message'], 'cheap')) {
				$valid = false;
				$validator->validationError(
					// fieldName
					'Message', 
					// message
					_t(
						'MyForm.NoViagra',
						'Sorry, but we are currently not looking for cheap Viagra'
					),
					// message type and css class
					'required' 
				);
			}
		}

		return $valid;
	};

	$validator = new LambdaValidator(
		// Parameter 1 - php callable to perform custom validation
		$validateCallback, 
		// Parameter 2 - boolean or array|FieldList of fields to validate
		// In this case boolean true to automatically validate all fields
		// Validate means check for correct value for a type, eg EmailField 
		// is valid if its empty or if a valid email address is entered
		true,  
		// Parameter 3 - boolean or array|FieldList of fields that are required (can not be empty)
		['Name', 'Email', 'Message']
	);

	// the above code can be in your own Form subclass or used in combination with the line below
	$form = new Form($controller, $name, $fields, $actions, $validator);

## License

	Copyright (c) 2015, Zauberfisch
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:
		* Redistributions of source code must retain the above copyright
		  notice, this list of conditions and the following disclaimer.
		* Redistributions in binary form must reproduce the above copyright
		  notice, this list of conditions and the following disclaimer in the
		  documentation and/or other materials provided with the distribution.
		* Neither the name Zauberfisch nor the names of other contributors may 
		  be used to endorse or promote products derived from this software 
		  without specific prior written permission.

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
	ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
	DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
	ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
