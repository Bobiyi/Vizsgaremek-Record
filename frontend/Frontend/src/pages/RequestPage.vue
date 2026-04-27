<script setup>
import { Cookies, useQuasar} from 'quasar';
import { onMounted, ref  } from "vue";
import { useRouter, useRoute } from 'vue-router'
import { useStore } from "../stores/store";

const Router = useRouter()
const route = useRoute()
const store = useStore();
const urlId = route.params.id

const $q = useQuasar()

if(!Cookies.has('user') || !(Cookies.get('user').user.role == 'admin')){
    $q.notify({
        message: 'Nincs engedélyed erre az oldalra',
        color: 'red',
        position: 'top',
        timeout: 3000,
        icon: 'cancel'
        })
  Router.push("/login")
}

let artistList = ref([])
onMounted(async () => {
  await store.getRequest(urlId, Cookies.get('user').token)
  await store.getArtists();
  artistList.value = [...store.artists]
});

const adminNote = ref("")
const recordArtist = ref(null)

async function accept(){
  let requestPayload = {'adminNote': adminNote.value}
  await store.acceptRequest(store.request.id, requestPayload, Cookies.get('user').token)
  if(recordArtist.value != null){
    let joinPayload = {
      'artistId' : recordArtist.value.id,
      'recordId' : store.response.insertedId,
    }
    await store.joinRecordArtist(joinPayload, Cookies.get('user').token)
  }
  
  Router.back()
}

function decline(){
  let payload = {'adminNote': adminNote.value}
  store.rejectRequest(store.request.id, payload, Cookies.get('user').token)
  Router.back()
}


</script>

<template>
  <q-page class="flex flex-center" v-if="store.request != null">
    <table>

      <thead>
        <tr>
          <td>id: {{ store.request.id }}</td>
          <td>{{ store.request.type === 'new_artist'? "Új kiadó" : "Új lemez" }}</td>
          <td>{{ store.request.userId + " - " + store.request.userName }}</td>
          <td>{{ store.request.createdAt }}</td>
        </tr>
      </thead>

      <tbody v-if="store.request.type === 'new_artist'" class="text-white">
        <tr>
          <td colspan="2">Nev:</td>
          <td colspan="2">{{store.request.data.artistName }}</td>
        </tr>
        <tr>
          <td colspan="2">Miota aktiv:</td>
          <td colspan="2">{{ store.request.data.activeSince === null ? "null" : store.request.data.activeSince }}</td>
        </tr>
        <tr>
          <td colspan="2">Csoport:</td>
          <td colspan="2">{{ store.request.data.isGroup? "igen" : "nem" }}</td>
        </tr>
        <tr>
          <td colspan="2">Nemzetiség:</td>
          <td colspan="2">{{ store.request.data.artistNationality === null ? "null" : store.request.data.artistNationality }}</td>
        </tr>
        <tr>
          <td colspan="2">Weboldal:</td>
          <td colspan="2">{{ store.request.data.artistWebsite === null ? "null" : store.request.data.artistWebsite}}</td>
        </tr>
      </tbody>
      <tbody v-if="store.request.type === 'new_record'">
        <tr>
          <td colspan="2">Neve:</td>
          <td colspan="2">{{ store.request.data.recordName }}</td>
        </tr>
        <tr>
          <td colspan="2">Kiadva:</td>
          <td colspan="2">{{ store.request.data.releaseYear === null ? "null" : store.request.data.releaseYear }}</td>
        </tr>
        <tr>
          <td colspan="2">Tipus:</td>
          <td colspan="2">{{ store.request.data.type }}</td>
        </tr>
        <tr>
          <td colspan="2">Hossz:</td>
          <td colspan="2">{{ store.request.data.length === null ? "null" : store.request.data.length }}</td>
        </tr>
  
      </tbody>

      <tfoot>
         <tr v-if="store.request.type === 'new_record'">
          <td colspan="2">Kiadó:</td>
          <td colspan="2" v-if="artistList != null">
            <q-select
              standout="bg-white text-black"
              v-model="recordArtist"
              :options="artistList"
              option-label="artistName"
              option-value="id"
              label="Kiadó"
              class="input"
            />    
          </td>
        </tr>
        <tr><td colspan="4">
        <q-input
          filled
          v-model="adminNote"
          label="Megjegyzés"
          class="input"
          color="white"
        />
        </td></tr>
        <tr>
          <td colspan="3"><q-btn class="button" @click="accept">Request elfogadasa</q-btn></td>
          <td><q-btn class="button" @click="decline">Request elutasitasa</q-btn></td>
        </tr>
      </tfoot>
    </table>
  </q-page>
</template>




<style lang="scss" scoped>
.q-field--focused :deep(.q-field__control) {
  background-color: $deep-purple-13 !important;
}
.input :deep(.q-field__label) {
  color: rgb(224, 224, 224) !important;
}
.input :deep(input.q-field__native){
    color: white !important;
}
.input :deep(.q-field__native span) {
  color: white !important;
}
td{
  color: white;
  font-size: xx-large;
}
thead, tfoot{
  background-color: $deep-purple-13;
}
thead tr td{
  text-align: center;
}

tbody{
  background-color: rgb(36, 29, 29);
}

table{
  width: 60em;
}
.button{
  width: 100%;
  height: 100%;
  background-color: rgb(130, 0, 252);
}

</style>