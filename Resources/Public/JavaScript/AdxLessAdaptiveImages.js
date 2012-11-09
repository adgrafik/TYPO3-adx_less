/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 ************************************************************************************************
 *
 * @copyright 2012, Arno Dudek, http://www.adgrafik.at
 * @license The GNU General Public License, http://www.gnu.org/copyleft/gpl.html.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 *
 ************************************************************************************************
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


(function($){

	// Equals height of grid boxes.
	$.fn.adxAdaptiveImages = function(options){
		console.log(options);
		console.log($(this).html());
		var image = $($(this).html());
		console.log(image);
//		$(this).before($(this).html());
	}

	$(document).ready(function(){
		$('body').prepend($('<div class="adx-adaptive-images-test" style="position: absolute; top: -100em" />'));
	});

})(jQuery);