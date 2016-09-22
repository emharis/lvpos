// Page Load Make sure to load the correct location

$('document').ready(function(){
  hash = location.hash.substr(1).split('&');
  if(hash[0]){
    section = hash[0].replace('/', '');
    console.log(section);
    if(typeof section === "undefined" || section == '') section = 'dashboard';
    load_section( $('#' + section + '-section') );
  } else {
    load_section($('#dashboard-section'));
  }
});


// AJAX Buttons and Content

$('document').ready(function(){
    $('body').on('click', 'a.ajax', function(e) {
        console.log('hit');
        e.preventDefault(); // stop the browser from following the link      
        load_section($(this));        
    });

});

// Funtion to load section via Ajax

function load_section(target){

  header = (typeof target.data('header') === "undefined") ? target.html() : target.data('header');

  $('.content-header h1').html(header);
  $('section.content').html('<i class="ion ion-loading-c"></i>');
  
  
  $('.sidebar-menu li.active').removeClass('active');
  target.parent().addClass('active');

  url = target.attr('href');
  $('section.content').load(url + $('#sort_order').val()); // load the html response into a DOM element
  
  newUrl = "/admin/#/" + target.data('section');
  history.pushState({},'Admin Dashboard - comments',newUrl);
}

