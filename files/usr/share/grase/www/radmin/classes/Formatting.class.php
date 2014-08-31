<?php

/* Copyright 2008 Timothy White */

/*  This file is part of GRASE Hotspot.

    http://grasehotspot.org/

    GRASE Hotspot is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    GRASE Hotspot is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GRASE Hotspot.  If not, see <http://www.gnu.org/licenses/>.
*/

class Formatting
{
    static public function formatBytes($bytes=0)
    {
        $kb = 1024;
        $mb = $kb*1024;
        $gb = $mb*1024;

        if(!isset($bytes)) return ""; // Unlimited needs to display as blank
        
        $bytes = $bytes + 0; // Should never be needed now as unlimited ^^

        if ($bytes >= $gb)
        {
            $output = \Grase\Locale::localeNumberFormat(sprintf ("%01.2f",$bytes/$gb)) . " GiB";
        }elseif ($bytes >= $mb)
        {
            $output = \Grase\Locale::localeNumberFormat(sprintf ("%01.2f",$bytes/$mb)) . " MiB";
        }
        elseif ( $bytes >= $kb )
        {
            $output = \Grase\Locale::localeNumberFormat(sprintf ("%01.0f",$bytes/1024)) . " KiB";
        }
        elseif ($bytes == 1 )
        {
            $output = \Grase\Locale::localeNumberFormat($bytes) . " B";
        }
        else
        {
            $output = \Grase\Locale::localeNumberFormat($bytes) . " B";
        }
     
        return $output;
    }

    static public function formatBits($bits=0)
    {
        $kb = 1024;
        $mb = $kb*1024;
        $gb = $mb*1024;

        if(!isset($bits)) return ""; // Unlimited needs to display as blank
        
        $bits = $bits + 0; // Should never be needed now as unlimited ^^

        if ($bits >= $gb)
        {
            $output = \Grase\Locale::localeNumberFormat(sprintf ("%01.2f",$bits/$gb)) . " Gibit/s";
        }elseif ($bits >= $mb)
        {
            $output = \Grase\Locale::localeNumberFormat(sprintf ("%01.2f",$bits/$mb)) . " Mibit/s";
        }
        elseif ( $bits >= $kb )
        {
            $output = \Grase\Locale::localeNumberFormat(sprintf ("%01.0f",$bits/1024)) . " Kibit/s";
        }
        elseif ($bits == 1 )
        {
            $output = \Grase\Locale::localeNumberFormat($bits) . " bit/s";
        }
        else
        {
            $output = \Grase\Locale::localeNumberFormat($bits) . " bit/s";
        }
     
        return $output;
    }

    static public function formatSec($seconds = 0)
    {
	    $minutes = intval($seconds / 60 % 60);
	    $hours = intval($seconds / 3600 % 24);
	    $days = intval($seconds / 86400);
	    $seconds = intval($seconds % 60);
	    if($days < 1) return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
	    if($days == 1) return sprintf("%dd %02d:%02d:%02d", $days, $hours, $minutes, $seconds);
	    return sprintf("%dd %02d:%02d:%02d", $days, $hours, $minutes, $seconds);
    }
}

// Formatting::formatBytes(1024*1024*2.56*1024);

?>
