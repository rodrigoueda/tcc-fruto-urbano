(FU.search = function() {
    'use strict';

    var
        apiKey = "Al-vZDA_YaFdgkTvi2V_X8OIdDW8cD9CZ34nO93XS-cqWDY6p5RoJHkTV0P_nkRm",

        searchPoint = function(query) {
            var url = 'http://dev.virtualearth.net/REST/v1/Locations';


            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'jsonp',
                jsonp: "jsonp",
                data: {
                    query: query,
                    key: apiKey
                }
            })
            .done(function(data) {
                var resources = data.resourceSets[0].resources,
                    point = resources[0].point.coordinates;

                point = [point[1], point[0]];

                FU.map.map.getView().setCenter(ol.proj.transform(point, "EPSG:4326", "EPSG:3857"));
            })
            .fail(function() {
                swal('erro', 'Não foi possível localizar o endereço.', 'error');
            });            
        },

        searchCoordinates = function(coordinates, callback) {
            var url = 'http://dev.virtualearth.net/REST/v1/Locations/';

            url += coordinates.join();

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'jsonp',
                jsonp: "jsonp",
                data: {
                    key: apiKey
                }
            })
            .done(function(data) {
                var resources = data.resourceSets[0].resources,
                    data = {
                        address: resources[0].address.formattedAddress,
                        state: resources[0].address.adminDistrict,
                        country: resources[0].address.countryRegion,
                        city: resources[0].address.locality
                    }

                callback(data);

            })
            .fail(function(data) {
                console.log(data);
            });    
        },

        init = (function() {
            $('#search-button').on('click', function() {
                searchPoint($('#search-content').val());
            });
        }());

        return {
            searchPoint: searchPoint,
            searchCoordinates: searchCoordinates
        }
}());
FU.searchPoint = FU.search.searchPoint;
FU.searchCoordinates = FU.search.searchCoordinates;