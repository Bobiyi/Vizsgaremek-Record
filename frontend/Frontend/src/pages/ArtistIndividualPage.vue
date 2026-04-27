<script setup>
import { useStore } from "../stores/store";
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from 'vue-router'
import { Cookies } from "quasar";
import { useUtils } from 'src/composables/useUtils';

  const Router = useRouter()
  const route = useRoute();
  const store = useStore();
  const urlId = route.params.id
  const biztos = ref(false)

onMounted(async () => { 
  await store.getArtistById(urlId);
  await store.getRecordByArtistId(urlId);
  if(Cookies.has("user")){
    await store.getFavourites(Cookies.get("user").user.id)
    await favouriteArrayMaker()
  }
});


let favouriteModel = ref({});

async function favouriteArrayMaker() {
  const tempModel = {};
  store.records.forEach(e => tempModel[e.id] = false);
  store.favourites.forEach(e => {
    if (e.recordId in tempModel) tempModel[e.recordId] = true;
  });
  favouriteModel.value = tempModel;
}

function favouriteToggle(albumId) {
  if (Cookies.has("user")) {
    favouriteModel.value[albumId] = !favouriteModel.value[albumId];
    store.toggleFavourite(albumId, Cookies.get("user").token);
  }
}
  function deleteArtist(){
    if(!biztos.value){
      biztos.value = true
    }else{
      store.deleteArtist(store.artist.id, Cookies.get('user').token)
      Router.back()
    }
  }


</script>

<template>
  <q-page v-if="store.artist != null && store.records != null" style="color: white;">

    <!--banner-->
    <q-img
    style="height: 25em; "
    :src="store.artist.artistCoverPath"
    fit="cover"
    class="banner"
  > 
  <div class="absolute-bottom-left q-pa-md caption ">
  <div class="text-white q-px-sm q-py-sm flex"> 
    <h1 style="vertical-align: middle; font-weight:bold;">{{ store.artist.artistName }}</h1>
  </div>
  </div>
  </q-img>
  <p>{{ store.artist.activeSince != null ? store.artist.activeSince+" óta aktív" : "" }} {{ useUtils().getNationalityByCode(store.artist.nationality) != "Nem ismert" ? useUtils().getNationalityByCode(store.artist.nationality).toLowerCase()+"i" : "" }} {{ store.artist.isGroup ? 'csoport' : 'zenész' }}</p>

  <!--featured albums-->
  <div class="featured-albums ">
    <h4>Lemezek:</h4>
    <q-virtual-scroll
      :items="store.records"
      virtual-scroll-horizontal
      v-slot="{ item: record, index }"
      class="scroll"
    >
      <div
        :key="index"
        class="row"
        style="margin-right: 1em;"
      >
      <!--album card-->
      <router-link :to="`../records/${record.id}`" class="router ">
            <q-card class="record-card" flat >
              <q-img :src="record.coverUrl" class="album-img rounded-borders ">
          <template v-slot:error>
            <q-img class="album-img"  src="http://127.0.0.1:8000/storage/Other/MissingImage.jpg" />
          </template>
          <template v-if="record.coverUrl == null">
            <q-img class="album-img"  src="http://127.0.0.1:8000/storage/Other/MissingImage.jpg" />
          </template>
              </q-img>

              <q-card-section>
                <div class="albumNameContainer">
                  <div>{{record.name }}</div>
                </div>
                <q-btn
                  v-if="Cookies.has('user')"
                  class="heart-toggle"
                  @click.stop.prevent="favouriteToggle(record.id)"
                  flat
                  round
                  icon="mdi-heart"
                  :color="favouriteModel[record.id] ? 'red' : 'white'"
                  size="1.5em"
                />  
              </q-card-section>
        </q-card>
    </router-link>
 
    <!--album card END-->
    </div>
  </q-virtual-scroll>
   </div>

  <a :href="store.artist.website" target="_blank" class="website">{{ store.artist.website }}</a>

  <!--featured albums END-->
<div class="button-container" v-if="Cookies.has('user') && Cookies.get('user').user.role == 'admin'">
        <q-btn class="button"  label="Frissítés" :to="`/adminPanel/update/artist/${store.artist.id}`" />
        <q-btn class="button" :label="biztos ? 'Biztos?' : 'Törlés'" @click="deleteArtist" />
</div>




  </q-page>
</template>

<style lang="scss" scoped>
:deep(.scroll::-webkit-scrollbar) {
  height: 10px; /* use height for horizontal scroll */
}

:deep(.scroll::-webkit-scrollbar-track) {
  background-color: $dark;
}

:deep(.scroll::-webkit-scrollbar-thumb) {
  background-color: #414141;
  border-radius: 4px;
}
p{
  font-size: xx-large;
  margin-left: 1.5em;
  padding-top: 1em;
}
.website{
  font-size: xx-large;
  margin-left: 1.5em;
  padding-top: 1em;
  color: $deep-purple-13;
}
table{
  width: 20em;
}

td:nth-child(2){
  text-align: right;

}

.featured-albums{
  padding-left: 3em;
  padding-bottom: 2em;
  margin-top: 1em;
}

.absolute-bottom-left{
  border-radius: 20px;
  margin: 10px;
  padding: 0;
}
h6{
  padding: 0px;
  padding-left: 10px;
  margin: auto;
}

.router{
  color: white;
  text-decoration: none;
}
.caption{
  background-color: #00000000 !important;
}
.banner :deep(.q-img__image){
    filter: brightness(0.7);
}

.albumName{
position: absolute;
left: 1px;
font-size: x-large;
}
.artistsName{
  position: absolute;
  left: 1px;
  top: 3em;
  font-size: large;
  color: rgb(197, 195, 195);
}
.record-card{
  width: 100%;
  max-width: 30em;
  height: 26.5em;
  background-color: #00000000;
  border-radius: 12px;
  padding: 0.7em;
  margin-bottom: 0.7em;
}
.record-card:hover{
  transition: 0.3s;
  background-color: #8a8a8a5d;
}
.record-card{
  transition: 0.3s;
  background-color: #00000000;
}
.router{
  color: white;
  text-decoration: none;
}
.heart-toggle :deep(.q-toggle__track), .heart-toggle :deep(.q-focus-helper), .heart-toggle :deep(.q-toggle__thumb::after)  {
  display: none;
}
.heart-toggle :deep(.q-toggle__thumb) {
  left: 0 !important;
}


.heart-toggle{
  z-index: 10;
  position: absolute;
  left: 11.5em;
  top: 0.3em;
  
}
.albumNameContainer{
position: absolute;
left: 1px;
font-size: x-large;
overflow: hidden;
white-space:nowrap;
width: 10em;
mask: linear-gradient(270deg,transparent, white 10%);
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

.button-container {
  display: flex;
  align-items: center;
}
</style>