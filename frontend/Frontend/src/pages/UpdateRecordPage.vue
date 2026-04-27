<script setup>
import { Cookies, useQuasar } from 'quasar';
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useStore } from 'src/stores/store';

const store = useStore()
const $q = useQuasar()
const Router = useRouter()
const route = useRoute()
const urlId = route.params.id

if(!(Cookies.has('user')) || Cookies.get('user').user.role != 'admin' ){
   $q.notify({
        message: 'Nincs jogosultságod erre az oldalra',
        color: 'red',
        position: 'top',
        timeout: 3000,
        icon: 'cancel'
    });
    Router.push("/")
}
let artistList = ref([])
onMounted(async () => {
  await store.getRecordById(urlId);
  await store.getArtists();
  artistList.value = [...store.artists]
  setValues();
});
//record data
const recordTypeOptions = [
  {'id': 1, 'name' : "Album"},
  {'id': 2, 'name' : "EP"},
  {'id': 3, 'name' : "Single"}
]
const recordName = ref("")
const recordType = ref("")
const recordReleaseYear = ref(null)
const recordLength = ref(null);
const recordCover = ref(null)
const recordArtist = ref(null)

function setValues(){
   recordName.value = store.record.name
   recordType.value = recordTypeOptions.find(e => e.name === store.record.typeName)
   recordReleaseYear.value = store.record.releaseYear
   recordLength.value = store.record.length
   recordArtist.value = store.record.artistName[0]
  }


function onSubmit(){
  if(recordName.value == "" && recordType.value == null){
       $q.notify({
        message: 'Kérlek a kötelező mezőket töltsed ki!',
        color: 'red',
        position: 'top',
        timeout: 3000,
        icon: 'cancel'
        });
    }else{ 
      const formData = new FormData()

      formData.append("recordName", recordName.value)
      formData.append("typeId",recordType.value.id)
      if(recordReleaseYear.value != null){formData.append('releaseYear', recordReleaseYear.value)}
      if(recordLength.value != null){ formData.append('length', recordLength.value)}
      if(recordCover.value != null) {formData.append("recordFile", recordCover.value)}
      formData.append('_method', 'put')

      store.updateRecord(formData, Cookies.get('user').token, route.params.id)
      Router.push(`/records/${store.record.id}`)
    }
    if(recordArtist.value?.id != null){
      let payload = {
        'artistId' : recordArtist.value.id,
        'recordId' : store.record.id,
      }
      store.joinRecordArtist(payload, Cookies.get('user').token)
    }
}


</script>

<template>

<q-page class="q-pa-md row q-gutter-md flex flex-center ">
    <q-form
      @submit="onSubmit"
      class="q-gutter-md"
      v-if="store.record != null"
    >
      <h4 >Lemez Módosítása</h4>

      <q-input
        filled
        v-model="recordName"
        label="Lemez neve"
        lazy-rules
        color="deep-purple-13"
        class="input"
      />

      <q-input
        filled
        type="number"
        v-model="recordReleaseYear"
        label="Kiadás éve"
        lazy-rules
        color="deep-purple-13"        
        class="input"
      />
      <q-select
        standout="bg-white text-black"
        v-model="recordType"
        :options="recordTypeOptions"
        option-label="name"
        option-value="id"
        label="Lemez tipusa *"
        class="input"
      />      
      <q-input
        filled
        type="number"
        v-model="recordLength"
        label="Zenék száma"
        lazy-rules
        color="deep-purple-13"        
        class="input"
      />
      <div>
        <h6>Borító kép:</h6>
        <q-input
          @update:model-value="
            val => {
              recordCover = val[0]
            }
          "
          filled
          type="file"
          class="input"
        />
      </div>
      <q-select
              standout="bg-white text-black"
              v-model="recordArtist"
              :options="artistList"
              option-label="artistName"
              option-value="id"
              label="Kiadó"
              class="input"
            />    
      <div>
        <q-btn label="Frissítés" type="submit" color="deep-purple-13"/>
        <q-btn label="Mégsem" :to="`/records/${store.record.id}`" color="deep-purple-13" flat class="q-ml-sm" />

      </div>
    </q-form>
    <!--ARTIST REQUEST VÉGE-->



</q-page>

</template>

<style lang="scss">
h6, h4{
  color: white;
  padding: 0px;
  margin: 0px;
}
h4{
  text-align: center;
  margin-bottom: 1em;
}
tr *{
    text-align: center;
    color: $deep-purple-13;
    font-size: xx-large;
}
.button{
    background-color: rgb(36, 36, 36);
    color: $deep-purple-13;
    margin-bottom: 2em;
}
.input{
  width: 25em;
  color: black;
  background-color: white;
}
.q-field__native {  // q-select text-je selectelés közben fekete legyen
  color: black !important; 
}
</style>