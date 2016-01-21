(FU.events = function() {
    'use strict';

    var
        sidebarToggle = function(flag, type) {
            if (flag == true) {
                if ($('#sidebar').hasClass('sidebar-hidden')) {
                    $('#sidebar').removeClass('sidebar-hidden');
                }
                $('#form-insert, #form-show').addClass('hidden');
                switch(type) {
                    case 'insert':
                        $('#form-insert').removeClass('hidden');
                        break;
                    case 'show':
                        $('#form-show').removeClass('hidden');
                        break;
                }
            } else {
                if (!$('#sidebar').hasClass('sidebar-hidden')) {
                    $('#sidebar').addClass('sidebar-hidden');
                }
            }
        },

        createPoint = function() {
            var formData = new FormData($('#form-insert')[0]);

            console.log(formData);

            $.ajax({
                url: '/point',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            })
            .done(function() {
                FU.map.load();
                sidebarToggle(false);
            })
            .fail(function(data) {
                console.log(data);
                if (data.status == 401) {
                    window.location = '/auth/login';
                }
            });
            
        },

        contextMenu = function() {
            $('#context-menu').on('click', function() {
                $('#sidebar form')[0].reset();

                $('#latitude').val($(this).attr('data-latitude'));
                $('#longitude').val($(this).attr('data-longitude'));
                $(this).addClass('hidden');

                sidebarToggle(true, 'insert');
            });
        },

        validateInsert = function() {
            var form = $('#form-insert');

            if (form.find('[name=type]:checked').val() == undefined) {
                return {
                    error: true,
                    msg: 'Você precisa selecionar o tipo.'
                }
            }
            return {
                error: false
            }
        },

        sideBar = function() {
            $('.btn-fechar').on('click', function(e) {
                e.preventDefault();

                sidebarToggle(false);
            });

            $('#form-insert #btn-salvar').on('click', function(e) {
                e.preventDefault();
                var validate = validateInsert();

                if (validate.error === true) {
                    swal('Erro', validate.msg, 'error');
                } else {
                    FU.searchCoordinates([
                        $('#form-insert [name=longitude]').val(),
                        $('#form-insert [name=latitude]').val()
                    ], function(data) {
                        $('#form-insert [name=address]').val(data.address);
                        $('#form-insert [name=city]').val(data.city);
                        $('#form-insert [name=state]').val(data.state);
                        $('#form-insert [name=country]').val(data.country);

                        createPoint();
                    });
                }
            });

            $('#form-insert [name=type]').on('change', function() {
                if ($(this).val() == 'VEGETACAO') {
                    $('#form-insert [name=species]').prop('disabled', '');
                } else {
                    $('#form-insert [name=species]').prop('disabled', 'disabled');
                }
            });
        },

        map = function() {
            FU.map.map.getViewport().addEventListener('contextmenu', function (e) {
                e.preventDefault();

                var position = [e.layerX, e.layerY],
                    coordinates = FU.map.map.getCoordinateFromPixel(position);
                
                coordinates = ol.proj.transform(coordinates, "EPSG:3857", "EPSG:4326");

                $('#context-menu')
                    .css('top', e.layerY)
                    .css('left', e.layerX)
                    .removeClass('hidden')
                    .attr('data-latitude', coordinates[0])
                    .attr('data-longitude', coordinates[1]);
            });

            var removeContextMenu = function() {
                if (!$('#context-menu').hasClass('hidden')) {
                    $('#context-menu').addClass('hidden');
                }
                sidebarToggle(false);
            };

            FU.map.map.on('click', removeContextMenu);
            FU.map.map.on('pointerdrag', removeContextMenu);
        },

        deletePoint = function() {
            $('#show-deletar').on('click', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '/point/'+$(this).data('id'),
                    type: 'DELETE'
                })
                .done(function() {
                    sidebarToggle(false);
                    FU.map.load();
                })
                .fail(function() {
                    swal('Erro', 'Você não pode remover este ponto.', 'error');
                });
                
            });
        },

        init = function() { 
            map();
            contextMenu();
            sideBar();
            deletePoint();
        }
        init();

        return {
            sidebarToggle: sidebarToggle
        }
}());
