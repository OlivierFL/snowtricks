/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (tailwind.css in this case)
import './styles/tailwind.css';

// Responsive navbar import
import './js/nav';

// Modal
import './js/modal';

// Update Media Modal
import './js/updateModal';

// Update Cover Modal
import './js/coverModal';

// Load more tricks and comments
import './js/load_more';

// Used to display file name in edit profile form
import './js/avatar';

import './js/trickForm';

// start the Stimulus application
import './bootstrap';
