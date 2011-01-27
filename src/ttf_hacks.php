<?php

// ttf_hacks.php - Image utility functions to work around lack of TTF support
// Copyright (c) 2011 Gordon P. Hemsley <me@gphemsley.org>
//
// ttf_hacks.php is part of phpSyntaxTree.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// $Id$

// If PHP is not compiled with FreeType support, we can't use TTF fonts.
// There are hacky workarounds for that problem.

define( 'STRING_FONT', 2 );
define( 'STRING_FONT_WIDTH', imagefontwidth( STRING_FONT ) );
define( 'STRING_FONT_HEIGHT', imagefontheight( STRING_FONT ) );

function xy_ttf2string( $old_x, $old_y )
{
	$new_x = $old_x;
	$new_y = $old_y - STRING_FONT_HEIGHT;

	return array( $new_x, $new_y );
}

function imagestringbbox( $text, $size, $x = 0, $y = 0 )
{
	$string_width = strlen( $text ) * STRING_FONT_WIDTH;

	$top = $y;
	$bottom = $y - STRING_FONT_HEIGHT;
	$left = $x;
	$right = $x + $string_width;

	return array( $left, $bottom, $right, $bottom, $right, $top, $left, $top );
}

if( !function_exists( 'imagettftext' ) )
{
	function imagettftext( $image, $size, $angle, $x, $y, $color, $fontfile, $text )
	{
		list( $x, $y ) = xy_ttf2string( $x, $y );

		if( imagestring( $image, STRING_FONT, $x, $y, $text, $color ) )
		{
			return imagestringbbox( $text, $size, $x, $y );
		}
		else
		{
			return FALSE;
		}
	}
}

if( !function_exists( 'imagettfbbox' ) )
{
	function imagettfbbox( $size, $angle, $fontfile, $text )
	{
		return imagestringbbox( $text, $size );
	}
}

?>