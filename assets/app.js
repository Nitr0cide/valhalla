// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import 'bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/app.js');
bsCustomFileInput.init();

$("#search").click(function(){
   event.preventDefault();
   $.ajax({
      url: '/factures/ajaxrequest/',
      data: {'date1': $("#date1").val(), 'date2': $("#date2").val(), 'clientName': $("#value").val()},
      method: 'POST',
      success: function(data) {
         $("#results").html(data);
      }
   });
});