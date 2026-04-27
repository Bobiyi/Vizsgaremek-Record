<script setup>
import { Cookies } from "quasar";
import { useStore } from "../stores/store";
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from 'vue-router'
import { useUtils } from "src/composables/useUtils";

const { longNameFormatter } = useUtils()
  const route = useRoute();
  const Router = useRouter()
  const store = useStore();
  const urlId = route.params.id
  const biztos = ref(false)

onMounted(() => {
  store.getRecordById(urlId);
  store.getArtistsByRecordId(urlId);
});

  function deleteRecord(){
    if(!biztos.value){
      biztos.value = true
    }else{
      store.deleteRecord(store.record.id, Cookies.get('user').token)
      Router.back()
    }
  }

</script>

<template>
  <q-page v-if="store.record =! null">
    <!--album display-->
    <div class="row album" style="width: 80vw; margin: auto; padding-top: 3em;">
      <div class="col-md-5 col-sm-12 flex item-center justify-center" >
        <q-img :src="store.record.coverUrl" class="album-img">
          <template v-slot:error>
            <q-img class="album-img"  src="http://127.0.0.1:8000/storage/Other/MissingImage.jpg" />
          </template>
          <template v-if="store.record.coverUrl == null">
            <q-img class="album-img"  src="http://127.0.0.1:8000/storage/Other/MissingImage.jpg" />
          </template>
        </q-img>
      </div>

      <div class="col-md-7 col-sm-12  text">
        <h1>{{ store.record.name }}</h1>
        <h3>{{ store.record.typeName }}</h3>
        <h2 v-if="store.record.releaseYear != null">{{ store.record.releaseYear }}</h2>
        <h3 v-if="store.record.length != null">{{ store.record.length }} zene</h3>
      </div>
    </div>
    <!--album vége-->

    <!--artist(ok)-->
      <div class="row" style="width: 80vw; margin: auto;  margin-top: 4em;">
        <div class="q-pa-md row q-gutter-md flex flex-center ">
        <router-link class="link"  :to="`../artists/${artist.id}`" v-for="artist in store.artists" :key="artist.id">
  
      <q-card class="artist-card" >
          <div class="card-top" style="object-position: top;" :style="`background-image: url(${artist.artistCoverPath}); `"></div>
          <div class="avatar-wrapper">
            <q-avatar size="100px">
              <img :src="artist.artistIconPath">
            </q-avatar>
          </div>
  
          <q-card-section class="content-section">
            <div class="text-h6">{{longNameFormatter(artist.artistName) }}</div>
          </q-card-section>
  
        </q-card>
  
  </router-link>
   </div>
      </div>
    <!--artist(ok) vége-->

      <div class="button-container" v-if="Cookies.has('user') && Cookies.get('user').user.role == 'admin'">
        <q-btn class="button"  label="Frissítés" :to="`/adminPanel/update/record/${store.record.id}`" />
        <q-btn class="button" :label="biztos ? 'Biztos?' : 'Törlés'" @click="deleteRecord" />
      </div>
  </q-page>
</template>

<style lang="scss" scoped>

.album-img{
  max-height: 35em;
  max-width: 35em;
}
.text{
  color: white;
  text-align: left;
}





.artist-card {
  min-width: 22em;
  max-width: 30em;
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
.button-container {
  display: flex;
  align-items: center;
}

.button {
  background-color: $deep-purple-13;
  color: white;
  text-align: center;
  min-width: 100px;
  max-width: 200px;
  margin: auto;
  margin-top: 5em;
}


</style>

