<?php
/*********************************************************************************************************************
 *
 * Filters file. Contains functionality that hooks into the admin interface to provide files.
 *
 *********************************************************************************************************************/

namespace jdp\WP\AttachmentCentrePoint;

class Filters
{
    /**
     * Binds all filters.
     */
    public static function init()
    {
        $sClass = 'jdp\\WP\\AttachmentCentrePoint\\Filters';
        add_filter('attachment_fields_to_edit', array($sClass, 'addFields'),  10, 2);
        add_filter('attachment_fields_to_save', array($sClass, 'saveFields'), 10, 2);
    }

    /**
     * Called when an attachment is edited: adds custom fields to the UI
     */
    public static function addFields($aFormField, $oPost)
    {
        // add main field
        $form_fields['centre_point'] = [
            'label'    => __('Centre point', 'jdp/wp/attachmentcentrepoint'),
            'input'    => 'text',
            'value'    => (get_post_meta($oPost->ID, 'centre_point', true) ?: '50% 50%'),
            'required' => true,
            'pattern'  => '(\d+)% (\d+)%'
        ];

        // cheat and add a trigger: there doesn’t seem to be a nicer way of doing this :/
        $form_fields['centre_point_trigger'] = [
            'label' => 'This space left intentionally blank',
            'input' => 'html',
            'html'  => "<script class=\"attachment-centre-point__trigger\" data-id=\"attachments-{$oPost->ID}-centre_point\">(function($) { $('body').trigger('jdp/wp/AttachmentCentrePoint/open'); })(jQuery);</script>"
        ];

        return $form_fields;
    }

    /**
     * Called when an attachment is saved: saves the content of the added fields.
     */
    public static function saveFields($aPost, $aField)
    {
        if (isset($aField['centre_point']))
        {
            update_post_meta($aPost['ID'], 'centre_point', $aField['centre_point']);
        }

        return $aPost;
    }

}
