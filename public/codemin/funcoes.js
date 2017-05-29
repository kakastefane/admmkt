$(document).ready(function(){

	$('table a.confirmar-excluir').click(function(){
		if(!confirm('Deseja realmente excluir?')){
			return false;
		}

		var id = $(this).attr("data-id");
		var tabela = $(this).attr("data-tabela");

		$.ajax({
			type: "POST",
			dataType: "json",
			url: "./gercont/" + tabela + "/excluir_ajax/" + id
		}).done(function(result) {
			if(result.sucesso){
				$("#registro_" + id).remove();
			} else {
				alert("Houve um erro ao excluir!");
			}
		});

		return false;

	});

	$('div.well a.confirmar-excluir').click(function(){
		if(!confirm('Deseja realmente excluir?')){
			return false;
		}
	});

	$('input[type="text"]').setMask();

	var funcao = $('form.area-menu .campo-funcao input:checked').val();
  modificar_menu_funcao(funcao);

  $('form.area-menu').on('change','.campo-funcao input',function(){

    var funcao = $(this).val();
    modificar_menu_funcao(funcao);

  });

  $("table").on('click','td.ativo span',function(){

		var status = $(this);

  	var id = status.attr('data-id');
		var tabela = status.attr("data-tabela");

		$.ajax({
			type: "POST",
			dataType: "json",
			url: "./administrador/" + tabela + "/ativo_ajax/" + id
		}).done(function(result) {
			if(result.sucesso){
				if(result.ativo){
					status.text('Ativo').addClass('label-success').removeClass('label-inverse');
				} else {
					status.text('Inativo').addClass('label-inverse').removeClass('label-success');
				}
			} else {
				alert("Houve um erro ou você não tem permissão!");
			}
		});

		return false;

  });

	$('select[name=status], select[name=idioma]').change(function(){
		var ativo = $('select[name=status]').val();
		var idioma = $('select[name=idioma]').val();
		$('tbody tr').show();
		if(ativo == 'ativo'){
			$('tbody td.ativo span').not('.label-success').parent().parent().hide();
		}else if(ativo == 'inativo'){
			$('tbody td.ativo span').not('.label-inverse').parent().parent().hide();
		}

		if(idioma != 0){
			$('tbody td.idioma input').not('[value='+idioma+']').parent().parent().hide();
		}
	});

});

function modificar_menu_funcao(funcao){

  switch (funcao) {
  	default:
		case 0:
		case '0':
			$('.campo-link_conteudo').hide();
			$('.campo-link_gercont').hide();
			$('.campo-link_manual').show();
			break;

		case 1:
		case '1':
			$('.campo-link_manual').hide();
			$('.campo-link_gercont').hide();
			$('.campo-link_conteudo').show();
			break;

		case 2:
		case '2':
			$('.campo-link_conteudo').hide();
			$('.campo-link_manual').hide();
			$('.campo-link_gercont').show();
			break;
	}

}

function opcoes(base_url,campo,tabela,tipo){

	// Adicionar Opção
	$("#modal-" + campo + " > .modal-body > .controls-row > input:button").click(function(){

		var botao = $(this);
		botao.attr("disabled", "disabled");
		var opcao = botao.parent().children("input:text").val();

		$.ajax({
			type: "POST",
			data: "tabela=" + tabela + "&campo=" + campo + "&opcao=" + opcao + "&tipo=" + tipo,
			url: base_url + 'adicionar'
		}).done(function(result) {
			var resultado = jQuery.parseJSON(result);
			if(tipo == 'select'){
				$('select[name="' + campo + '"]').append('<option value="' + resultado.id + '">' + resultado.valor + '</option>');
			} else if(tipo == 'checkbox'){
				$('#label_' + campo).append('<label class="checkbox"><input type="checkbox" name="' + campo + '[]" value="' + resultado.id + '">' + resultado.valor + '</label>');
			} else if(tipo == 'radio'){
				$('#label_' + campo).append('<label class="radio"><input type="radio" name="' + campo + '[]" value="' + resultado.id + '">' + resultado.valor + '</label>');
			}
			botao.parent().parent().children('table').children('tbody')
			.append('<tr><td>' + opcao + '</td><td><input type="button" value="Remover" class="btn btn-danger"/><input type="hidden" value="' + resultado.id + '"/>');
			botao.removeAttr("disabled");
			botao.parent().children("input:text").val('');
		});

	});

	// Remover Opção
	$(document).on("click", "#modal-" + campo + " > .modal-body > table > tbody > tr > td > input:button", function(){

		var linha = $(this).parent().parent();
		var id = linha.find('input:hidden').val();

		$.ajax({
			type: "POST",
			data: "id=" + id,
			url: base_url + 'remover'
		}).done(function(result) {
			var resultado = jQuery.parseJSON(result);
			if(resultado.success == true){
				if(tipo == 'select'){
					$('select[name="' + campo + '"] option[value=' + id + ']').remove();
				} else if(tipo == 'checkbox' || tipo == 'radio'){
					$('#label_' + campo + ' input[value=' + id + ']').parent().remove();
				}
				linha.remove();
			}
		});

	});
}

function videos(base_url,tabela,campo,id){

	$('#modal-' + campo).find('input.btn-success').click(function(){
		var botao = $(this);
		botao.attr("disabled", "disabled");
		var video = botao.parent().find('input[type="text"]').val();
		$.ajax({
			type: "POST",
			dataType: "json",
			data: "video=" + video + '&tabela=' + tabela + '&campo=' + campo + '&id=' + id,
			url: base_url
		}).done(function(resultado) {
			$('#modal-' + campo).find('table tbody').prepend('<tr><td>' + resultado.capa + '</td><td>' + resultado.link + '</td><td>' + resultado.botao + '</td></tr>');
			botao.removeAttr("disabled");
			botao.parent().children("input:text").val('');
		});
	});

	$('#modal-' + campo).find('table tr td button.btn-danger').on('click',function(){
		var linha = $(this).parent().parent();
		var id = $(this).children('input').val();
		$.ajax({
			type: "POST",
			dataType: "json",
			data: "id=" + id,
			url: base_url
		}).done(function(resultado) {
			if(resultado.deletado){
				linha.fadeOut(250);
			}
		});
	});

}

function google_maps(id,lat,lng){
	$(window).load(function(){
			initialize(id, lat, lng);
	});
}
var map;
var myLatlng = null;
function initialize(id, lat, lng) {
  geocoder = new google.maps.Geocoder(); 
  myLatlng = new google.maps.LatLng(lat, lng);
  var zoom = null;
  if(myLatlng.d == 0 && myLatlng.e == 0){
  	zoom = 2;
  }else{
  	zoom = 16;
  }
  var mapOptions = {
    center: myLatlng,
    zoom: zoom
  };
  var map = new google.maps.Map(document.getElementById('map-canvas'),
    mapOptions);

  var input = (document.getElementById('pac-input'));
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', map);

  var infowindow = new google.maps.InfoWindow();
  var marker = new google.maps.Marker({
    map: map
  });

// CARREGA ENDERECO PELA LONGITUDE E LATITUDE 
geocoder.geocode({ 'latLng': myLatlng }, function (results, status) {
	if (status == google.maps.GeocoderStatus.OK) {
	    if (results[1]) {

	        map.setZoom(16);
	        marker.setPosition(myLatlng);                 
	        infowindow.setContent('<div><strong>' + $('input[name="titulo"]').val() + '</strong><br>' + $('input[name="endereco"]').val());
			infowindow.open(map, marker);
	    } 
	} 
});  

google.maps.event.addListener(autocomplete, 'place_changed', function() {
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }

    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(16);
    }

    marker.setIcon(({
      url: place.icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(16, 5),
      scaledSize: new google.maps.Size(35, 35)
    }));
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);    

    var address = '';
    if (place.formatted_address) {
      address = place.formatted_address;
    }
    
    if(place.geometry) {
    	$('input[name='+id+']').val(place.geometry.location.k+', '+place.geometry.location.D);
    }

    if(place.address_components){
    	if( $('input[name="titulo"]').length ){
    		$('input[name="titulo"]').val(place.name);
    	}

    	if( $('input[name="endereco"]').length ){
    		$('input[name="endereco"]').val(address);
    	}

    	if( $('input[name="cep"]').length ){
	    	$.each(place.address_components, function(i, v){
	    		var rm_traco = v.long_name;
	    		rm_traco = rm_traco.replace(/[\.-]/g, "");
				if(!isNaN(rm_traco)) {
					$('input[name="cep"]').val(v.long_name);
				}
	    	});
    	}
    }

    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    infowindow.open(map, marker);
  });

}

function atualizar_legenda(base_url,id,legenda,btn){

	$.ajax({
		type: "POST",
		dataType: "json",
		data: "id=" + id + "&legenda=" + legenda,
		url: base_url + 'opcoes/atualizar_legenda'
	}).done(function(resultado) {
		if(resultado.sucesso){
			btn.attr('disabled',false).text('Atualizado').addClass('btn-success').removeClass('btn-info');
		} else {
			btn.attr('disabled',false).text('Erro ao Atualizar').addClass('btn-danger').removeClass('btn-info');
		}
	});

}

