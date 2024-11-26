<?php



global $wpdb;

// Get the current user and folder
$current_user_id = get_current_user_id();
$current_folder_id  = bp_get_folder_folder_id();

// Query to get audio files
$audio_files = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT d.id, d.title, d.description, d.date_modified, d.status, dm.meta_value AS file_extension
        FROM {$wpdb->prefix}bp_document d
        JOIN {$wpdb->prefix}bp_document_meta dm ON d.id = dm.document_id
        WHERE d.user_id = %d 
        AND d.folder_id = %d
        AND dm.meta_key = 'extension'
        AND (dm.meta_value LIKE '.mp3')
        ",
        $current_user_id,
        $current_folder_id
    )
);
?>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Modified</th>
            <th>Visibility</th>
        </tr>
    </thead>
    <tbody>
        
   
<?php
foreach( $audio_files as $audio_file ){
    echo '<tr>';
    echo '<td>' . $audio_file->title .'</td>';
    echo '<td>' . date('Y-m-d', strtotime( $audio_file->date_modified ) ) .'</td>';
    echo '<td>' . $audio_file->status .'</td>';
    echo '</tr>';

}
?>
 </tbody>
</table>