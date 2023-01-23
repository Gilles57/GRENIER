/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// require("jquery-ui-bundle");

// require jQuery normally
const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;


// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/custom.scss';

// start the Stimulus application
import './bootstrap';
