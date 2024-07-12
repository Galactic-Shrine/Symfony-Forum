//import './bootstrap.js';
import CanvasConfetti from 'canvas-confetti';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 * bootstrap/dist/css/bootstrap.min.css
 */
//import 'bootstrap/dist/css/bootstrap.min.css';
import { Alert } from 'bootstrap';

document.body.addEventListener('click', () => {

  CanvasConfetti()
})

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
