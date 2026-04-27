<script setup>
import { Cookies, useQuasar } from 'quasar';
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useStore } from 'src/stores/store';
import { useUtils } from 'src/composables/useUtils';

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

onMounted(async () => {
  await store.getArtistById(urlId);
  setValues();
});

const nationalityList = useUtils().nationalityList()
//artist data
const artistName = ref("")
const artistActive = ref("")
const artistNationality = ref("")
const artistWebsite = ref("")
const artistIsGroup = ref(false)
const artistIconImage = ref(null)
const artistBannerImage = ref(null)

function setValues(){
    artistName.value = store.artist.artistName
    artistActive.value = store.artist.activeSince
    artistNationality.value = nationalityList.find(e => e.code == store.artist.nationality)
    artistWebsite.value = store.artist.website
    artistIsGroup.value = store.artist.isGroup
  }


function onSubmit(){
  if(artistName.value == ""){
       $q.notify({
        message: 'Kérlek a kötelező mezőket töltsed ki!',
        color: 'red',
        position: 'top',
        timeout: 3000,
        icon: 'cancel'
        });
    }else{ 
      const formData = new FormData()

      formData.append("artistName", artistName.value)
      formData.append("isGroup",artistIsGroup.value ? 1 : 0)
      if(artistActive.value != null){formData.append('activeSince', artistActive.value)}
      if(artistNationality.value != null){formData.append('artistNationality', artistNationality.value.code)}
      if(artistWebsite.value != null){formData.append('artistWebsite', artistWebsite.value)}

      if(artistIconImage.value != null){formData.append('artistIcon', artistIconImage.value)}
      if(artistBannerImage.value != null){formData.append('artistCover', artistBannerImage.value)}


      formData.append('_method', 'put')


      store.updateArtist(formData, Cookies.get('user').token, route.params.id)
      Router.push(`/artists/${store.artist.id}`)
    }
}


</script>

<template>

<q-page class="q-pa-md row q-gutter-md flex flex-center ">





        <!--ARTIST REQUEST-->
<q-form
  @submit="onSubmit"
  class="q-gutter-md"
  v-if="store.artist != null"
>
<h4 >Kiadó frissítés</h4>

 <q-input
        filled
        v-model="artistName"
        label="Előadó *"
        lazy-rules
        color="deep-purple-13"
        class="input"
      />

      <q-input
        filled
        type="number"
        v-model="artistActive"
        label="Mióta aktív az előadó"
        lazy-rules
        color="deep-purple-13"        
        class="input"
      />
      <q-select
        standout="bg-white text-black"
        v-model="artistNationality"
        :options="nationalityList"
        option-label="name"
        option-value="code"
        label="Kiadó származása"
        class="input"
      />

      <q-input
        filled
        v-model="artistWebsite"
        label="Weboldal"
        lazy-rules
        color="deep-purple-13"
        class="input"
        type="url"
      />
      <div style="display: flex;align-items: center;justify-content: left;"  >
        <q-toggle v-model="artistIsGroup" color="deep-purple-13" keep-color />
        <h6>Csoport</h6>
      </div>
<div>
        <h6>Profil kép:</h6>
        <q-input
          @update:model-value="
            val => {
              artistIconImage = val[0]
            }
          "
          filled
          type="file"
          class="input"
        />
        <q-btn icon="info" class="info" flat round color="white">
          <q-tooltip>Csak .jpg-t fogad el!</q-tooltip>
        </q-btn>
</div>

      <div>
        <h6>Borító kép:</h6>
        <q-input
          @update:model-value="
            val => {
              artistBannerImage = val[0]
            }
          "
          filled
          type="file"
          class="input"
        />
        <q-btn icon="info" class="info" flat round color="white">
          <q-tooltip>Csak .jpg-t fogad el!</q-tooltip>
        </q-btn>
      </div>
      <div>
        <q-btn label="Frissítés" type="submit" color="deep-purple-13"/>
        <q-btn label="Mégsem" :to="`/artists/${store.artist.id}`" color="deep-purple-13" flat class="q-ml-sm" />

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