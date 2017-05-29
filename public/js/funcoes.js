$(document).ready(function() {
 $("#telefone").setMask("(99) 999-99999");
});


var markers = [];
var infowindow = [];
var marker = [];
var Open = 0;

function initialize(location) {
  var mapOptions = {
    zoom: 2,
    center: new google.maps.LatLng(17.4939439,-13.8019042)
  };

  var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

  
  location.forEach(function(value, key){
    var latlngbounds = new google.maps.LatLngBounds();
    var contentString = '<strong>'+value[0]+'</strong><p>'+value[2]+'</p>';

    infowindow[key] = new google.maps.InfoWindow({
        content: contentString
    });
    var latlng = value[1].split(',');
    marker[key] = new google.maps.Marker({
        position: new google.maps.LatLng(latlng[0],latlng[1]),
        map: map,
        draggable:true,
        animation: google.maps.Animation.DROP,
        title: value[0]
    });  

    markers.push(marker[key]);
    latlngbounds.extend(marker[key].position);
    google.maps.event.addListener(marker[key], 'click', function() {      
        
      if (marker[key].getAnimation() != null) {
        marker[key].setAnimation(null);
      } else {
        marker[key].setAnimation(google.maps.Animation.BOUNCE);
      }

      infowindow[Open].close();
      infowindow[key].open(map,marker[key]);
      Open = key;

    });
  
  });
  var markerCluster = new MarkerClusterer(map, markers);
}


// pega a moeda e transforma para float
function moedaParaFloat(moeda){

   moeda = moeda.replace(".","");
   moeda = moeda.replace(",",".");
   return parseFloat(moeda);

}

// formata para moeda BR
function formataReal(num){
  x = 0;
  if(num<0){
    num = Math.abs(num);
    x = 1;
  }
  if(isNaN(num)) num = "0";
    cents = Math.floor((num*100+0.5)%100);
    num = Math.floor((num*100+0.5)/100).toString();
  if(cents < 10) cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    num = num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
    ret = num + ',' + cents;
    if (x == 1) ret = ' – ' + ret;

  return ret;
}

// Atualizar valores do catálogo
function atualizar_valores(input){
  var qtd = input.val();
  var valor = input.parent().parent().parent().find('td.valor').text();
  var subtotal = ((moedaParaFloat(valor)*1) * (qtd*1));
  input.parent().parent().parent().find('td.subtotal').text(formataReal(subtotal));
  var total = 0;
  $('form#form-catalogo table tbody tr td.subtotal').each(function(){
    total = total + ((moedaParaFloat($(this).text()))*1);
  });
  $('form#form-catalogo table tbody tr td.total').text(formataReal(total));

}

function atendimento_online(codigo){

  window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
  d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
  _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
  $.src='//v2.zopim.com/?'+codigo;z.t=+new Date;$.
  type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');

}


$(document).ready(function(){

  atendimento_online('1zmJhWx8L9A2raXPEgGFn5dSNqnn1JDF');

  if($('#getMaps input').length){     
    var arr_locations = [];
    $('#getMaps input').each(function(i, v){
      var all = $(this).val();
      var arr = all.split('|||'); 
      arr_locations.push(arr);
    });
   
    $(window).load(function(){
      initialize(arr_locations);
    });

  }

  // Catálogo
  $('form#form-catalogo input.submit').click(function(){
    $('form#form-catalogo').attr({'action':$('#form-finalizar').val()});
  })
  $('form#form-catalogo input.atualizar').click(function(){
    $('form#form-catalogo').attr({'action':$('#form-atualizar').val()});
  })
  $('form#form-catalogo table tbody tr td button.add').click(function(){
    var input = $(this).parent().find('input:text');
    var total = input.val();
    input.val((total*1) + 1);
    atualizar_valores(input);
    return false;
  });
  $('form#form-catalogo table tbody tr td button.rem').click(function(){
    var input = $(this).parent().find('input:text');
    var total = input.val();
    if(total == 1) return false;
    input.val((total*1) - 1);
    atualizar_valores(input);
    return false;
  });
  // Catálogo

  // Galeria de imagens
  $(".fancybox").fancybox();
  
  // Validação formulário de contato
  if($("#form_contato").length){
    $("#form_contato").validate({
      submitHandler: function(form) {
          var url  = $(form).attr('action');
          var data = $(form).serialize();
          var botao = $(form).find('.btn');
          botao.attr('disabled','disabled').text('Enviando...');;
          $.ajax({
            url: url,
            data: data,
            type: 'POST',
            dataType: 'json'
          }).done(function(response) {
            botao.removeAttr('disabled').text('Enviar');;
            if(response.sucesso){
              $('input').val('');
              $('textarea').val('');
              $("#erro_formulario").hide();
              $("#sucesso_formulario").show();
            } else {
              $("#sucesso_formulario").hide();
              $("#erro_formulario").show().text(response.mensagem);
            }
          });
      }
    });
  }
  
  // Validação formulário de newsletter
  if($("#form_newsletter").length){
    $("#form_newsletter").validate({
      submitHandler: function(form) {
          var url  = $(form).attr('action');
          var data = $(form).serialize();
          var botao = $(form).find('.btn');
          botao.attr('disabled','disabled');
          $.ajax({
            url: url,
            data: data,
            type: 'POST',
            dataType: 'json'
          }).done(function(response) {
            botao.removeAttr('disabled');
            if(response.sucesso){
              $('input').val('');
              $('textarea').val('');
              $("#erro_formulario").hide();
              $("#sucesso_formulario").show();
            } else {
              $("#sucesso_formulario").hide();
              $("#erro_formulario").show().text(response.mensagem);
            }
          });
      }
    });
  }
  
  // validação formulário de catálogo
  if($("#form_catalogo").length){
    $("#form_catalogo").validate({

    });
  }

});


