<script setup>
import { Cookies, useQuasar, QTooltip } from 'quasar';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useUtils } from 'src/composables/useUtils';
import { useStore } from 'src/stores/store';

const store = useStore()
const $q = useQuasar()
const Router = useRouter()
let selected = ref(null);
if(!(Cookies.has('user'))){
   $q.notify({
        message: 'Kérlek jelentkezz be!',
        color: 'red',
        position: 'top',
        timeout: 3000,
        icon: 'cancel'
    });
    Router.push("/login")
}


const nationalityList = useUtils().nationalityList()
//artist data
const artistName = ref("")
const artistActive = ref("")
const artistNationality = ref("")
const artistWebsite = ref("")
const artistIsGroup = ref(false)
const artistIconImage = ref(null)
const artistBannerImage = ref(null)

let errors = []

//record data
const recordTypeOptions = [
  {'id': 1, 'name' : "Album"},
  {'id': 2, 'name' : "EP"},
  {'id': 3, 'name' : "Single"}
]

const recordName = ref("")
const recordType = ref(recordTypeOptions[0])
const recordReleaseYear = ref(null)
const recordLenght = ref(null);
const recordCover = ref(null)

//reset
function onReset(){
   artistName.value = ""
   artistActive.value = ""
   artistNationality.value = ""
   artistWebsite.value = ""
   artistIsGroup.value = false
   artistIconImage.value = null
   artistBannerImage.value = null
   recordName.value = ""
   recordType.value = recordTypeOptions[0]
   recordReleaseYear.value = null
   recordLenght.value = null
   recordCover.value = null
}


//record data

async function onSubmit(){
    //ARTIST REQUEST ------------------------------------
  if(selected.value == "artist"){

    const formData = new FormData()
    const payload = {}
    const type = Object.freeze({ type: 'new_artist' });

    if(artistName.value != ""){
      payload.artistName = artistName.value
      payload.isGroup = artistIsGroup.value ? 1 : 0
    }else{
      errors.push("Kérlek a kötelező mezőket töltsed ki!")
    }
    if(artistActive.value != ""){
      if(artistActive.value.length == 4 && artistActive.value >= 1264){
        payload.activeSince = artistActive.value
      }else{
        errors.push("Kérlek 1264 vagy nagyobb évszámot adjál meg!")
      }
    }

    if(artistNationality.value!= ""){
      payload.artistNationality = artistNationality.value.code
    }

    if(artistWebsite.value!= ""){
      payload.artistWebsite = artistWebsite.value
    }
    if(artistIconImage.value != null){
      if(artistIconImage.value.name.endsWith(".jpg")){
        formData.append('artistIcon', artistIconImage.value)
      }else{
        errors.push("Kérlek csak .jpg fájl-t tölts fel!")
      }
    }
    if(artistBannerImage.value != null){
      if(artistBannerImage.value.name.endsWith(".jpg")){
        formData.append('artistCover', artistBannerImage.value)
      }else{
        errors.push("Kérlek csak .jpg fájl-t tölts fel!")
      }
    }
    

    if(errors.length == 0){
      formData.append("payload", JSON.stringify(payload))
      formData.append("type", type.type)
      await store.createRequest(formData, Cookies.get('user').token)
      if(store.response?.message == "Request created!"){
        selected.value = null
        onReset()
      }
    }else{
      warningOut(errors);
    }
  }else{  //ARTIST REQUEST  END------------------------------------
  //RECORD REQUEST -------------------------------------------

      const formData = new FormData()
      const payload = {}
      const type = Object.freeze({ type: 'new_record' });

      if(recordName.value != "" && recordType.value != null){
        payload.recordName = recordName.value
        payload.typeId = recordType.value.id
      }else{
        errors.push("Kérlek a kötelező mezőket töltsed ki!")
      }

      if(recordReleaseYear.value != null){
        if(recordReleaseYear.value >= 1900){
          payload.releaseYear = recordReleaseYear.value
        }else{
          errors.push("Kérlek csak 1900 vagy nagyobb évszámot adjál meg!")
        }

      }

      if(recordLenght.value != null){
        if(recordLenght.value > 0){
          payload.lenght = recordLenght.value
        }else{
          errors.push("Kérem értelmes számot adjál meg!")
        }
      }
      if(recordCover.value != null) {
        if(recordCover.value.name.endsWith(".jpg")){
          formData.append("recordFile", recordCover.value)
        }else{
          errors.push("Kérlek csak .jpg fájl-t tölts fel!")
        }
      }


      if(errors.length == 0){ // ha nincs hiba
        formData.append("payload", JSON.stringify(payload))
        formData.append("type", type.type)
        await store.createRequest(formData, Cookies.get('user').token)
        if(store.response.message == "Request created!"){
          selected.value = null
          onReset()
        }
      }else{                  //ha van hiba
        warningOut(errors)
      }
    }
  }  //RECORD REQUEST END -------------------------------------------



  function warningOut(warnings){
    warnings.forEach(element => {
      $q.notify({
        message: element,
        color: 'red',
        position: 'top',
        timeout: 3000,
        icon: 'cancel'
        });
    });
    errors = []
  }
</script>

<template>

<q-page class="q-pa-md row q-gutter-md flex flex-center ">

    <table v-if="selected === null">
        <thead>
            <tr>
                <th colspan="2">
                <h2>Nem találod a kedvenc albumodat vagy kiadódat?</h2>
                <h2>Töltsd ki az alábbi űrlapok egyikét, és mi elintézzük a többit.</h2>
              </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <q-btn @click="selected = 'artist'" class="button">Új kiadó</q-btn>
                </td>
                <td>
                    <q-btn @click="selected = 'record'" class="button">Új lemez</q-btn>
                </td>
            </tr>
        </tbody>
    </table>



    <div v-if="selected != null">

          <q-btn @click="selected = null" class="button">Vissza</q-btn>
        <!--ARTIST REQUEST-->
        <q-form
      @submit="onSubmit"
      @reset="onReset"
      class="q-gutter-md"
      v-if="selected === 'artist'"
    >
              <h4 >Új kiadó</h4>

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
        <q-btn label="Létrehozás" type="submit" color="deep-purple-13"/>
        <q-btn label="Törlés" type="reset" color="deep-purple-13" flat class="q-ml-sm" />
      </div>
    </q-form>
    <!--ARTIST REQUEST VÉGE-->



    <!--RECORD REQUEST-->

    <q-form
      @submit="onSubmit"
      @reset="onReset"
      class="q-gutter-md"
      v-if="selected === 'record' && store.artists != null"
    >
      <h4 >Új lemez</h4>

      <q-input
        filled
        v-model="recordName"
        label="Lemez neve *"
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
        v-model="recordLenght"
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
        <q-btn icon="info" class="info" flat round color="white">
          <q-tooltip>Csak .jpg, és 1:1 kép árányt fogad el!</q-tooltip>
        </q-btn>
      </div>
 
      <div>
        <q-btn label="Létrehozás" type="submit" color="deep-purple-13"/>
        <q-btn label="Törlés" type="reset" color="deep-purple-13" flat class="q-ml-sm" />
      </div>
    </q-form>
    <!--ARTIST REQUEST VÉGE-->

    </div>


</q-page>

</template>

<style lang="scss">
thead h2{
  color: white;
  margin: 0px;
  font-weight: 700px;
}
.info{
  float: right;
  cursor: pointer;
}

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