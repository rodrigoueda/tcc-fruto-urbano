(FU.map = function() {
    'use strict';

    var

        view = new ol.View({
            center: ol.proj.transform([-49.058484, -22.3317505], "EPSG:4326", "EPSG:3857"),
            zoom: 13
        }),

        aerialLayer = new ol.layer.Tile ({
            source: new ol.source.BingMaps({
                key: 'Al-vZDA_YaFdgkTvi2V_X8OIdDW8cD9CZ34nO93XS-cqWDY6p5RoJHkTV0P_nkRm',
                imagerySet: 'AerialWithLabels',
                maxZoom: 19
            }),
            preload: 20,
            opacity: 1
        }),

        setColor = function(type) {
            switch(type) {
                case 'PODA_DRASTICA': return 'red';
                case 'VEGETACAO':     return 'green';
                case 'LOCAL_PLANTIO': return 'blue';
            }
        },

        featureStyle = function(feature, resolution) {
            var color = setColor(feature.get('type')),

                size = Math.max(5, 7/resolution);

            return [new ol.style.Style({
                image: new ol.style.Circle({
                    radius: size,
                    fill:  new ol.style.Fill({
                        color: color
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'white',
                        width: size*0.25
                    }),
                })
            })]
        },

        featureSource = new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            projection: 'EPSG:3857',
            strategy: ol.loadingstrategy.bbox
        }),

        featureLayer = new ol.layer.Vector({
            source: featureSource,
            name: 'Pontos',
            style: featureStyle
        }),

        osmLayer = new ol.layer.Tile ({
            source: new ol.source.OSM(),
            preload: Infinity,
            opacity: 1
        }),

        map = new ol.Map({
            target: 'map',
            layers: [
                aerialLayer
            ],
            view: view
        }),

        geolocation = new ol.Geolocation({
            projection: view.getProjection(),
            tracking: true
        }),

        load = function() {
            var extent = map.getView().calculateExtent(map.getSize());

            extent = ol.proj.transformExtent(extent, "EPSG:3857", "EPSG:4326");

            $.ajax({
                url: '/point/',
                type: 'GET',
                dataType: 'json',
                data: {
                    minX: extent[0], 
                    minY: extent[1], 
                    maxX: extent[2], 
                    maxY: extent[3], 
                },
            })
            .done(function(data) {
                if (data.length > 0) {
                    featureSource.clear();
                    $(data).each(function(index, element) {
                        element.geometry.coordinates = ol.proj.transform(element.geometry.coordinates, "EPSG:4326", "EPSG:3857");
                        featureSource.addFeature((new ol.format.GeoJSON()).readFeature(element));
                    });
                }
            });
            
        },

        app = {};

        app.Drag = function() {

            ol.interaction.Pointer.call(this, {
                handleDownEvent: app.Drag.prototype.handleDownEvent,
                handleDragEvent: app.Drag.prototype.handleDragEvent,
                handleMoveEvent: app.Drag.prototype.handleMoveEvent,
                handleUpEvent: app.Drag.prototype.handleUpEvent
            });

            this.coordinate_ = null;
            this.cursor_ = 'pointer';
            this.feature_ = null;
            this.previousCursor_ = undefined;

        };
        ol.inherits(app.Drag, ol.interaction.Pointer);

        app.Drag.prototype.handleDownEvent = function(evt) {
            var map = evt.map;

            var feature = map.forEachFeatureAtPixel(evt.pixel,
            function(feature) {
                if (feature.get('isOwner') == false) {
                    return false;
                }
                return feature;
            });

            if (feature) {
                this.coordinate_ = evt.coordinate;
                this.feature_ = feature;
            }

            return !!feature;
        };

        app.Drag.prototype.handleDragEvent = function(evt) {
            var deltaX = evt.coordinate[0] - this.coordinate_[0];
            var deltaY = evt.coordinate[1] - this.coordinate_[1];

            var geometry = this.feature_.getGeometry();
            geometry.translate(deltaX, deltaY);

            this.coordinate_[0] = evt.coordinate[0];
            this.coordinate_[1] = evt.coordinate[1];
        };

        app.Drag.prototype.handleMoveEvent = function(evt) {
            if (this.cursor_) {
                var map = evt.map;
                var feature = map.forEachFeatureAtPixel(evt.pixel,
                function(feature) {
                        return feature;
                });
                var element = evt.map.getTargetElement();
                if (feature) {
                    if (element.style.cursor != this.cursor_) {
                        this.previousCursor_ = element.style.cursor;
                        element.style.cursor = this.cursor_;
                    }
                } else if (this.previousCursor_ !== undefined) {
                    element.style.cursor = this.previousCursor_;
                    this.previousCursor_ = undefined;
                }
            }
        };

        app.Drag.prototype.handleUpEvent = function() {     
            this.coordinate_ = null;
            this.feature_ = null;

            return false;
        };

        var init = function() {
            map.addLayer(featureLayer);
            map.addInteraction(new app.Drag());

            geolocation.once('change', function(evt) {
                view.setCenter(geolocation.getPosition());
                view.setZoom(17);
            });

            map.on('moveend', load);

            map.on("pointermove", function (evt) {
                var hit = this.forEachFeatureAtPixel(evt.pixel,
                    function(feature, layer) {
                        return true;
                });

                if (hit) {
                    $("#map").css('cursor', 'pointer');
                } else {
                    $("#map").css('cursor', '');
                }
            });

            map.on("click", function (evt) {
                var feature = this.forEachFeatureAtPixel(evt.pixel,
                    function(feature, layer) {
                        return feature;
                });

                if (feature === undefined) {
                    return;
                }

                $.ajax({
                    url: '/point/'+feature.get('id'),
                    type: 'GET',
                    dataType: 'json'
                })
                .done(function(data) {
                    FU.events.sidebarToggle(true, 'show');
                    $('#show-tipo').html(data.type);
                    $('#show-endereco').html(data.address);
                    if (data.species && data.species.length > 0) {
                        if ($('#show-especie').hasClass('hidden')) {
                            $('#show-especie').removeClass('hidden')
                        }
                        $('#show-especie b').html(data.species);
                    } else {
                        if (!$('#show-especie').hasClass('hidden')) {
                            $('#show-especie').addClass('hidden')
                        }
                    }
                    if (data.image && data.image.length > 0) {
                        if ($('#show-imagem').hasClass('hidden')) {
                            $('#show-imagem').removeClass('hidden')
                        }
                        $('#show-imagem').attr('src', '/img/uploads/'+data.image);
                    } else {
                        if (!$('#show-imagem').hasClass('hidden')) {
                            $('#show-imagem').addClass('hidden')
                        }
                    }
                    if (data.comments && data.comments.length > 0) {
                        if ($('#show-observacoes').hasClass('hidden')) {
                            $('#show-observacoes').removeClass('hidden')
                        }
                        $('#show-observacoes span').html(data.comments);
                    } else {
                        if (!$('#show-observacoes').hasClass('hidden')) {
                            $('#show-observacoes').addClass('hidden')
                        }
                    }
                    if (data.isOwner && data.isOwner == true) {
                        if ($('#show-deletar').hasClass('hidden')) {
                            $('#show-deletar').removeClass('hidden')
                        }
                        $('#show-deletar').attr('data-id', data.id);
                    } else {
                        if (!$('#show-deletar').hasClass('hidden')) {
                            $('#show-deletar').addClass('hidden')
                        }
                    }
                });
                
            });
        };
        init();

    return {
        map: map,
        load: load
    }
}());