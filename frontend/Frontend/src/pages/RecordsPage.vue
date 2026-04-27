<script setup>
import { useStore } from "../stores/store";
import { onMounted, ref } from "vue";
import { useUtils } from "src/composables/useUtils";
import { Cookies } from "quasar";

const { artistListFormatter } = useUtils()
const store = useStore();
const options = ["Cím", "Készítő", "Kiadás", "Tipus"]
const selected = ref("Cím")
let sortedRecords = ref([])


onMounted(async () => {
  await store.getRecords();
  sortedRecords.value = store.recordsSortedByName
  if (Cookies.has("user")) {
    await store.getFavourites(Cookies.get("user").user.id)
    await favouriteArrayMaker()
  }

});

//FILTER
function onChange() {
  switch (selected.value) {
    case 'Cím': sortedRecords.value = store.recordsSortedByName; break;
    case 'Készítő': sortedRecords.value = store.recordsSortedByArtistName; break;
    case 'Kiadás': sortedRecords.value = store.recordsSortedByRelease; break;
    case 'Tipus': sortedRecords.value = store.recordsSortedByType; break;
    default: sortedRecords.value = store.recordsSortedByName; break;
  }
}

let favouriteModel = ref({});

async function favouriteArrayMaker() {
  const tempModel = {};
  sortedRecords.value.forEach(e => tempModel[e.id] = false);
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



</script>




<template>
  <q-page class="flex flex-center">

    <q-select filled class="absolute-right filter my-select" v-model="selected" :options="options" label="Sort"
      color="purple-13" label-color="black" text-color="white" @update:model-value="onChange" />
    <!--albumok container-->

    <div class="q-pa-md row q-gutter-md flex flex-center album-container">

      <router-link class="router" :to="`../records/${record.id}`" v-for="record in sortedRecords" :key="record.id">
        <q-card class="record-card" flat bordered>
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
              <div>{{ record.name }}</div>
            </div>
            <div class="artistsName">{{ artistListFormatter(record.artistName) }}</div>
            <q-btn v-if="Cookies.has('user')" class="heart-toggle" @click.stop.prevent="favouriteToggle(record.id)" flat
              round icon="mdi-heart" :color="favouriteModel[record.id] ? 'red' : 'white'" size="1.5em" />
          </q-card-section>
        </q-card>
      </router-link>
    </div>

    <!--albumok container end-->



  </q-page>
</template>


<style lang="scss" scoped>
.heart-toggle :deep(.q-toggle__track),
.heart-toggle :deep(.q-focus-helper),
.heart-toggle :deep(.q-toggle__thumb::after) {
  display: none;
}

.heart-toggle :deep(.q-toggle__thumb) {
  left: 0 !important;
}


.heart-toggle {
  z-index: 10;
  position: absolute;
  left: 11.5em;
}

.album-container {
  margin-top: 5em;
}

.record-card {
  width: 100%;
  max-width: 30em;
  height: 28em;
  background-color: #00000000;
  border-radius: 12px;
  padding: 0.7em;
  margin-bottom: 0.7em;
}

.record-card:hover {
  transition: 0.3s;
  background-color: #8a8a8a5d;
}

.record-card {
  transition: 0.3s;
  background-color: #00000000;
}

.router {
  color: white;
  text-decoration: none;
}

.filter {
  margin-top: 1em;
  margin-right: 2em;
  width: 10em;
  height: 5em;
  margin-bottom: 0px;

}

.filter :deep(.q-field__control) {
  background-color: white !important;
  border-radius: 10px;
}



.artistsName {
  position: absolute;
  left: 1px;
  top: 3em;
  font-size: large;
  color: rgb(197, 195, 195);
}

.albumNameContainer {
  position: absolute;
  left: 1px;
  font-size: x-large;
  overflow: hidden;
  white-space: nowrap;
  width: 10em;
  mask: linear-gradient(270deg, transparent, white 10%);
}



</style>
