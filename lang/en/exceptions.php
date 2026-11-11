<?php

declare(strict_types=1);

/*
 * Messages of the domain exceptions (Modules\Waste\Exceptions\*), shown to the operator as they are.
 */
return [
    'invalid_ewc_code' => [
        'malformed' => 'Invalid EWC code: ":code". Expected a six-digit code, e.g. 19 12 04.',
        'chapter_out_of_range' => 'Invalid EWC code: ":code". The chapter must be between 01 and 20.',
        'not_found' => 'EWC code #:id does not exist.',
        'inactive' => 'EWC code :code is not active.',
    ],
    'invalid_quantity' => [
        'not_positive' => 'Quantity must be greater than zero (given: :quantity).',
        'negative' => 'Quantity cannot be negative (given: :quantity).',
        'rejected_must_be_zero' => 'For rejected waste the accepted quantity must be zero (given: :quantity).',
    ],
    'hazard_mismatch' => [
        'missing_for_hazardous' => 'EWC code :code is hazardous: specify at least one hazardous property (HP).',
        'present_for_non_hazardous' => 'EWC code :code is not hazardous: hazardous properties (HP) are not allowed.',
    ],
    'invalid_register_entry' => [
        'register_not_found' => 'Register #:id does not exist.',
        'register_inactive' => 'Register ":register" is not active.',
        'cause_not_allowed' => 'Reason ":cause" is not allowed for an entry of type ":type".',
        'operation_required' => 'Reason ":cause" requires the recovery or disposal operation (R/D).',
        'counterpart_required' => 'Reason ":cause" requires the counterpart operator.',
        'counterpart_not_found' => 'Counterpart operator #:id does not exist.',
        'operation_in_future' => 'The operation date (:operation_at) cannot be in the future.',
        'load_references_on_load' => 'A load entry cannot reference other loads.',
        'load_references_missing' => 'An unload entry must reference at least one load.',
        'load_reference_invalid' => 'Entry #:id is not a load of this register.',
        'load_reference_ewc_mismatch' => 'Load #:id has EWC code :actual, different from the unload (:expected).',
        'load_reference_superseded' => 'Load #:id has been rectified: reference the rectification entry.',
        'fir_not_found' => 'Waste form #:id does not exist.',
        'fir_cancelled' => 'Waste form :fir is cancelled.',
        'fir_ewc_mismatch' => 'Waste form :fir has an EWC code different from :code.',
        'rectification_register_mismatch' => 'A rectification must be recorded on the same register as the original entry.',
        'rectification_type_mismatch' => 'A rectification must have the same type (load/unload) as the original entry.',
        'rectification_reason_missing' => 'Specify the rectification reason.',
    ],
    'immutable_fir' => [
        'fields' => 'Form :fir has already been issued: :fields cannot be changed. After the draft only status, travel dates, acceptance and the RENTRI number change.',
        'status' => 'Form :fir cannot move from status :from to status :to.',
        'deletion' => 'Form :fir has already been issued and cannot be deleted: cancel it instead.',
    ],
    'immutable_register_entry' => [
        'transmitted' => 'Entry :year/:number has been transmitted to RENTRI and cannot be changed: record a rectification.',
        'numbering' => 'Register, year, number and recording date of entry :year/:number cannot be changed.',
        'deletion' => 'Entry :year/:number cannot be deleted: record a rectification.',
    ],
    'register_entry_already_rectified' => 'Entry :year/:number has already been rectified: rectify the rectification entry.',
    'invalid_fir_status_transition' => [
        'not_allowed' => 'The waste form cannot move from status ":from" to status ":to".',
        'acceptance_requires_data' => 'Status ":to" is set by recording the acceptance at destination, with the accepted quantity.',
        'invalid_initial_status' => 'A waste form cannot be created with status ":status".',
        'not_ready_for_transport' => 'A paper form cannot be issued without :fields: fill them in with «Edit» while it is a draft.',
        'fields' => [
            'number' => 'form number',
            'transporter_operator_id' => 'carrier',
        ],
    ],
    'invalid_fir' => [
        'operator_not_found' => 'Operator #:id does not exist.',
        'operator_role_mismatch' => 'Operator ":operator" does not have the required role (:role).',
        'site_not_of_operator' => 'Site #:id does not belong to ":operator".',
        'reason_required' => 'Outcome ":acceptance" requires a reason.',
        'duplicate_number' => 'A waste form with number :number already exists.',
    ],
    'ewc_csv_import' => [
        'unreadable' => 'CSV file not readable: :path.',
        'invalid_row' => 'Invalid row :line: :reason',
    ],
];
