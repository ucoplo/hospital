var limpiarPacienteTipdoc = false;
var limpiarPacienteNacion = false;
var limpiarPacienteCodpais = false;
var limpiarPacienteCodpro = false;
var limpiarPacienteCodpar = false;
var limpiarPacienteCodloc = false;
var limpiarPacienteCodcall = false;
var limpiarPacienteTipoviv = false;
var limpiarPacienteBarrio = false;
var limpiarPacienteCodos = false;
var limpiarPacienteLocnac = false;
var limpiarPacienteProvnac = false;
var limpiarPacientePartidonac = false;
var limpiarPacienteNivinst = false;
var limpiarPacienteSitlabo = false;
var limpiarPacienteOcupac = false;

var autocompletePacienteTipdoc = $("#paciente-tipdoc");
var hiddenPacienteTipdoc =$("#paciente-pa_tipdoc");
var autocompletePacienteNacion = $("#paciente-nacion");
var hiddenPacienteNacion =$("#paciente-pa_nacion");
var autocompletePacienteCodpais = $("#paciente-codpais");
var hiddenPacienteCodpais =$("#paciente-pa_codpais");
var autocompletePacienteCodpro = $("#paciente-codpro");
var hiddenPacienteCodpro =$("#paciente-pa_codpro");
var autocompletePacienteCodpar = $("#paciente-codpar");
var hiddenPacienteCodpar =$("#paciente-pa_codpar");
var autocompletePacienteCodloc = $("#paciente-codloc");
var hiddenPacienteCodloc =$("#paciente-pa_codloc");
var autocompletePacienteCodcall = $("#paciente-codcall");
var hiddenPacienteCodcall =$("#paciente-pa_codcall");
var autocompletePacienteBarrio = $("#paciente-barrio");
var hiddenPacienteBarrio =$("#paciente-pa_barrio");
var autocompletePacienteTipoviv = $("#paciente-tipoviv");
var hiddenPacienteTipoviv =$("#paciente-pa_tipoviv");
var autocompletePacienteCodos = $("#paciente-codos");
var hiddenPacienteCodos =$("#paciente-pa_codos");
var autocompletePacienteLocnac = $("#paciente-locnac");
var autocompletePacienteProvnac = $("#paciente-provnac");
var hiddenPacienteProvnac =$("#paciente-pa_provnac");
var autocompletePacientePartidonac = $("#paciente-partidonac");
var hiddenPacientePartidonac =$("#paciente-pa_partidonac");
var hiddenPacienteLocnac =$("#paciente-pa_locnac");
var autocompletePacienteNivinst = $("#paciente-nivinst");
var hiddenPacienteNivinst =$("#paciente-pa_nivisnt");
var autocompletePacienteSitlabo = $("#paciente-sitlabo");
var hiddenPacienteSitlabo =$("#paciente-pa_sitlabo");
var autocompletePacienteOcupac = $("#paciente-ocupac");
var hiddenPacienteOcupac =$("#paciente-pa_ocupac");

function setPacienteTipoDocumento (e,datum) {
	hiddenPacienteTipdoc.val(datum.cod);
	limpiarPacienteTipdoc = true;
}

function setPacienteNacionalidad (e,datum) {
	hiddenPacienteNacion.val(datum.cod);
	limpiarPacienteNacion = true;
}

function setPacientePais (e,datum) {
	hiddenPacienteCodpais.val(datum.cod);
	limpiarPacienteCodpais = true;
}

function setPacienteProvincia (e,datum) {
	hiddenPacienteCodpro.val(datum.cod);
	limpiarPacienteCodpro = true;
}

function setPacientePartido (e,datum) {
	hiddenPacienteCodpar.val(datum.cod);
	limpiarPacienteCodpar = true;
}

function setPacienteLocalidad (e,datum) {
	hiddenPacienteCodloc.val(datum.cod);
	limpiarPacienteCodloc = true;
}

function setPacienteCalle (e,datum) {
	hiddenPacienteCodcall.val(datum.cod);
	limpiarPacienteCodcall = true;
	armarDomicilio();
}

function setPacienteBarrio (e,datum) {
	hiddenPacienteBarrio.val(datum.cod);
	limpiarPacienteBarrio = true;
	armarDomicilio();
}

function setPacienteTipoVivienda (e,datum) {
	hiddenPacienteTipoviv.val(datum.cod);
	limpiarPacienteTipoviv = true;
}

function setPacienteObraSocial (e,datum) {
	hiddenPacienteCodos.val(datum.cod);
	limpiarPacienteCodos = true;
}

function setPacienteLocalidadNacimiento (e,datum) {
	hiddenPacienteLocnac.val(datum.cod);
	limpiarPacienteLocnac = true;
}

function setPacienteProvinciaNacimiento (e,datum) {
	hiddenPacienteProvnac.val(datum.cod);
	limpiarPacienteProvnac = true;
}

function setPacientePartidoNacimiento (e,datum) {
	hiddenPacientePartidonac.val(datum.cod);
	limpiarPacientePartidonac = true;
}

function setPacienteNivelInstruccion (e,datum) {
	hiddenPacienteNivinst.val(datum.cod);
	limpiarPacienteNivinst = true;
}

function setPacienteSituacionLaboral (e,datum) {
	hiddenPacienteSitlabo.val(datum.cod);
	limpiarPacienteSitlabo = true;
}

function setPacienteOcupacion (e,datum) {
	hiddenPacienteOcupac.val(datum.cod);
	limpiarPacienteOcupac = true;
}

/*
	Armar el domicilio con los datos que se van completando
*/
function armarDomicilio() {
	var calleAux = $("#paciente-codcall").val();
	var calle = calleAux.substring(calleAux.indexOf(']') + 1);
	var nro = $("#paciente-pa_nrocall").val();
	var cuerpo = $("#paciente-pa_cuerpo").val();
	var piso = $("#paciente-pa_piso").val();
	var depto = $("#paciente-pa_dpto").val();
	var barrio = $("#paciente-barrio").val();
	var domic = calle + ' ' + nro + (cuerpo ? ', Cuerpo ' + cuerpo : '') +
				(piso ? ', piso ' + piso : '') + (depto ? ', depto. ' + depto : '') +
				(barrio ? ', Bº ' + barrio : '');

	$("#paciente-pa_direc").val(domic);
}

/*
	Armar el campo nombre y apellido con los datos que se van completando
*/
function armarNombreApellido() {
	var nombres = $("#paciente-pa_nombre").val();
	var apellidos = $("#paciente-pa_apellido").val();
	var nya = apellidos + ' ' + nombres;

	$("#paciente-pa_apenom").val(nya);
}

$(document).ready(function(){

	/*
	 Si el usuario presiona la tecla backspace o delete, directamente se borra el contenido del
	 campo de autocompletar y el campo oculto donde se guarda el código. Esto es para evitar que
	 se quede mostrando algo, cuando en realidad en el campo oculto hay otra cosa guardada.
	*/
	autocompletePacienteTipdoc.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteTipdoc && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteTipdoc.typeahead("val", "");
			hiddenPacienteTipdoc.val("");
			limpiarPacienteTipdoc = false;
		}
	});

	autocompletePacienteNacion.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteNacion && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteNacion.typeahead("val", "");
			hiddenPacienteNacion.val("");
			limpiarPacienteNacion = false;
		}
	});

	autocompletePacienteCodpais.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteCodpais && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteCodpais.typeahead("val", "");
			hiddenPacienteCodpais.val("");
			limpiarPacienteCodpais = false;
		}
	});

	autocompletePacienteCodpro.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteCodpro && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteCodpro.typeahead("val", "");
			hiddenPacienteCodpro.val("");
			limpiarPacienteCodpro = false;
		}
	});

	autocompletePacienteCodpar.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteCodpar && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteCodpar.typeahead("val", "");
			hiddenPacienteCodpar.val("");
			limpiarPacienteCodpar = false;
		}
	});

	autocompletePacienteCodloc.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteCodloc && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteCodloc.typeahead("val", "");
			hiddenPacienteCodloc.val("");
			limpiarPacienteCodloc = false;
		}
	});

	autocompletePacienteCodcall.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteCodcall && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteCodcall.typeahead("val", "");
			hiddenPacienteCodcall.val("");
			limpiarPacienteCodcall = false;
			armarDomicilio();
		}
	});

	autocompletePacienteBarrio.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteBarrio && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteBarrio.typeahead("val", "");
			hiddenPacienteBarrio.val("");
			limpiarPacienteBarrio = false;
			armarDomicilio();
		}
	});

	autocompletePacienteTipoviv.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteTipoviv && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteTipoviv.typeahead("val", "");
			hiddenPacienteTipoviv.val("");
			limpiarPacienteTipoviv = false;
		}
	});

	autocompletePacienteCodos.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteCodos && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteCodos.typeahead("val", "");
			hiddenPacienteCodos.val("");
			limpiarPacienteCodos = false;
		}
	});

	autocompletePacienteLocnac.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteLocnac && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteLocnac.typeahead("val", "");
			hiddenPacienteLocnac.val("");
			limpiarPacienteLocnac = false;
		}
	});

	autocompletePacienteProvnac.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteProvnac && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteProvnac.typeahead("val", "");
			hiddenPacienteProvnac.val("");
			limpiarPacienteProvnac = false;
		}
	});

	autocompletePacientePartidonac.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacientePartidonac && (keycode == 8) || (keycode == 46)) {
			autocompletePacientePartidonac.typeahead("val", "");
			hiddenPacientePartidonac.val("");
			limpiarPacientePartidonac = false;
		}
	});

	autocompletePacienteNivinst.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteNivinst && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteNivinst.typeahead("val", "");
			hiddenPacienteNivinst.val("");
			limpiarPacienteNivinst = false;
		}
	});

	autocompletePacienteSitlabo.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteSitlabo && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteSitlabo.typeahead("val", "");
			hiddenPacienteSitlabo.val("");
			limpiarPacienteSitlabo = false;
		}
	});

	autocompletePacienteOcupac.keydown(function(e){
		var keycode =  e.keyCode ? e.keyCode : e.which;
		if (limpiarPacienteOcupac && (keycode == 8) || (keycode == 46)) {
			autocompletePacienteOcupac.typeahead("val", "");
			hiddenPacienteOcupac.val("");
			limpiarPacienteOcupac = false;
		}
	});



});

/*
	Presentar sugerencias ante la repetición de ciertos datos
*/
function buscarPorTipoNumeroDocumento(){
	var containerSugerencias = $('#sugerencias-tipo-nro-doc');
	var tipoDoc = $('#paciente-pa_tipdoc :selected').text();
	var numDoc = $('#paciente-pa_numdoc');
 	if ((tipoDoc !== '') && (numDoc.val() !== '')) {
	    $.ajax({
	        url:   'index.php?r=paciente/buscar-sugerencias&PacienteBuscar[PA_TIPDOC]=' + tipoDoc + '&PacienteBuscar[PA_NUMDOC]=' + numDoc.val(),
	        type:  'GET',
	        beforeSend: function () {
	                	containerSugerencias.html("<strong>Buscando coincidencias...</strong>");
	        },
	        success:  function (response) {
	                    containerSugerencias.html(response);
	        },
	        error: function (response) { 
	                containerSugerencias.html('');
	        }
	    });
	}
};

function buscarPorDomicilioFechaNacimiento(){
	var containerSugerencias = $('#sugerencias-domicilio-fnac');
	var nroCalle = $('#paciente-pa_nrocall');
	var fechaNac = $('#paciente-pa_fecnac');
 	if ((hiddenPacienteCodcall.val() !== '') && (nroCalle.val() !== '') && (fechaNac.val() !== '')) {
	    $.ajax({
	        url:   'index.php?r=paciente/buscar-sugerencias&PacienteBuscar[PA_CODCALL]=' + hiddenPacienteCodcall.val() + '&PacienteBuscar[PA_NROCALL]=' + nroCalle.val() + '&PacienteBuscar[PA_FECNAC]=' + fechaNac.val(),
	        type:  'GET',
	        beforeSend: function () {
	                	containerSugerencias.html("<strong>Buscando coincidencias...</strong>");
	        },
	        success:  function (response) {
	                    containerSugerencias.html(response);
	        },
	        error: function (response) {
	                containerSugerencias.html('');
	        }
	    });
	}
};