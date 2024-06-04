export default function filamentGoogleMapsField({
  state,
  setStateUsing,
  getStateUsing,
  autocomplete,
  autocompleteReverse,
  geolocate = false,
  geolocateOnLoad,
  geolocateLabel,
  draggable,
  clickable,
  defaultLocation,
  statePath,
  controls,
  layers,
  reverseGeocodeFields,
  defaultZoom,
  types,
  countries,
  placeField,
  debug,
  gmaps,
  mapEl,
  pacEl,
  drawingControl,
  drawingControlPosition,
  polyOptions = {
    fillColor: "#f06eaa",
    strokeColor: "#00ff00",
    strokeOpacity: "0.5",
    strokeWeight: 3,
    fillOpacity: 0.45,
    draggable: true,
    editable: false,
    clickable: true,
  },
  circleOptions = {
    fillColor: "#f06eaa",
    strokeColor: "#00ff00",
    strokeOpacity: "0.5",
    strokeWeight: 3,
    fillOpacity: 0.45,
    draggable: true,
    editable: false,
    clickable: true,
  },
  rectangleOptions = {
    fillColor: "#f06eaa",
    strokeColor: "#00ff00",
    strokeOpacity: "0.5",
    strokeWeight: 3,
    fillOpacity: 0.45,
    draggable: true,
    editable: false,
    clickable: true,
  },
  drawingModes = {
    marker: true,
    circle: true,
    rectangle: true,
    polygon: true,
    polyline: true,
  },
  drawingField,
  geoJson,
  geoJsonField,
  geoJsonProperty,
  geoJsonVisible,
  reverseGeocodeUsing,
  placeUpdatedUsing,
  hasReverseGeocodeUsing = false,
  hasPlaceUpdatedUsing = false,
}) {
  return {
    state,
    map: null,
    geocoder: null,
    marker: null,
    markerLocation: null,
    layers: null,
    symbols: {
      "%n": ["street_number"],
      "%z": ["postal_code"],
      "%S": ["street_address", "route"],
      "%A1": ["administrative_area_level_1"],
      "%A2": ["administrative_area_level_2"],
      "%A3": ["administrative_area_level_3"],
      "%A4": ["administrative_area_level_4"],
      "%A5": ["administrative_area_level_5"],
      "%a1": ["administrative_area_level_1"],
      "%a2": ["administrative_area_level_2"],
      "%a3": ["administrative_area_level_3"],
      "%a4": ["administrative_area_level_4"],
      "%a5": ["administrative_area_level_5"],
      "%L": ["locality", "postal_town"],
      "%D": ["sublocality"],
      "%C": ["country"],
      "%c": ["country"],
      "%p": ["premise"],
      "%P": ["premise"],
    },
    drawingManager: null,
    overlays: [],
    dataLayer: null,
    geoJsonDataLayer: null,
    polyOptions: polyOptions,
    circleOptions: circleOptions,
    rectangleOptions: rectangleOptions,
    selectedShape: null,
    placesService: null,
    placeFields: [],

    loadGMaps: function () {
      if (!document.getElementById("filament-google-maps-google-maps-js")) {
        const script = document.createElement("script");
        script.id = "filament-google-maps-google-maps-js";
        window.filamentGoogleMapsAsyncLoad = this.createMap.bind(this);
        script.src = gmaps + "&callback=filamentGoogleMapsAsyncLoad";
        document.head.appendChild(script);
      } else {
        const waitForGlobal = function (key, callback) {
          if (window[key]) {
            callback();
          } else {
            setTimeout(function () {
              waitForGlobal(key, callback);
            }, 100);
          }
        };

        waitForGlobal(
          "filamentGoogleMapsAPILoaded",
          function () {
            this.createMap();
          }.bind(this)
        );
      }
    },

    init: function () {
      this.loadGMaps();
    },

    createMap: function () {
      window.filamentGoogleMapsAPILoaded = true;

      if (autocompleteReverse || Object.keys(reverseGeocodeFields).length > 0) {
        this.geocoder = new google.maps.Geocoder();
      }

      this.map = new google.maps.Map(mapEl, {
        center: this.getCoordinates(),
        zoom: defaultZoom,
        ...controls,
      });

      this.marker = new google.maps.Marker({
        draggable: draggable,
        map: this.map,
      });

      this.marker.setPosition(this.getCoordinates());

      if (clickable) {
        this.map.addListener("click", (event) => {
          this.markerMoved(event);
        });
      }

      if (draggable) {
        google.maps.event.addListener(this.marker, "dragend", (event) => {
          this.markerMoved(event);
        });
      }

      if (controls.searchBoxControl) {
        const input = pacEl;
        const searchBox = new google.maps.places.SearchBox(input);
        this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        searchBox.addListener("places_changed", () => {
          input.value = "";
          this.markerLocation = searchBox.getPlaces()[0].geometry.location;
        });
      }

      if (hasPlaceUpdatedUsing) {
        this.placesService = new google.maps.places.PlacesService(this.map);
      }

      this.placeFields = [
        "address_components",
        "formatted_address",
        "geometry",
        "name",
      ];

      if (!this.placeFields.includes(placeField)) {
        this.placeFields.push(placeField);
      }

      if (hasPlaceUpdatedUsing) {
        this.placeFields.push("photos");
      }

      if (autocomplete) {
        const geoComplete = document.getElementById(autocomplete);

        if (geoComplete) {
          window.addEventListener(
            "keydown",
            function (e) {
              if (
                e.key === "U+000A" ||
                e.key === "Enter" ||
                e.code === "Enter"
              ) {
                if (e.target.nodeName === "INPUT" && e.target.type === "text") {
                  e.preventDefault();
                  return false;
                }
              }
            },
            true
          );

          const geocompleteOptions = {
            fields: this.placeFields,
            strictBounds: false,
            types: types,
          };

          const gAutocomplete = new google.maps.places.Autocomplete(
            geoComplete,
            geocompleteOptions
          );

          gAutocomplete.setComponentRestrictions({
            country: countries,
          });

          gAutocomplete.addListener("place_changed", () => {
            const place = gAutocomplete.getPlace();

            if (!place.geometry || !place.geometry.location) {
              window.alert(
                "No details available for input: '" + place.name + "'"
              );
              return;
            }

            if (place.geometry.viewport) {
              this.map.fitBounds(place.geometry.viewport);
            } else {
              this.map.setCenter(place.geometry.location);
            }

            setStateUsing(autocomplete, place[placeField]);
            this.marker.setPosition(place.geometry.location);
            this.markerLocation = place.geometry.location;
            this.setCoordinates(place.geometry.location);
            this.updateGeocodeFromAddressComponents(place.address_components);
            if (hasPlaceUpdatedUsing) {
              placeUpdatedUsing(place);
            }
          });
        }
      }

      if (layers) {
        this.layers = layers.map((layerUrl) => {
          const kmlLayer = new google.maps.KmlLayer({
            url: layerUrl,
            map: this.map,
          });

          kmlLayer.addListener("click", (kmlEvent) => {
            const text = kmlEvent.featureData.description;
          });
        });
      }

      if (geoJson) {
        if (geoJsonVisible) {
          this.geoJsonDataLayer = this.map.data;
        } else {
          this.geoJsonDataLayer = new google.maps.Data();
        }

        if (/^http/.test(geoJson)) {
          this.geoJsonDataLayer.loadGeoJson(geoJson);
        } else {
          this.geoJsonDataLayer.addGeoJson(JSON.parse(geoJson));
        }
      }

      if (geolocateOnLoad) {
        this.getLocation();
      }

      if (geolocate && "geolocation" in navigator) {
        const locationButton = document.createElement("button");

        locationButton.textContent = geolocateLabel;
        locationButton.classList.add("custom-map-control-button");
        this.map.controls[google.maps.ControlPosition.TOP_CENTER].push(
          locationButton
        );

        locationButton.addEventListener("click", (e) => {
          e.preventDefault();
          this.getLocation();
        });
      }

      if (drawingControl) {
        this.map.data.setStyle({
          clickable: false,
          cursor: null,
          draggable: false,
          editable: false,
          fillOpacity: 0.0,
          visible: false,
          // zIndex: 0
        });

        this.drawingManager = new google.maps.drawing.DrawingManager({
          drawingMode: null,
          drawingControl: true,
          drawingControlOptions: {
            position: drawingControlPosition,
            drawingModes: [
              ...(drawingModes.marker
                ? [google.maps.drawing.OverlayType.MARKER]
                : []),
              ...(drawingModes.circle
                ? [google.maps.drawing.OverlayType.CIRCLE]
                : []),
              ...(drawingModes.polygon
                ? [google.maps.drawing.OverlayType.POLYGON]
                : []),
              ...(drawingModes.polyline
                ? [google.maps.drawing.OverlayType.POLYLINE]
                : []),
              ...(drawingModes.rectangle
                ? [google.maps.drawing.OverlayType.RECTANGLE]
                : []),
            ],
          },
          markerOptions: {
            draggable: true,
          },
          polylineOptions: {
            draggable: true,
            editable: false,
          },
          rectangleOptions: this.rectangleOptions,
          circleOptions: this.circleOptions,
          polygonOptions: this.polyOptions,
        });

        this.drawingManager.setMap(this.map);
        google.maps.event.addListener(
          this.drawingManager,
          "drawingmode_changed",
          () => {
            this.clearSelection();
          }
        );
        // google.maps.event.addListener(this.map, 'click', () => {
        //     this.clearSelection()
        // });

        if (drawingField) {
          this.dataLayer = new google.maps.Data();

          let geoJSON = getStateUsing(drawingField);
          geoJSON && this.loadFeaturesCollection(JSON.parse(geoJSON));

          google.maps.event.addListener(
            this.drawingManager,
            "overlaycomplete",
            (event) => {
              event.overlay.type = event.type;
              event.overlay.id = this.guid();
              event.overlay.feature = this.instanceFeature(event.overlay);
              this.addOverlayEvents(event.overlay);
              this.overlays.push(event.overlay);

              if (event.type != google.maps.drawing.OverlayType.MARKER) {
                // Switch back to non-drawing mode after drawing a shape.
                this.drawingManager.setDrawingMode(null);
                this.setSelection(event.overlay);
              }

              this.drawingModified();
            }
          );
        }
      }

      this.$watch("state", () => {
        if (this.state === undefined) {
          return;
        }

        const location = this.getCoordinates();
        const markerLocation = this.marker.getPosition();

        if (
          !(
            location.lat === markerLocation.lat() &&
            location.lng === markerLocation.lng()
          )
        ) {
          this.updateAutocompleteFromLocation(location);
          this.updateMap(location);
        }
      });
    },
    markerMoved: function (event) {
      this.geoJsonContains(event.latLng);
      this.markerLocation = event.latLng.toJSON();
      this.setCoordinates(this.markerLocation);
      this.updateFromLocation(this.markerLocation);
      this.map.panTo(this.markerLocation);

      if (hasPlaceUpdatedUsing && event.placeId) {
        this.placesService.getDetails(
          {
            placeId: event.placeId,
            fields: this.placeFields,
          },
          (results, status) => {
            status === "OK" && placeUpdatedUsing(results);
          }
        );
      }
    },
    updateMap: function (position) {
      this.marker.setPosition(position);
      this.map.panTo(position);
    },
    updateFromLocation: function (location) {
      if (this.hasReverseGeocode() || this.hasReverseAutocomplete()) {
        this.geocoder
          .geocode({ location })
          .then((response) => {
            this.updateGeocodeFromAddressComponents(
              response.results[0].address_components
            );
            this.updateAutocompleteFromFormattedAddress(
              response.results[0].formatted_address
            );
            if (hasReverseGeocodeUsing) {
              reverseGeocodeUsing(response);
            }
          })
          .catch((error) => {
            console.log(error.message);
          });
      }
    },
    updateGeocodeFromAddressComponents: function (address_components) {
      if (this.hasReverseGeocode()) {
        const replacements = this.getReplacements(address_components);

        for (const field in reverseGeocodeFields) {
          let replaced = reverseGeocodeFields[field];
          for (const replacement in replacements) {
            replaced = replaced
              .split(replacement)
              .join(replacements[replacement]);
          }

          for (const symbol in this.symbols) {
            replaced = replaced.split(symbol).join("");
          }

          replaced = replaced.trim();
          setStateUsing(field, replaced);
        }
      }
    },
    updateGeocodeFromLocation: function (location) {
      if (this.hasReverseGeocode()) {
        this.geocoder
          .geocode({ location })
          .then((response) => response.results[0].address_components)
          .then((address_components) =>
            this.updateGeocodeFromAddressComponents(address_components)
          )
          .catch((error) => {
            console.log(error.message);
          });
      }
    },
    updateAutocompleteFromFormattedAddress: function (address) {
      if (this.hasReverseAutocomplete()) {
        setStateUsing(autocomplete, address);
      }
    },
    updateAutocompleteFromLocation: function (location) {
      if (this.hasReverseAutocomplete()) {
        this.geocoder
          .geocode({ location: location })
          .then((response) => {
            if (response.results[0]) {
              setStateUsing(
                autocomplete,
                response.results[0].formatted_address
              );
            }
          })
          .catch((error) => {
            console.log(error.message);
          });
      }
    },
    hasReverseAutocomplete: function () {
      return autocomplete && autocompleteReverse;
    },
    hasReverseGeocode: function () {
      return (
        Object.keys(reverseGeocodeFields).length > 0 || hasReverseGeocodeUsing
      );
    },
    setCoordinates: function (position) {
      this.state = position;
    },
    getCoordinates: function () {
      if (this.state === null || !this.state.hasOwnProperty("lat")) {
        this.state = { lat: defaultLocation.lat, lng: defaultLocation.lng };
      }
      return this.state;
    },
    getLocation: function () {
      navigator.geolocation.getCurrentPosition((position) => {
        this.markerLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        this.setCoordinates(this.markerLocation);
        this.updateFromLocation(this.markerLocation);
        this.map.panTo(this.markerLocation);
      });
    },

    getReplacements: function (address_components) {
      let replacements = {};

      address_components.forEach((component) => {
        for (const symbol in this.symbols) {
          if (this.symbols[symbol].indexOf(component.types[0]) !== -1) {
            if (symbol === symbol.toLowerCase()) {
              replacements[symbol] = component.short_name;
            } else {
              replacements[symbol] = component.long_name;
            }
          }
        }
      });

      if (debug) {
        console.log(replacements);
      }

      return replacements;
    },

    instanceOverlay: function (feature) {
      var instance = null;
      switch (feature.properties.type) {
        case google.maps.drawing.OverlayType.MARKER:
          instance = new google.maps.Marker({
            id: feature.properties.id,
            type: feature.properties.type,
            position: new google.maps.LatLng(
              feature.geometry.coordinates[1],
              feature.geometry.coordinates[0]
            ),
            draggable: true,
          });
          break;
        case google.maps.drawing.OverlayType.RECTANGLE:
          var NE = new google.maps.LatLng(
            feature.geometry.coordinates[0][2][1],
            feature.geometry.coordinates[0][2][0]
          );
          var SW = new google.maps.LatLng(
            feature.geometry.coordinates[0][0][1],
            feature.geometry.coordinates[0][0][0]
          );
          instance = new google.maps.Rectangle(
            Object.assign({}, this.polyOptions, {
              id: feature.properties.id,
              type: feature.properties.type,
              // fillColor: feature.properties.color,
              bounds: new google.maps.LatLngBounds(SW, NE),
              editable: false,
            })
          );
          break;
        case google.maps.drawing.OverlayType.POLYGON:
          instance = new google.maps.Polygon(
            Object.assign({}, this.polyOptions, {
              id: feature.properties.id,
              type: feature.properties.type,
              // fillColor: feature.properties.color,
              paths: this.transformToMVCArray(feature.geometry.coordinates),
              editable: false,
            })
          );
          break;
        case google.maps.drawing.OverlayType.POLYLINE:
          instance = new google.maps.Polyline({
            id: feature.properties.id,
            type: feature.properties.type,
            // strokeColor: feature.properties.color,
            path: this.transformToMVCArray([
              feature.geometry.coordinates,
            ]).getAt(0),
            draggable: true,
            editable: false,
          });
          break;
        case google.maps.drawing.OverlayType.CIRCLE:
          instance = new google.maps.Circle(
            Object.assign({}, this.polyOptions, {
              id: feature.properties.id,
              type: feature.properties.type,
              // fillColor: feature.properties.color,
              center: new google.maps.LatLng(
                feature.geometry.coordinates[1],
                feature.geometry.coordinates[0]
              ),
              radius: feature.properties.radius,
              editable: false,
            })
          );
          break;
      }
      // instance.zIndex = this.overlays.length + 1;
      return instance;
    },

    instanceFeature: function (overlay) {
      var calculatedOverlay = this.calculateGeometry(overlay);
      return this.dataLayer.add(
        new google.maps.Data.Feature({
          geometry: calculatedOverlay.geometry,
          properties: Object.assign(
            {
              id: this.guid(),
              type: overlay.type,
            },
            calculatedOverlay.hasOwnProperty("properties")
              ? calculatedOverlay.properties
              : {}
          ),
        })
      );
    },

    calculateGeometry: function (overlay, geometryOnly) {
      switch (overlay.type) {
        case google.maps.drawing.OverlayType.MARKER:
          return geometryOnly
            ? new google.maps.Data.Point(overlay.getPosition())
            : {
                geometry: new google.maps.Data.Point(overlay.getPosition()),
              };
        case google.maps.drawing.OverlayType.RECTANGLE:
          let b = overlay.getBounds(),
            p = [
              b.getSouthWest(),
              {
                lat: b.getSouthWest().lat(),
                lng: b.getNorthEast().lng(),
              },
              b.getNorthEast(),
              {
                lng: b.getSouthWest().lng(),
                lat: b.getNorthEast().lat(),
              },
            ];
          return geometryOnly
            ? new google.maps.Data.Polygon([p])
            : {
                geometry: new google.maps.Data.Polygon([p]),
              };
        case google.maps.drawing.OverlayType.POLYGON:
          return geometryOnly
            ? new google.maps.Data.Polygon([overlay.getPath().getArray()])
            : {
                geometry: new google.maps.Data.Polygon([
                  overlay.getPath().getArray(),
                ]),
              };
        case google.maps.drawing.OverlayType.POLYLINE:
          return geometryOnly
            ? new google.maps.Data.LineString(overlay.getPath().getArray())
            : {
                geometry: new google.maps.Data.LineString(
                  overlay.getPath().getArray()
                ),
              };
        case google.maps.drawing.OverlayType.CIRCLE:
          return geometryOnly
            ? new google.maps.Data.Point(overlay.getCenter())
            : {
                properties: {
                  radius: overlay.getRadius(),
                },
                geometry: new google.maps.Data.Point(overlay.getCenter()),
              };
      }
    },

    transformToMVCArray: function (a) {
      let clone = new google.maps.MVCArray();

      function transform($a, parent) {
        if ($a.length == 2 && !Array.isArray($a[0]) && !Array.isArray($a[1]))
          parent.push(new google.maps.LatLng($a[1], $a[0]));
        for (let a = 0; a < $a.length; a++) {
          if (!Array.isArray($a[a])) continue;
          transform(
            $a[a],
            parent
              ? $a[a].length == 2 &&
                !Array.isArray($a[a][0]) &&
                !Array.isArray($a[a][1])
                ? parent
                : parent.getAt(parent.push(new google.maps.MVCArray()) - 1)
              : clone.getAt(clone.push(new google.maps.MVCArray()) - 1)
          );
        }
      }

      function isMVCArray(array) {
        return array instanceof google.maps.MVCArray;
      }

      transform(a);

      return clone;
    },

    loadFeaturesCollection: function (geoJSON) {
      if (Array.isArray(geoJSON.features) && geoJSON.features.length > 0) {
        let bounds = new google.maps.LatLngBounds();
        for (let f = 0; f < geoJSON.features.length; f++) {
          let overlay = this.instanceOverlay(geoJSON.features[f]);
          overlay.feature = this.instanceFeature(overlay);
          this.addOverlayEvents(overlay);
          overlay.feature.getGeometry().forEachLatLng(function (latlng) {
            bounds.extend(latlng);
          });
          // overlay.feature.setProperty("color", features[f].properties.color);
          overlay.setMap(this.map);
          this.overlays.push(overlay);
        }
        this.map.fitBounds(bounds);
      }
    },

    addOverlayEvents: function (overlay) {
      switch (overlay.type) {
        case google.maps.drawing.OverlayType.POLYLINE:
          google.maps.event.addListener(overlay.getPath(), "set_at", () => {
            if (!overlay.drag) {
              overlay.feature.setGeometry(
                this.calculateGeometry(overlay, true)
              );
              this.drawingModified();
            }
          });
          google.maps.event.addListener(overlay.getPath(), "insert_at", () => {
            overlay.feature.setGeometry(this.calculateGeometry(overlay, true));
            this.drawingModified();
          });
          google.maps.event.addListener(overlay.getPath(), "remove_at", () => {
            overlay.feature.setGeometry(this.calculateGeometry(overlay, true));
            this.drawingModified();
          });
          break;
        case google.maps.drawing.OverlayType.POLYGON:
          const paths = overlay.getPaths();
          for (let p = 0; p < paths.getLength(); p++)
            for (let sp = 0; sp < paths.getAt(p).getLength(); sp++) {
              google.maps.event.addListener(paths.getAt(p), "set_at", () => {
                if (!overlay.drag) {
                  overlay.feature.setGeometry(
                    this.calculateGeometry(overlay, true)
                  );
                  this.drawingModified();
                }
              });
              google.maps.event.addListener(paths.getAt(p), "insert_at", () => {
                overlay.feature.setGeometry(
                  this.calculateGeometry(overlay, true)
                );
                this.drawingModified();
              });
              google.maps.event.addListener(paths.getAt(p), "remove_at", () => {
                overlay.feature.setGeometry(
                  this.calculateGeometry(overlay, true)
                );
                this.drawingModified();
              });
            }
          break;
        case google.maps.drawing.OverlayType.RECTANGLE:
          google.maps.event.addListener(overlay, "bounds_changed", () => {
            if (!overlay.drag) {
              overlay.feature.setGeometry(
                this.calculateGeometry(overlay, true)
              );
              this.drawingModified();
            }
          });
          break;
        case google.maps.drawing.OverlayType.CIRCLE:
          google.maps.event.addListener(overlay, "radius_changed", () => {
            overlay.feature.setProperty(
              "radius",
              this.calculateGeometry(overlay).properties.radius
            );
            this.drawingModified();
          });
          break;
      }
      if (overlay.type !== google.maps.drawing.OverlayType.MARKER) {
        let self = this;
        google.maps.event.addListener(overlay, "click", function (event) {
          self.setSelection(this);
        });
      }
      google.maps.event.addListener(overlay, "dragstart", () => {
        overlay.drag = true;
      });
      google.maps.event.addListener(overlay, "mouseup", () => {
        if (overlay.drag) {
          overlay.drag = false;
          overlay.feature.setGeometry(this.calculateGeometry(overlay, true));
          this.drawingModified();
        }
      });
    },

    drawingModified: function () {
      if (drawingField) {
        this.dataLayer.toGeoJson((obj) => {
          setStateUsing(drawingField, JSON.stringify(obj));
        });
      }
    },

    guid: function () {
      function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
          .toString(16)
          .substring(1);
      }

      return (
        s4() +
        s4() +
        "-" +
        s4() +
        "-" +
        s4() +
        "-" +
        s4() +
        "-" +
        s4() +
        s4() +
        s4()
      );
    },

    setSelection: function (shape) {
      this.clearSelection();
      this.selectedShape = shape;
      // shape.setEditable(true);
      // selectColor(shape.get('fillColor') || shape.get('strokeColor'));
      this.overlays.forEach(function (item) {
        if (shape && item.id == shape.id) {
          if (item.getEditable()) {
            shape.setOptions({
              strokeColor: "#00ff00",
              strokeOpacity: "0.5",
            });
          } else {
            shape.setOptions({
              strokeColor: "#ff0000",
              strokeOpacity: "0.8",
            });
          }
          item.setEditable(!item.getEditable());
        } else {
          item.setEditable(false);
        }
      });
    },

    clearSelection: function () {
      this.selectedShape = null;

      this.overlays.forEach(function (item) {
        item.setEditable(false);
        item.setOptions({
          // strokeColor: '#f06eaa'
          strokeColor: "#00ff00",
          strokeOpacity: "0.5",
        });
      });
    },

    geoJsonContains: function (latLng) {
      if (geoJson && geoJsonField) {
        let features = [];
        let dataLayer = new google.maps.Data();
        this.geoJsonDataLayer.forEach((feature) => {
          if (feature.getGeometry().getType() === "Polygon") {
            var poly = new google.maps.Polygon({
              path: feature.getGeometry().getAt(0).getArray(),
            });
            if (google.maps.geometry.poly.containsLocation(latLng, poly)) {
              if (geoJsonProperty) {
                features.push(feature.getProperty(geoJsonProperty));
              } else {
                dataLayer.add(feature);
              }
            }
          }
          if (feature.getGeometry().getType() === 'MultiPolygon') {
                let array = feature.getGeometry().getArray();
                array.forEach(function(item,i){

                    let  coords = item.getAt(0).getArray();
                    let poly = new google.maps.Polygon({
                        paths: coords
                    });
                    if (google.maps.geometry.poly.containsLocation(latLng, poly)) {
                        if (geoJsonProperty) {
                            features.push(feature.getProperty(geoJsonProperty))
                        } else {
                            dataLayer.add(feature);
                        }
                    }
                });
          }
        });

        let fieldContent;
        if (geoJsonProperty) {
          fieldContent = JSON.stringify(features);
          setStateUsing(geoJsonField, fieldContent);
        } else {
          dataLayer.toGeoJson((gj) => {
            fieldContent = JSON.stringify(gj);
            setStateUsing(geoJsonField, fieldContent);
          });
        }
      }
    },
  };
}
