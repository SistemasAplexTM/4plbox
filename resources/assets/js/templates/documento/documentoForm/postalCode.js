   var inputZip = '';
    $(document).ready(function() {
        //*********************   funcion para traer el codigo zip Consignee *********************
        $('#buttonPostalCode').on('click', function() {
            var validar = true;
            inputZip = 'zipD';
            // Obtenemos la dirección, la ciudad, el estado o departamento y el pais y la asignamos a una variable a cada uno
            var direccion = $('#direccionD').val();
            var ciudad = $('#localizacion_id_c').text();
            if (ciudad === '') {
                validar = false;
            }
            //        var estado = $('#estadoD').val();
            var estado = $('#deptoD').val();
            if (estado === '') {
                validar = false;
            }
            var pais = $('#paisD').val();
            if (estado === '') {
                validar = false;
            }
            /*se arma la direccion completa con los datos obtenidos de las variables para pasarselas a Google*/
            var address = direccion + ' ' + ciudad + ' ' + estado + ' ' + pais;
            console.log(address);
            if (validar === false) {
                swal("Atencion!", "Porfavor de click en el boton 'Buscar' del campo de ciudad del Shipper o Consignee y seleccione una ciudad de la lista.", "warning");
            } else {
                // Creamos el Objeto Geocoder
                var geocoder = new google.maps.Geocoder();
                // Hacemos la petición indicando la dirección e invocamos la función
                // geocodeDesult enviando todo el resultado obtenido
                geocoder.geocode({
                    'address': address
                }, geocodeDesult);
            }
        });
    });

    function setDataGeocode(address, inputZipData){
        inputZip = inputZipData;
        /* address: arma la direccion completa con los datos obtenidos de las variables para pasarselas a Google
           address = direccion + ciudad + estado + pais*/
        // Creamos el Objeto Geocoder
        var geocoder = new google.maps.Geocoder();
        // Hacemos la petición indicando la dirección e invocamos la función
        // geocodeDesult enviando todo el resultado obtenido
        geocoder.geocode({
            'address': address
        }, geocodeDesult);
    }

    function geocodeDesult(results, status) {
        // Verificamos el estatus
        if (status == 'OK') {
            // Si hay resultados encontrados, centramos y repintamos el mapa
            // esto para eliminar cualquier pin antes puesto
            //                var mapOptions = {
            //                    center: results[0].geometry.location,
            //                    mapTypeId: google.maps.MapTypeId.DOADMAP
            //                };
            //                map = new google.maps.Map($("#map1").get(0), mapOptions);
            // fitBounds acercará el mapa con el zoom adecuado de acuerdo a lo buscado
            //                map.fitBounds(results[0].geometry.viewport);
            // Dibujamos un marcador con la ubicación del primer resultado obtenido
            //                var markerOptions = {position: results[0].geometry.location}
            //                var marker = new google.maps.Marker(markerOptions);
            //                marker.setMap(map);
            //                $('#latitude').text(results[0].geometry.location.lat());
            //                $('#longitude').text(results[0].geometry.location.lng());
            var buscar = "";
            var valor = "postal_code";
            var objeto = results[0].address_components;
            //                evaluamos el objeto json que nos da google donde se encuentran los componentes de la direccion 
            //                que se define con la posicion=(results[0].address_components) del archivo json
            for (var arreglo in objeto) {
                //alert(" arreglo2 = " + arreglo);
                for (var elemento in objeto[arreglo]) {
                    //alert(" elemento = " + objeto[arreglo][elemento]);
                    for (var tipo in objeto[arreglo][elemento]) {
                        //alert(" elemento = " + objeto[arreglo][elemento][tipo]);
                        if (valor === objeto[arreglo][elemento][tipo]) {
                            //                            si encuentra que la variable (valor) es igual al (tipo['postal_code']) que tiene el objeto en la posicion que 
                            //                            se esta evaluando entonces le asigana el valor del arreglo en la que se encuentra para luego buscar
                            //                            el zip_code en esa posicion, devido a que ese dato varia de posicion deacuerdo a los datos que contenga el pais
                            buscar = arreglo;
                        }
                    }
                }
            }
            console.log(results[0]);
            //              con la posicion (buscar) ya obtenida, se la asignamos a (address_components[buscar]) para que nos traiga el codigo postal
            $('#'+inputZip).val(results[0].address_components[buscar].long_name);
        } else {
            // En caso de no haber resultados o que haya ocurrido un error
            // lanzamos un mensaje con el error
            alert("Geocoding no tuvo éxito debido a: " + status);
        }
    }