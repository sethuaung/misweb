<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backpack Crud Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the CRUD interface.
    | You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    // Forms
    'save_action_save_and_new' => 'រក្សាទុកនិងបង្កើតថ្មី',
    'save_action_save_and_edit' => 'រក្សាទុកនិងកែប្រែ',
    'save_action_save_and_back' => 'រក្សាទុកនិងត្រឡប់មកវិញ',
    'save_action_changed_notification' => 'ឥរិយាបថលំនាំដើមបន្ទាប់ពីការរក្សាទុកត្រូវបានផ្លាស់ប្តូរ.',

    // Create form
    'add' => 'បន្ថែម',
    'back_to_all' => 'ត្រលប់ទៅទាំងអស់ ',
    'cancel' => 'បោះបង់',
    'add_a_new' => 'បន្ថែមថ្មី ',

    // Edit form
    'edit' => 'កែសម្រួល',
    'save' => 'រក្សាទុក',

    // Revisions
    'revisions' => 'Revisions',
    'no_revisions' => 'No revisions found',
    'created_this' => 'created this',
    'changed_the' => 'changed the',
    'restore_this_value' => 'Restore this value',
    'from' => 'ពី',
    'to' => 'ដល់',
    'undo' => 'Undo',
    'revision_restored' => 'Revision successfully restored',
    'guest_user' => 'Guest User',

    // Translatable models
    'edit_translations' => 'EDIT TRANSLATIONS',
    'language' => 'ភាសា',

    // CRUD table view
    'all' => 'All ',
    'in_the_database' => 'in the database',
    'list' => 'បញ្ជី',
    'actions' => 'ដំណើរការ',
    'preview' => 'មើលជាមុន',
    'delete' => 'លុប',
    'admin' => 'អ្នកគ្រប់គ្រង',
    'details_row' => 'This is the details row. Modify as you please.',
    'details_row_loading_error' => 'There was an error loading the details. Please retry.',

    // Confirmation messages and bubbles
    'delete_confirm' => 'តើអ្នកប្រាកដជាចង់លុបមែនឬទេ?',
    'delete_confirmation_title' => 'Item Deleted',
    'delete_confirmation_message' => 'The item has been deleted successfully.',
    'delete_confirmation_not_title' => 'NOT deleted',
    'delete_confirmation_not_message' => "There's been an error. Your item might not have been deleted.",
    'delete_confirmation_not_deleted_title' => 'Not deleted',
    'delete_confirmation_not_deleted_message' => 'Nothing happened. Your item is safe.',

    'ajax_error_title' => 'Error',
    'ajax_error_text' => 'Error loading page. Please refresh the page.',

    // DataTables translation
    'emptyTable' => 'មិនមានទិន្នន័យនៅក្នុងតារាង',
    'info' => 'បង្ហាញ _START_ ទៅ _END_ នៃ _TOTAL_ កំណត់ត្រាទាំងអស់',
    'infoEmpty' => 'បង្ហាញ 0 to 0 of 0 កំណត់ត្រាទាំងអស់',
    'infoFiltered' => '(filtered from _MAX_ total កំណត់ត្រាទាំងអស់)',
    'infoPostFix' => '',
    'thousands' => ',',
    'lengthMenu' => '_MENU_ ក្នុងមួយទំព័រ',
    'loadingRecords' => 'កំពុងផ្ទុក...',
    'processing' => 'កំពុងដំណើរការ...',
    'search' => 'ស្វែងរក: ',
    'zeroRecords' => 'មិនមានទិន្នន័យស្វែងរកនោះទេ',
    'paginate' => [
        'first' => 'ទីមួយ',
        'last' => 'ចុងក្រោយ',
        'next' => 'បន្ទាប់',
        'previous' => 'មុន',
    ],
    'aria' => [
        'sortAscending' => ': activate to sort column ascending',
        'sortDescending' => ': activate to sort column descending',
    ],
    'export' => [
        'copy' => 'ចម្លង',
        'excel' => 'Excel',
        'csv' => 'CSV',
        'pdf' => 'PDF',
        'print' => 'បោះពុម្ព',
        'column_visibility' => 'ភាពមើលឃើញជួរឈរ',
    ],

    // global crud - errors
    'unauthorized_access' => 'Unauthorized access - you do not have the necessary permissions to see this page.',
    'please_fix' => 'Please fix the following errors:',

    // global crud - success / error notification bubbles
    'insert_success' => 'The item has been added successfully.',
    'update_success' => 'The item has been modified successfully.',

    // CRUD reorder view
    'reorder' => 'Reorder',
    'reorder_text' => 'Use drag&drop to reorder.',
    'reorder_success_title' => 'Done',
    'reorder_success_message' => 'Your order has been saved.',
    'reorder_error_title' => 'Error',
    'reorder_error_message' => 'Your order has not been saved.',

    // CRUD yes/no
    'yes' => 'បាទ/ចាស',
    'no' => 'ទេ',

    // CRUD filters navbar view
    'filters' => 'ការច្រោះយក',
    'toggle_filters' => 'Toggle filters',
    'remove_filters' => 'សម្អាតការច្រោះ',

    // Fields
    'browse_uploads' => 'Browse uploads',
    'clear' => 'ជម្រះ',
    'page_link' => 'Page link',
    'page_link_placeholder' => 'http://example.com/your-desired-page',
    'internal_link' => 'Internal link',
    'internal_link_placeholder' => 'Internal slug. Ex: \'admin/page\' (no quotes) for \':url\'',
    'external_link' => 'External link',
    'choose_file' => 'ជ្រើសរើសរូបភាព',

    //Table field
    'table_cant_add' => 'Cannot add new :entity',
    'table_max_reached' => 'Maximum number of :max reached',

    // File manager
    'file_manager' => 'កម្មវិធី​គ្រប់​គ្រង​ឯកសារ',
];
