<?php
/*********************************************************************************************************************
 *
 * Provides functions for use in the template.
 *
 *********************************************************************************************************************/

namespace jdp\WP\AttachmentCentrePoint;

class TemplateFunctions
{
    /**
     * Returns the centre point for an attachment.
     *
     * @param   iAttachmentId   the ID of the attachment
     * @return  the centre point, in CSS-friendly x% y% format
     */
    public static function get_attachment_image_centre_point($iAttachmentId)
    {
        return get_post_meta($iAttachmentId, 'centre_point', true) ?: '50% 50%';
    }


    /**
     * Returns a style attribute containing the URL of the requested attachment formatted as a background-image, plus
     * its position as a background-position.
     *
     * This is argument-compatible with Wordpress’ core wp_get_attachment_image_url method.
     *
     * @param   iAttachmentId   (int)           Image attachment ID
     * @param   sSize           (array|string)  image size to retrieve. Accepts any valid image size, or an array of width
     *                                          and height values in pixels (in that order). (Optional, default: ‘thumbnail’)
     * @param   bIcon           (bool)          Whether the image should be treated as an icon (Optional, default: false)
     * @return  a valid ‘style’ attribute for the requested image, or false if an invalid attachment ID is specified
     */
    public static function get_attachment_style_attribute($iAttachmentId, $sSize = 'thumbnail', $bIcon = false)
    {
        // 0. sanity check
        $sUrl = wp_get_attachment_image_url($iAttachmentId, $sSize, $bIcon);
        if ($sUrl === false)
        {
            return false;
        }

        // 1. build the style attr
        $sAttr = 'style="background-image:url('.esc_attr($sUrl).');'.
                        'background-position:'.self::get_attachment_image_centre_point($iAttachmentId).';"';

        // 2.
        return $sAttr;
    }

    /** -- ALIASES TO OTHER METHODS -- */
    /**
     * Alias for get_attachment_image_centre_point, above, for our friends across the Atlantic =)
     */
    public static function get_attachment_image_center_point($iAttachmentId)
    {
        return self::get_attachment_image_centre_point($iAttachmentId);
    }

    /**
     * More friendly alias for get_attachment_image_centre_point to better suit the CSS-inclined.
     */
    public static function get_attachment_image_position($iAttachmentId)
    {
        return self::get_attachment_image_centre_point($iAttachmentId);
    }

}
