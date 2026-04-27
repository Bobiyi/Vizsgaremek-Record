<script setup>
import { Cookies, useQuasar } from 'quasar';
import { useRouter } from 'vue-router'
import { useStore } from "../stores/store";
import { onMounted } from "vue";

const store = useStore();
const $q = useQuasar();
const Router = useRouter();
if (!Cookies.has('user')) {
  $q.notify({
    message: 'Kérlek jelentkez be',
    color: 'red',
    position: 'top',
    timeout: 3000,
    icon: 'cancel'
  })
  Router.push("/login")
}
const user = Cookies.get('user')

function logout() {
  store.logout(user.token)
  Cookies.remove("user")
  Router.push("/login")
}

onMounted(async () => {
  if (Cookies.has("user")) {
    await store.getFavouritesRecord(Cookies.get("user").user.id)
  }

});

</script>


<template>
  <q-page v-if="user != null">

    <div class="main-container">

      <div class="profile-banner bg-dark q-pa-lg row items-center q-gutter-lg">
        <q-avatar size="110px" color="blue-grey-9">
          <q-icon name="person" size="60px" color="blue-grey-5" />
        </q-avatar>
        <div>
          <div class="text-caption text-blue-grey-4 q-mb-xs" style="letter-spacing: 0.08em">
            {{ Cookies.get("user").user.email }}
          </div>
          <div class="text-h3 text-white text-weight-bold q-mb-sm">
            {{ Cookies.get("user").user.userName }}
          </div>
          <div class="text-caption text-blue-grey-4 row items-center q-gutter-xs">
          </div>
        </div>
      </div>

      <div class="favorites" v-if="store.records != null && !(Cookies.get('user').user.role == 'admin')">
        <h3 style="color: white;">Kedvencek:</h3>
         <div class="q-pa-md row wrap q-gutter-md album-container" >


         <router-link class="router" :to="`../records/${record.id}`" v-for="record in store.records" :key="record.id">
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
            <div class="artistsName">{{ record.artistName }}</div>
          </q-card-section>
        </q-card>
      </router-link>
    </div>
      </div>


      <div class="button-container">
        <q-btn class="button" label="Kijelentkezés" @click="logout" />
        <q-btn v-if="user.user.role == 'admin'" class="button" label="Admin felület" to="/adminPanel" />
      </div>
    </div>

  </q-page>
</template>



<style lang="scss" scoped>
.album-container {
  display: flex;
  flex-wrap: wrap;
  flex-direction: row;
  justify-content: flex-start;
  align-items: flex-start;
  gap: 1em;
  margin-top: 5em;
  width: 100%;
}


h1 {
  color: $deep-purple-13;
}

.main-container {
  width: 80%;
  margin: auto;
}

.profile-banner {
  width: 50em;
  margin: auto;
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
}


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