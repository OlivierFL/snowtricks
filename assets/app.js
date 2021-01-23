/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (tailwind.css in this case)
import './styles/tailwind.css';

// AlpineJs used for modals
import 'alpinejs';

// Responsive navbar import
import './js/nav';

// Homepage load more tricks
import './js/load_more_tricks';

// Trick detail page load more comments
import './js/load_more_comments';

// start the Stimulus application
import './bootstrap';
