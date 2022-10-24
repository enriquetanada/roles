<template>
    <div>
        <Navigation />
        <div class="container mt-5">
          <GmapMap 
            :center="{lat:10, lng:10}"
            :zoom="7"
            map-type-id="terrain"
            style="width: 1300px; height: 700px">
          </GmapMap>
        </div>
    </div>
</template>
<script>
import Navigation from './Navigation';

export default {
  components: {
    Navigation
  }
}
</script>

