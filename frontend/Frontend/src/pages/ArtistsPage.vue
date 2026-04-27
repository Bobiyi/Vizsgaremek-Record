<script setup>
import { useStore } from "../stores/store";
import { onMounted } from "vue";
import { useUtils } from "src/composables/useUtils";

const store = useStore();

onMounted(() => {
  store.getArtists();
});



const { longNameFormatter } = useUtils()



</script>

<template>
<q-page class="flex flex-center">



  <div class="q-pa-md row q-gutter-md flex flex-center ">
      <router-link class="link" :to="`../artists/${artist.id}`" v-for="artist in store.ArtistsSortedByName" :key="artist.id">

        <q-card class="artist-card">
          <div class="card-top" style="object-position: top;"
            :style="`background-image: url(${artist.artistCoverPath}); `"></div>
          <div class="avatar-wrapper">
            <q-avatar size="100px">
              <img :src="artist.artistIconPath">
            </q-avatar>
          </div>

          <q-card-section class="content-section">
            <div class="text-h6 artist-name">{{ longNameFormatter(artist.artistName) }}</div>
          </q-card-section>

        </q-card>

      </router-link>
    </div>




  </q-page>
</template>

<style scoped lang="scss">
.artist-card {
  width: 30em;
  height: 14em;
  overflow: hidden;
  position: relative;
  background-color: #00000000;
}

.artist-card:hover{
  transition: 0.5s ;
  background-color: #ffffff28;
}
.artist-card{
  transition: 0.5s ;
  background-color: #00000000;
}
.card-top {
  height: 140px;
  background-size: cover;
  background-position: center;
}

.avatar-wrapper {
  position: absolute;
  top: 90px;
  left: 20px;
}

.content-section {
  padding-top: 70px;
}
.link{
  text-decoration: none;
}
.text-h6{
  color: white;
  position: absolute;
  right: 10px;
  top: 15px;
}


</style>