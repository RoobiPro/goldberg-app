<template>
<l-map style="height: 350px" :zoom="zoom" :center="center">
<l-control-layers position="topright"  ></l-control-layers>
<l-tile-layer
      v-for="tileProvider in tileProviders"
      :key="tileProvider.name"
      :name="tileProvider.name"
      :visible="tileProvider.visible"
      :url="tileProvider.url"
      :attribution="tileProvider.attribution"

      layer-type="base"/>
      <!-- Use default icon -->
<l-marker :lat-lng="center" ></l-marker>
</l-map>
</template>

<script>
import {LMap, LTileLayer, LMarker, LControlLayers} from 'vue2-leaflet';


export default {
  props: ['coordinates_x', 'coordinates_y'],
  components: {
    LMap,
    LTileLayer,
    LControlLayers,
    LMarker,

  },
  data () {
    return {
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      zoom: 13,
      center: [parseFloat(this.coordinates_x), parseFloat(this.coordinates_y)],
      // markers: [
      //   parseFloat(this.coordinates_x), parseFloat(this.coordinates_y)
      // ],
      tileProviders: [

        {
          name: 'OpenTopoMap',
          visible: false,
          url: 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
          // attribution:
          //   'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
        },
        {
          name: 'Esri.WorldImagery',
          url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
          // attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'

        }
      ],
    };
  }
}
</script>
