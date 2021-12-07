<?php

/* Victor Zugadi victorzugadi@bamatic.com
 *
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * or see https://www.gnu.org/
 */

/**
 *  \file        htdocs/core/lib/images.lib.php
 *  \brief        Set of function for manipulating images
 */
function victor_audio_format_supported($file) {

    $regeximgext = 'wav|mp3|m4a';
    $reg = [];
    if (!preg_match('/('.$regeximgext.')$/i', $file['extension'], $reg)) return -1;
    return 0;
}
function victor_audio_link_supported($url) {

    $regeximgext = 'wav|mp3|m4a';
    $reg = [];
    if ( ( $pos = strrpos($url,'.') ) === false || $pos == 0) return -1;
    $extension = substr($url,$pos+1);
    if (!preg_match('/('.$regeximgext.')$/i', $extension, $reg)) return -1;
    return 0;
}